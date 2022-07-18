<?php

declare (strict_types=1);

namespace app\dao\slave;

use app\dao\PlaceDeclareDao;
use app\model\slave\PlaceSlave;
use app\model\slave\PlaceDeclareSlave;
use think\facade\Db;
use app\dao\PlaceDeclareNodeDao;

class PlaceDeclareSlaveDao extends PlaceDeclareDao
{
    
    protected function setModel(): string
    {
        return PlaceDeclareSlave::class;
    }

    public function getPartPlaceListTotal()
    {
        return app()->make(PlaceSlave::class)::where('create_time', '<', strtotime('-1 days'))->count('id');
    }

    public function getPartPlaceList($param)
    {
        $where = [];
        $where[] = ['id', '>', $param['_where_id_lg']];
        $where[] = ['create_time', '<', strtotime('-1 days')];
        return app()->make(PlaceSlave::class)::field('id,yw_street,code,name,short_name,link_man,link_phone,addr,create_time')->where($where)->limit(100)->select()->toArray();
        //return Db::connect('mysql_slave')->query("SELECT id,yw_street,code,name,short_name,link_man,link_phone,addr,FROM_UNIXTIME(create_time) create_time FROM eb_place WHERE ifnull(delete_time, 0)=0 and create_time < :create_time and id > :_where_id_lg limit 100", ['create_time' => strtotime('-1 days'), '_where_id_lg' => $param['_where_id_lg']]);
    }

    public function getTodaySummaryPartList($param)
    {
        if(isset($param['node_id']) && $param['node_id'] > 0) {
            $node = app()->make(PlaceDeclareNodeDao::class)->get($param['node_id']);
            $table_name = 'place_declare'. $node['suffix'];
        }else{
            $table_name = 'place_declare';
        }
        return Db::connect('mysql_slave')->name($table_name)->field('place_code,count(id) cishu')->where('create_date', $param['create_date'])->where('place_code', 'in', $param['place_code'])->group('place_code')->select()->toArray();
        //return Db::connect('mysql_slave')->query("SELECT place_code,count(id) cishu FROM eb_place_declare WHERE create_date = :create_date and place_code in (:place_code_str) GROUP BY place_code", ['create_date' => $param['create_date'], 'place_code_str' => $param['place_code']]);
    }

    // public function getTodaySummaryList($param)
    // {
    //     return Db::connect('mysql_slave')->query("SELECT a.id,a.yw_street,a.code,a.name,a.short_name,a.link_man,a.link_phone,a.addr,FROM_UNIXTIME( a.create_time ) create_time,ifnull( b.cishu, 0 ) cishu FROM eb_place a 
    //         LEFT JOIN(SELECT place_code,count(*) cishu FROM (SELECT place_code,id_card,create_time FROM eb_place_declare WHERE create_date = :create_date ) a GROUP BY place_code ) b 
    //         ON b.place_code = a.code WHERE ifnull( a.delete_time, 0 )=0 and a.id > :_where_id_lg limit 100", ['create_date' => $param['create_date'], '_where_id_lg' => $param['_where_id_lg']]);
    // }

    public function codeSendCompleteNum()
    {
        return Db::connect('mysql_slave')->query("SELECT yw_street,count(id) mashu from (select * from eb_place where ifnull(delete_time,0)=0) a group by yw_street");
    }
    
    public function dayScanCodeTime($param)
    {
        if(isset($param['node_id']) && $param['node_id'] > 0) {
            $node = app()->make(PlaceDeclareNodeDao::class)->get($param['node_id']);
            $table_name = 'eb_place_declare'. $node['suffix'];
        }else{
            $table_name = 'eb_place_declare';
        }
        return Db::connect('mysql_slave')->query("SELECT yw_street,count(id) cishu from (select yw_street,create_time,id_card,id 
            from ". $table_name ." where create_date= :create_date ) a GROUP BY yw_street", ['create_date' => $param['create_date']]);
    }
    
    public function dayActiveCode($param)
    {
        if(isset($param['node_id']) && $param['node_id'] > 0) {
            $node = app()->make(PlaceDeclareNodeDao::class)->get($param['node_id']);
            $table_name = 'eb_place_declare'. $node['suffix'];
        }else{
            $table_name = 'eb_place_declare';
        }
        return Db::connect('mysql_slave')->query("SELECT b.yw_street,count(*) mashu24 from (select a.yw_street,a.place_code from (select 
            yw_street,place_code,create_time from ". $table_name ." where create_date= :create_date ) a group by a.yw_street,a.place_code) a
            left join eb_place b on a.place_code=b.code GROUP BY b.yw_street", ['create_date' => $param['create_date']]);
    }
    
    public function scanCodeTimeOrMore($param)
    {
        if(isset($param['node_id']) && $param['node_id'] > 0) {
            $node = app()->make(PlaceDeclareNodeDao::class)->get($param['node_id']);
            $table_name = 'eb_place_declare'. $node['suffix'];
        }else{
            $table_name = 'eb_place_declare';
        }
        return Db::connect('mysql_slave')->query("SELECT b.yw_street,count(*) mashu10 from (select a.yw_street,a.place_code,count(id) cishu from 
            (select yw_street,place_code,create_time,id from ". $table_name ." where create_date= :create_date ) a 
            group by a.yw_street,a.place_code) a left join eb_place b on a.place_code=b.code where a.cishu >= 10 GROUP BY b.yw_street", ['create_date' => $param['create_date']]);
    }
    
    public function beiyuanFushipinListTotal()
    {
        $date_time = time() - 86400;
        return Db::connect('mysql_slave')->query("SELECT count(id) total from eb_place where create_time <= :date_time and yw_street = '北苑街道' 
            and (ifnull(delete_time,0) = 0) and (name like '%副食品市场%' or short_name like '%副食品市场%' or addr like '%副食品市场%')", ['date_time' => $date_time]);
    }

    public function beiyuanFushipinList($param)
    {
        $date_time = time() - 86400;
        return Db::connect('mysql_slave')->query("SELECT a.id,a.yw_street,a.code,a.name,a.short_name,a.link_man,a.link_phone,a.addr,ifnull(b.cishu,0) AS cishu,from_unixtime(a.create_time) AS create_time 
            from (eb_place a left join (select place_code,sum(total_nums) AS cishu from eb_place_declare_date_nums group by place_code) b on b.place_code = a.code) where a.create_time <= 
            :date_time and a.yw_street = '北苑街道' and (ifnull(a.delete_time,0) = 0) and (a.name like '%副食品市场%' or a.short_name like '%副食品市场%' or a.addr like '%副食品市场%')
             and a.id > :_where_id_lg limit 100", ['_where_id_lg' => $param['_where_id_lg'], 'date_time' => $date_time]);
    }
    
    public function beiyuanGuopinExportListTotal()
    {
        $date_time = time() - 86400;
        return Db::connect('mysql_slave')->query("SELECT count(id) total from eb_place where create_time <= :date_time and yw_street = '北苑街道' 
            and (ifnull(delete_time,0) = 0) and (name like '%果品市场%' or short_name like '%果品市场%' or addr like '%果品市场%')", ['date_time' => $date_time]);
    }

    public function beiyuanGuopinExportList($param)
    {
        $date_time = time() - 86400;
        return Db::connect('mysql_slave')->query("SELECT a.id,a.yw_street,a.code,a.name,a.short_name,a.link_man,a.link_phone,a.addr,ifnull(b.cishu,0) AS cishu,from_unixtime(a.create_time) AS create_time 
            from (eb_place a left join (select place_code,sum(total_nums) AS cishu from eb_place_declare_date_nums group by place_code) b on b.place_code = a.code) where a.create_time <= 
            :date_time and a.yw_street = '北苑街道' and (ifnull(a.delete_time,0) = 0) and (a.name like '%果品市场%' or a.short_name like '%果品市场%' or a.addr like '%果品市场%')
            and a.id > :_where_id_lg limit 100", ['_where_id_lg' => $param['_where_id_lg'], 'date_time' => $date_time]);
    }

    public function beiyuanShoucangpinExportListTotal()
    {
        $date_time = time() - 86400;
        return Db::connect('mysql_slave')->query("SELECT count(id) total from eb_place where create_time <= :date_time and yw_street = '北苑街道' 
            and (ifnull(delete_time,0) = 0) and (name like '%收藏品市场%' or short_name like '%收藏品市场%' or addr like '%收藏品市场%')", ['date_time' => $date_time]);
    }

    public function beiyuanShoucangpinExportList($param)
    {
        $date_time = time() - 86400;
        return Db::connect('mysql_slave')->query("SELECT a.id,a.yw_street,a.code,a.name,a.short_name,a.link_man,a.link_phone,a.addr,ifnull(b.cishu,0) AS cishu,from_unixtime(a.create_time) AS create_time 
            from (eb_place a left join (select place_code,sum(total_nums) AS cishu from eb_place_declare_date_nums group by place_code) b on b.place_code = a.code) where a.create_time <= 
            :date_time and a.yw_street = '北苑街道' and (ifnull(a.delete_time,0) = 0) and (a.name like '%收藏品市场%' or a.short_name like '%收藏品市场%' or a.addr like '%收藏品市场%')
            and a.id > :_where_id_lg limit 100", ['_where_id_lg' => $param['_where_id_lg'], 'date_time' => $date_time]);
    }

    public function beiyuanWuziExportListTotal()
    {
        $date_time = time() - 86400;
        return Db::connect('mysql_slave')->query("SELECT count(id) total from eb_place where create_time <= :date_time and yw_street = '北苑街道' 
            and (ifnull(delete_time,0) = 0) and (name like '%物资市场%' or short_name like '%物资市场%' or addr like '%物资市场%')", ['date_time' => $date_time]);
    }

    public function beiyuanWuziExportList($param)
    {
        $date_time = time() - 86400;
        return Db::connect('mysql_slave')->query("SELECT a.id,a.yw_street,a.code,a.name,a.short_name,a.link_man,a.link_phone,a.addr,ifnull(b.cishu,0) AS cishu,from_unixtime(a.create_time) AS create_time 
            from (eb_place a left join (select place_code,sum(total_nums) AS cishu from eb_place_declare_date_nums group by place_code) b on b.place_code = a.code) where a.create_time <= 
            :date_time and a.yw_street = '北苑街道' and (ifnull(a.delete_time,0) = 0) and (a.name like '%物资市场%' or a.short_name like '%物资市场%' or a.addr like '%物资市场%')
            and a.id > :_where_id_lg limit 100", ['_where_id_lg' => $param['_where_id_lg'], 'date_time' => $date_time]);
    }

    public function chengxiLiangshiExportListTotal()
    {
        $date_time = time() - 86400;
        return Db::connect('mysql_slave')->query("SELECT count(id) total from eb_place where create_time <= :date_time and yw_street = '城西街道' 
            and (ifnull(delete_time,0) = 0) and (name like '%粮食市场%' or short_name like '%粮食市场%' or addr like '%粮食市场%')", ['date_time' => $date_time]);
    }

    public function chengxiLiangshiExportList($param)
    {
        $date_time = time() - 86400;
        return Db::connect('mysql_slave')->query("SELECT a.id,a.yw_street,a.code,a.name,a.short_name,a.link_man,a.link_phone,a.addr,ifnull(b.cishu,0) AS cishu,from_unixtime(a.create_time) AS create_time 
            from (eb_place a left join (select place_code,sum(total_nums) AS cishu from eb_place_declare_date_nums group by place_code) b on b.place_code = a.code) where a.create_time <= 
            :date_time and a.yw_street = '城西街道' and (ifnull(a.delete_time,0) = 0) and (a.name like '%粮食市场%' or a.short_name like '%粮食市场%' or a.addr like '%粮食市场%')
            and a.id > :_where_id_lg limit 100", ['_where_id_lg' => $param['_where_id_lg'], 'date_time' => $date_time]);
    }

    public function chouchengJiadianExportListTotal()
    {
        $date_time = time() - 86400;
        return Db::connect('mysql_slave')->query("SELECT count(id) total from eb_place where create_time <= :date_time and yw_street = '稠城街道' 
            and (ifnull(delete_time,0) = 0) and (name like '%家电市场%' or short_name like '%家电市场%' or addr like '%家电市场%')", ['date_time' => $date_time]);
    }

    public function chouchengJiadianExportList($param)
    {
        $date_time = time() - 86400;
        return Db::connect('mysql_slave')->query("SELECT a.id,a.yw_street,a.code,a.name,a.short_name,a.link_man,a.link_phone,a.addr,ifnull(b.cishu,0) AS cishu,from_unixtime(a.create_time) AS create_time 
            from (eb_place a left join (select place_code,sum(total_nums) AS cishu from eb_place_declare_date_nums group by place_code) b on b.place_code = a.code) where a.create_time <= 
            :date_time and a.yw_street = '稠城街道' and (ifnull(a.delete_time,0) = 0) and (a.name like '%家电市场%' or a.short_name like '%家电市场%' or a.addr like '%家电市场%')
            and a.id > :_where_id_lg limit 100", ['_where_id_lg' => $param['_where_id_lg'], 'date_time' => $date_time]);
    }

    public function choujiangJiajuExportListTotal()
    {
        $date_time = time() - 86400;
        return Db::connect('mysql_slave')->query("SELECT count(id) total from eb_place where create_time <= :date_time and yw_street = '稠江街道' 
            and (ifnull(delete_time,0) = 0) and (name like '%家具城%' or name like '%家居城%' or short_name like '%家具城%' or 
            short_name like '%家居城%' or addr like '%家具城%' or addr like '%家居城%')", ['date_time' => $date_time]);
    }

    public function choujiangJiajuExportList($param)
    {
        $date_time = time() - 86400;
        return Db::connect('mysql_slave')->query("SELECT a.id,a.yw_street,a.code,a.name,a.short_name,a.link_man,a.link_phone,a.addr,ifnull(b.cishu,0) AS cishu,from_unixtime(a.create_time) AS create_time 
            from (eb_place a left join (select place_code,sum(total_nums) AS cishu from eb_place_declare_date_nums group by place_code) b on b.place_code = a.code) where a.create_time <= 
            :date_time and a.yw_street = '稠江街道' and (ifnull(a.delete_time,0) = 0) and (a.name like '%家具城%' or a.name like '%家居城%' or a.short_name like '%家具城%' 
            or a.short_name like '%家居城%' or a.addr like '%家具城%' or a.addr like '%家居城%')
            and a.id > :_where_id_lg limit 100", ['_where_id_lg' => $param['_where_id_lg'], 'date_time' => $date_time]);
    }

    public function choujiangJiancaiExportListTotal()
    {
        $date_time = time() - 86400;
        return Db::connect('mysql_slave')->query("SELECT count(id) total from eb_place where create_time <= :date_time and yw_street = '稠江街道' 
            and (ifnull(delete_time,0) = 0) and (name like '%建材市场%' or short_name like '%建材市场%' or addr like '%建材市场%')", ['date_time' => $date_time]);
    }

    public function choujiangJiancaiExportList($param)
    {
        $date_time = time() - 86400;
        return Db::connect('mysql_slave')->query("SELECT a.id,a.yw_street,a.code,a.name,a.short_name,a.link_man,a.link_phone,a.addr,ifnull(b.cishu,0) AS cishu,from_unixtime(a.create_time) AS create_time 
            from (eb_place a left join (select place_code,sum(total_nums) AS cishu from eb_place_declare_date_nums group by place_code) b on b.place_code = a.code) where a.create_time <= 
            :date_time and a.yw_street = '稠江街道' and (ifnull(a.delete_time,0) = 0) and (a.name like '%建材市场%' or a.short_name like '%建材市场%' or a.addr like '%建材市场%')
            and a.id > :_where_id_lg limit 100", ['_where_id_lg' => $param['_where_id_lg'], 'date_time' => $date_time]);
    }

    public function fotangMucaiExportListTotal()
    {
        $date_time = time() - 86400;
        return Db::connect('mysql_slave')->query("SELECT count(id) total from eb_place where create_time <= :date_time and yw_street = '佛堂镇' 
            and (ifnull(delete_time,0) = 0) and (name like '%木材市场%' or short_name like '%木材市场%' or addr like '%木材市场%')", ['date_time' => $date_time]);
    }

    public function fotangMucaiExportList($param)
    {
        $date_time = time() - 86400;
        return Db::connect('mysql_slave')->query("SELECT a.id,a.yw_street,a.code,a.name,a.short_name,a.link_man,a.link_phone,a.addr,ifnull(b.cishu,0) AS cishu,from_unixtime(a.create_time) AS create_time 
            from (eb_place a left join (select place_code,sum(total_nums) AS cishu from eb_place_declare_date_nums group by place_code) b on b.place_code = a.code) where a.create_time <= 
            :date_time and a.yw_street = '佛堂镇' and (ifnull(a.delete_time,0) = 0) and (a.name like '%木材市场%' or a.short_name like '%木材市场%' or a.addr like '%木材市场%')
            and a.id > :_where_id_lg limit 100", ['_where_id_lg' => $param['_where_id_lg'], 'date_time' => $date_time]);
    }

    public function fotangNongfuchanpinExportListTotal()
    {
        $date_time = time() - 86400;
        return Db::connect('mysql_slave')->query("SELECT count(id) total from eb_place where create_time <= :date_time and yw_street = '佛堂镇' 
            and (ifnull(delete_time,0) = 0) and (name like '%浙中农副产品物流中心%' or short_name like '%浙中农副产品物流中心%' or addr like '%浙中农副产品物流中心%')", ['date_time' => $date_time]);
    }

    public function fotangNongfuchanpinExportList($param)
    {
        $date_time = time() - 86400;
        return Db::connect('mysql_slave')->query("SELECT a.id,a.yw_street,a.code,a.name,a.short_name,a.link_man,a.link_phone,a.addr,ifnull(b.cishu,0) AS cishu,from_unixtime(a.create_time) AS create_time 
            from (eb_place a left join (select place_code,sum(total_nums) AS cishu from eb_place_declare_date_nums group by place_code) b on b.place_code = a.code) where a.create_time <= 
            :date_time and a.yw_street = '佛堂镇' and (ifnull(a.delete_time,0) = 0) and (a.name like '%浙中农副产品物流中心%' or a.short_name like '%浙中农副产品物流中心%' or a.addr like '%浙中农副产品物流中心%')
            and a.id > :_where_id_lg limit 100", ['_where_id_lg' => $param['_where_id_lg'], 'date_time' => $date_time]);
    }

    public function houzhaiErshoucheExportListTotal()
    {
        $date_time = time() - 86400;
        return Db::connect('mysql_slave')->query("SELECT count(id) total from eb_place where create_time <= :date_time and yw_street = '后宅街道' 
            and (ifnull(delete_time,0) = 0) and (name like '%二手车中心%' or short_name like '%二手车中心%' or addr like '%二手车中心%' or name like '%二手车市场%' 
            or short_name like '%二手车市场%' or addr like '%二手车市场%' or name like '%二手车交易中心%' or short_name like '%二手车交易中心%' or addr like '%二手车交易中心%')", ['date_time' => $date_time]);
    }

    public function houzhaiErshoucheExportList($param)
    {
        $date_time = time() - 86400;
        return Db::connect('mysql_slave')->query("SELECT a.id,a.yw_street,a.code,a.name,a.short_name,a.link_man,a.link_phone,a.addr,ifnull(b.cishu,0) AS cishu,from_unixtime(a.create_time) AS create_time 
            from (eb_place a left join (select place_code,sum(total_nums) AS cishu from eb_place_declare_date_nums group by place_code) b on b.place_code = a.code) where a.create_time <= 
            :date_time and a.yw_street = '后宅街道' and (ifnull(a.delete_time,0) = 0) and (a.name like '%二手车中心%' or a.short_name like '%二手车中心%' or a.addr like '%二手车中心%' or 
            a.name like '%二手车市场%' or a.short_name like '%二手车市场%' or a.addr like '%二手车市场%' or a.name like '%二手车交易中心%' or a.short_name like '%二手车交易中心%' or a.addr like '%二手车交易中心%')
            and a.id > :_where_id_lg limit 100", ['_where_id_lg' => $param['_where_id_lg'], 'date_time' => $date_time]);
    }

    public function shangxiMujuExportListTotal()
    {
        $date_time = time() - 86400;
        return Db::connect('mysql_slave')->query("SELECT count(id) total from eb_place where create_time <= :date_time and yw_street = '上溪镇' 
            and (ifnull(delete_time,0) = 0) and (name like '%模具城%' or short_name like '%模具城%' or addr like '%模具城%')", ['date_time' => $date_time]);
    }

    public function shangxiMujuExportList($param)
    {
        $date_time = time() - 86400;
        return Db::connect('mysql_slave')->query("SELECT a.id,a.yw_street,a.code,a.name,a.short_name,a.link_man,a.link_phone,a.addr,ifnull(b.cishu,0) AS cishu,from_unixtime(a.create_time) AS create_time 
            from (eb_place a left join (select place_code,sum(total_nums) AS cishu from eb_place_declare_date_nums group by place_code) b on b.place_code = a.code) where a.create_time <= 
            :date_time and a.yw_street = '上溪镇' and (ifnull(a.delete_time,0) = 0) and (a.name like '%模具城%' or a.short_name like '%模具城%' or a.addr like '%模具城%')
            and a.id > :_where_id_lg limit 100", ['_where_id_lg' => $param['_where_id_lg'], 'date_time' => $date_time]);
    }
}
