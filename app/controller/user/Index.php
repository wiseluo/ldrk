<?php

namespace app\controller\user;

use think\facade\App;
use crmeb\basic\BaseController;
use crmeb\services\CacheService;
use think\Response;
use app\validate\user\IndexValidate;
use app\services\user\IndexServices;
use \behavior\XcmTool;
use think\facade\Db;
use \behavior\SmsTool;
use think\facade\Log;
use think\facade\Config;
use think\facade\Cache;
use app\dao\RiskDistrictDao;

class Index extends BaseController
{
    public function __construct(App $app, IndexServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * 下载文件
     * @param string $key
     * @return Response|\think\response\File
     */
    public function download(string $key = '')
    {
        if (!$key) {
            return Response::create()->code(500);
        }
        $fileName = CacheService::get($key);
        if (is_array($fileName) && isset($fileName['path']) && isset($fileName['fileName']) && $fileName['path'] && $fileName['fileName'] && file_exists($fileName['path'])) {
            CacheService::delete($key);
            return download($fileName['path'], $fileName['fileName']);
        }
        return Response::create()->code(500);
    }

    public function xcmdxjk()
    {
        $phone = $this->request->param('phone', '');
        if($phone == '') {
            return show(400, '手机号必填');
        }
        $xcmTool = new XcmTool();
        $res = $xcmTool->xcmdxjk($phone);
        //var_dump($res);
        if($res['status'] == 1) {
            return show(200, $res['msg'], $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }
    
    public function xcmjk()
    {
        $phone = $this->request->param('phone', '');
        if($phone == '') {
            return show(400, '手机号必填');
        }
        $verification = $this->request->param('verification', '');
        if($verification == '') {
            return show(400, '验证码必填');
        }
        $city_code = app()->make(RiskDistrictDao::class)->getCityCodeConcat();
        
        $xcmTool = new XcmTool();
        $res = $xcmTool->xcmjk($phone, $verification, $city_code);
        //var_dump($res);
        if($res['status'] == 1) {
            return show(200, $res['msg'], $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }
    public function jkm_log(){
        $count = Db::name('user_jkm_log')->count();
        var_dump($count);
        $un_count = Db::name('declare')->where('jkm_get','=',0)->count();
        var_dump($un_count);
        $data = Db::name('user_jkm_log')->order('id','desc')->limit(100)->select()->toArray();
        dump($data);
    }
    public function hsjc_log(){
        $count = Db::name('user_hsjc_log')->count();
        var_dump($count);
        $un_count = Db::name('declare')->where('hsjc_get','=',0)->count();
        var_dump($un_count);
        $data = Db::name('user_hsjc_log')->order('id','desc')->limit(100)->select()->toArray();
        dump($data);
    }
    public function test_log(){
        $data = Db::name('test')->order('id','desc')->limit(100)->select()->toArray();
        dump($data);
    }

    // 8、发送定时短信：
    // a)参考文本：总计企业555家，员工23456人，其中稠江企业20家，员工400人，稠城企业31家，员工678人；
    // b)企业数多的镇街放前面；
    // c)早上八点到晚上八点；
    // 企业码统计
    public function qymtongji(){
        $param = $this->request->param();

        // if(Config::get('app.app_host') == 'dev') { //测试环境不验证
        //     Db::connect('mysql_slave')->name('company') = Db::name('company');
        //     Db::connect('mysql_slave')->name('company_staff') = Db::name('company_staff');
        // }else{
            test_log('mysql_slave qymtongji');
        // }

        $ssdata = Db::connect('mysql_slave')->name('company')->field('count(id) as company_nums,yw_street,yw_street_id')->group('yw_street')->select()->toArray();
    
        $jiedao_str = '';
        $all_company_nums = 0;
        $all_user_nums = 0;

        //根据字段 company_nums 对数组$data进行降序排列
        $last_names = array_column($ssdata,'company_nums');
        array_multisort($last_names,SORT_DESC,$ssdata);

        foreach($ssdata as $key => $value){
            $ssdata[$key]['user_nums'] = Db::connect('mysql_slave')->name('company_staff')->where('yw_street_id','=',$value['yw_street_id'])->count();
            $jiedao_str .= $value['yw_street'].'企业'.$value['company_nums'].'家,员工'.$ssdata[$key]['user_nums'].'人,';
            $all_company_nums += $value['company_nums'];
            $all_user_nums += $ssdata[$key]['user_nums'];
        }

        $jiedao_str = trim($jiedao_str,',');
    
        $str = '总计企业'.$all_company_nums.'家，员工'.$all_user_nums.'人，其中'.$jiedao_str.'。';

        test_log($str);
        if(isset($param['is_debug']) && $param['is_debug'] == 1){
            var_dump($str);
        }else{
            if(Config::get('app.app_host') == 'dev'){
                echo $str;
                return ;
            }
            // 发送短信
            $smsTool = new SmsTool();
            $qymtongji_phone = Db::name('system_config')->where('menu_name','=','qymtongji_phone')->value('value');
            if($qymtongji_phone != ''){
                $res = $smsTool->sendSms($qymtongji_phone, $str);
                var_dump($res);
            }else{
                test_log('qymtongji_phone is empty');
            }
        }
    }




    public function csmtongji(){
        $param = $this->request->param();
        $today_time = strtotime(Date('Y-m-d'));


        // if(Config::get('app.app_host') == 'dev') { //测试环境不验证
        //     Db::connect('mysql_slave')->name('place') = Db::name('place');
        //     Db::connect('mysql_slave')->name('place_declare_date_nums') = Db::name('place_declare_date_nums');
        //     Db::connect('mysql_slave')->name('place_declare') = Db::name('place_declare');
        // }else{
            test_log('mysql_slave csmtongji');
        // }



        // 场所情况
        $ssdata = Db::connect('mysql_slave')->name('place')
            ->field('
                count(id) as total_nums,
                sum(if( place_classify="gov",1,0)) as gov_nums,
                sum(if( place_classify="company",1,0)) as company_nums,
                sum(if( place_classify="other",1,0)) as other_nums,

                sum(if( yw_street="大陈镇",1,0)) as da_chen_zhen_nums,
                sum(if( yw_street="义亭镇",1,0)) as yi_ting_zhen_nums,
                sum(if( yw_street="赤岸镇",1,0)) as chi_an_zhen_nums,
                sum(if( yw_street="苏溪镇",1,0)) as su_xi_zhen_nums,
                sum(if( yw_street="城西街道",1,0)) as cheng_xi_nums,
                sum(if( yw_street="上溪镇",1,0)) as shang_xi_zhen_nums,
                sum(if( yw_street="稠城街道",1,0)) as chou_cheng_nums,
                sum(if( yw_street="江东街道",1,0)) as jiang_dong_nums,
                sum(if( yw_street="廿三里街道",1,0)) as nian_san_li_nums,
                sum(if( yw_street="后宅街道",1,0)) as hou_zhai_nums,
                sum(if( yw_street="佛堂镇",1,0)) as fo_tang_zhen_nums,
                sum(if( yw_street="稠江街道",1,0)) as chou_jiang_nums,
                sum(if( yw_street="北苑街道",1,0)) as bei_yuan_nums,
                sum(if( yw_street="福田街道",1,0)) as fu_tian_nums,
                sum(if( yw_street_id=0,1,0)) as empty_stree_nums,


                sum(if(  create_time>'.$today_time.' ,1,0)) as today_total_nums,
                sum(if( place_classify="gov" and create_time>'.$today_time.' ,1,0)) as today_gov_nums,
                sum(if( place_classify="company" and create_time>'.$today_time.' ,1,0)) as today_company_nums,
                sum(if( place_classify="other" and create_time>'.$today_time.' ,1,0)) as today_other_nums,

                sum(if( yw_street="大陈镇" and create_time>'.$today_time.',1,0)) as today_da_chen_zhen_nums,
                sum(if( yw_street="义亭镇" and create_time>'.$today_time.',1,0)) as today_yi_ting_zhen_nums,
                sum(if( yw_street="赤岸镇" and create_time>'.$today_time.',1,0)) as today_chi_an_zhen_nums,
                sum(if( yw_street="苏溪镇" and create_time>'.$today_time.',1,0)) as today_su_xi_zhen_nums,
                sum(if( yw_street="城西街道" and create_time>'.$today_time.',1,0)) as today_cheng_xi_nums,
                sum(if( yw_street="上溪镇" and create_time>'.$today_time.',1,0)) as today_shang_xi_zhen_nums,
                sum(if( yw_street="稠城街道" and create_time>'.$today_time.',1,0)) as today_chou_cheng_nums,
                sum(if( yw_street="江东街道" and create_time>'.$today_time.',1,0)) as today_jiang_dong_nums,
                sum(if( yw_street="廿三里街道" and create_time>'.$today_time.',1,0)) as today_nian_san_li_nums,
                sum(if( yw_street="后宅街道" and create_time>'.$today_time.',1,0)) as today_hou_zhai_nums,
                sum(if( yw_street="佛堂镇" and create_time>'.$today_time.',1,0)) as today_fo_tang_zhen_nums,
                sum(if( yw_street="稠江街道" and create_time>'.$today_time.',1,0)) as today_chou_jiang_nums,
                sum(if( yw_street="北苑街道" and create_time>'.$today_time.',1,0)) as today_bei_yuan_nums,
                sum(if( yw_street="福田街道" and create_time>'.$today_time.',1,0)) as today_fu_tian_nums,
                sum(if( yw_street_id=0 and create_time>'.$today_time.',1,0)) as today_empty_stree_nums


            ')
            ->whereNull('delete_time')
            ->select()->toArray();

        $data = $ssdata[0];
        // 
        $stree_place_nums_str = '';
        $today_stree_place_nums_str = '';
        foreach($data as $key => $value){
            if($key == 'da_chen_zhen_nums'){
                $stree_place_nums_str .= '大陈镇'.$data['da_chen_zhen_nums'].'个,';
            }
            if($key == 'today_da_chen_zhen_nums'){
                $today_stree_place_nums_str .= '大陈镇'.$data['today_da_chen_zhen_nums'].'个,';
            }
            if($key == 'yi_ting_zhen_nums'){
                $stree_place_nums_str .= '义亭镇'.$data['yi_ting_zhen_nums'].'个,';
            }
            if($key == 'today_yi_ting_zhen_nums'){
                $today_stree_place_nums_str .= '义亭镇'.$data['today_yi_ting_zhen_nums'].'个,';
            }
            if($key == 'chi_an_zhen_nums'){
                $stree_place_nums_str .= '赤岸镇'.$data['chi_an_zhen_nums'].'个,';
            }
            if($key == 'today_chi_an_zhen_nums'){
                $today_stree_place_nums_str .= '赤岸镇'.$data['today_chi_an_zhen_nums'].'个,';
            }
            if($key == 'su_xi_zhen_nums'){
                $stree_place_nums_str .= '苏溪镇'.$data['su_xi_zhen_nums'].'个,';
            }
            if($key == 'today_su_xi_zhen_nums'){
                $today_stree_place_nums_str .= '苏溪镇'.$data['today_su_xi_zhen_nums'].'个,';
            }
            if($key == 'cheng_xi_nums'){
                $stree_place_nums_str .= '城西街道'.$data['cheng_xi_nums'].'个,';
            }
            if($key == 'today_cheng_xi_nums'){
                $today_stree_place_nums_str .= '城西街道'.$data['today_cheng_xi_nums'].'个,';
            }
            if($key == 'shang_xi_zhen_nums'){
                $stree_place_nums_str .= '上溪镇'.$data['shang_xi_zhen_nums'].'个,';
            }
            if($key == 'today_shang_xi_zhen_nums'){
                $today_stree_place_nums_str .= '上溪镇'.$data['today_shang_xi_zhen_nums'].'个,';
            }
            if($key == 'chou_cheng_nums'){
                $stree_place_nums_str .= '稠城街道'.$data['chou_cheng_nums'].'个,';
            }
            if($key == 'today_chou_cheng_nums'){
                $today_stree_place_nums_str .= '稠城街道'.$data['today_chou_cheng_nums'].'个,';
            }
            if($key == 'jiang_dong_nums'){
                $stree_place_nums_str .= '江东街道'.$data['jiang_dong_nums'].'个,';
            }
            if($key == 'today_jiang_dong_nums'){
                $today_stree_place_nums_str .= '江东街道'.$data['today_jiang_dong_nums'].'个,';
            }
            if($key == 'nian_san_li_nums'){
                $stree_place_nums_str .= '廿三里街道'.$data['nian_san_li_nums'].'个,';
            }
            if($key == 'today_nian_san_li_nums'){
                $today_stree_place_nums_str .= '廿三里街道'.$data['today_nian_san_li_nums'].'个,';
            }
            if($key == 'hou_zhai_nums'){
                $stree_place_nums_str .= '后宅街道'.$data['hou_zhai_nums'].'个,';
            }
            if($key == 'today_hou_zhai_nums'){
                $today_stree_place_nums_str .= '后宅街道'.$data['today_hou_zhai_nums'].'个,';
            }
            if($key == 'fo_tang_zhen_nums'){
                $stree_place_nums_str .= '佛堂镇'.$data['fo_tang_zhen_nums'].'个,';
            }
            if($key == 'today_fo_tang_zhen_nums'){
                $today_stree_place_nums_str .= '佛堂镇'.$data['today_fo_tang_zhen_nums'].'个,';
            }
            if($key == 'chou_jiang_nums'){
                $stree_place_nums_str .= '稠江街道'.$data['chou_jiang_nums'].'个,';
            }
            if($key == 'today_chou_jiang_nums'){
                $today_stree_place_nums_str .= '稠江街道'.$data['today_chou_jiang_nums'].'个,';
            }
            if($key == 'bei_yuan_nums'){
                $stree_place_nums_str .= '北苑街道'.$data['bei_yuan_nums'].'个,';
            }
            if($key == 'today_bei_yuan_nums'){
                $today_stree_place_nums_str .= '北苑街道'.$data['today_bei_yuan_nums'].'个,';
            }
            if($key == 'fu_tian_nums'){
                $stree_place_nums_str .= '福田街道'.$data['fu_tian_nums'].'个,';
            }
            if($key == 'today_fu_tian_nums'){
                $today_stree_place_nums_str .= '福田街道'.$data['today_fu_tian_nums'].'个,';
            }
            if($key == 'empty_stree_nums'){
                $stree_place_nums_str .= '未知街道'.$data['empty_stree_nums'].'个,';
            }
            if($key == 'today_empty_stree_nums'){
                $today_stree_place_nums_str .= '未知街道'.$data['today_empty_stree_nums'].'个,';
            }
        }
        $stree_place_nums_str = trim($stree_place_nums_str,',');
        $today_stree_place_nums_str = trim($today_stree_place_nums_str,',');



        // 扫码情况
       

        $data_scan['total_nums'] = 0; // Db::name('place_declare')->count();
        $pre_total_nums = Db::connect('mysql_slave')->name('place_declare_date_nums')->sum('total_nums'); // 将日统计的里面的进行累加

        $yestoday_date = Date('Y-m-d',strtotime(' -1 day'));
        $yestoday_not_zero_place_num = Db::connect('mysql_slave')->name('place_declare_date_nums')->where('date','=',$yestoday_date)->count(); // 将日统计的里面的进行累加
        // $data_scan['today_total_nums'] = Db::name('place_declare')->whereDay('create_time')->count();

        $today_nums_data = Db::connect('mysql_slave')->name('place_declare')->field('yw_street,COUNT(id) AS yw_street_nums')->where('create_date','=',Date('Y-m-d'))->group('yw_street')->select();

        $data_scan['today_total_nums'] = 0; // 今日总数
        $today_stree_nums_str = '';
        $empty_nums = 0;
        $zero_num_place_num = 0; // 总场所数 - 今日新增 - 昨日有扫
        $zero_num_place_num = $data['total_nums'] - $data['today_total_nums'] - $yestoday_not_zero_place_num;

        foreach($today_nums_data as $key => $value){
            $data_scan['today_total_nums'] += $value['yw_street_nums'];
            if($value['yw_street'] == ''){
                $empty_nums += $value['yw_street_nums'];
            }else{
                $today_stree_nums_str .= $value['yw_street'].$value['yw_street_nums'].'人次,';
            }
        }
        $today_stree_nums_str .= '未知街道'.$empty_nums.'人次,';
        $today_stree_nums_str = trim($today_stree_nums_str,',');

        $data_scan['total_nums'] = $pre_total_nums + $data_scan['today_total_nums'];

        $str = "截至".Date('Y-m-d H:i:s')."共".$data['total_nums']."个单位申请了场所码，其中党政机关".$data['gov_nums']."个，企业".$data['company_nums']."个，其他".$data['other_nums']."个，合计不活跃码数".$zero_num_place_num."个。".$stree_place_nums_str."
        今天增加".$data['today_total_nums']."个场所码申请，其中党政机关".$data['today_gov_nums']."个，企业".$data['today_company_nums']."个，其他".$data['today_other_nums']."个。".$today_stree_place_nums_str."
        扫码情况：共扫码".$data_scan['total_nums'].'人次,今日共扫码'.$data_scan['today_total_nums'].'人次'.',其中'.$today_stree_nums_str;

        Log::error($str);
        test_log($str);
        if(isset($param['is_debug']) && $param['is_debug'] == 1){
            var_dump($str);
        }else{
            if(Config::get('app.app_host') == 'dev'){
                return 11;
            }
            // 发送短信
            $smsTool = new SmsTool();
            $csmtongji_phone = Db::name('system_config')->where('menu_name','=','csmtongji_phone')->value('value');
            if($csmtongji_phone != ''){
                $res = $smsTool->sendSms($csmtongji_phone, $str);
                var_dump($res);
            }else{
                test_log('csmtongji_phone is empty');
            }
        }
    }


    // 今日14：00-16：00，自主申报系统共申报宁波来义人员67人，杭州来义人员303人，绍兴来义人员159人。
    // 6
    // 8-22/2
    // 0 

    public function zuijin(){
        $param = $this->request->param();
        $time_start_at = isset($param['time_start_at']) ? $param['time_start_at'] : 'b2'; // 'f0t6'='0点到06点的时间段', 'b2'='之前2小时内的情况'

        test_log('mysql_slave zuijin shenbao');


        $city_arr = ['杭州市']; // '宁波市','绍兴市'

        $where = [];
        $where[] = ['city','in',$city_arr];
        $where[] = ['declare_type','<>','leave'];

        switch($time_start_at){
            // 0点到06点的时间段
            case 'f0t6':
                $start_time = strtotime(Date('Y-m-d'). ' 00:00:00');
                $end_time = strtotime(Date('Y-m-d'). ' 06:00:00');
            break;
            case 'b2':
                // 当前的整点时间 倒推前2小时的时间段
                $end_time = time();
                $end_zd_time = strtotime(Date('Y-m-d'). ' '.Date("H").':00:00');
                $start_time = $end_zd_time - 2*3600;
            break;
            case 'f0_now':
                $start_time = strtotime(Date('Y-m-d'). ' 00:00:00');
                $end_time = time();
            break;
        }
        $where[] = ['create_time','>',$start_time];
        $where[] = ['create_time','<=',$end_time];

        $ssdata = Db::connect('mysql_slave')->name('declare')
                  ->field('
                        city,
                        count(id) as nums,
                        sum(if( declare_type="quarantine",1,0)) as quarantine_nums,
                        sum(if( city_id="301" && county_id="2783",1,0)) as beilun_nums,
                        sum(if( city_id="301" && county_id<>"2783",1,0)) as fei_beilun_nums
                    ')
                  ->where($where)
                  ->group('city')
                  ->select()->toArray();

        //$tujingData = Db::name('declare')
        //->field('
        //    sum(if( city_id<>"301" and travel_route like "%宁波%" ,1,0)) as tujing_ningbo_nums,
        //    sum(if( (travel_route like "%宁波%" or city_id="301" ) ,1,0)) as all_ningbo_nums
        //')
        //->where($where)
        //->select()->toArray();


        // $tujing_nums = $tujingData[0];


        $city_nums_data = [];
        $shangyu_nums = 0;
        $beilun_nums = 0;
        $fei_beilun_nums = 0;
        foreach($ssdata as $key => $value){
            $city_nums_data[$value['city']] = $value['nums'];
            if($value['city'] == '绍兴市'){
                $shangyu_nums = $value['quarantine_nums'];
            }
            if($value['city'] == '宁波市'){
                $beilun_nums = $value['beilun_nums'];
                $fei_beilun_nums = $value['fei_beilun_nums'];
            }
        }

        $str = '今日'.Date('H:i',$start_time).'-'.Date('H:i',$end_time).',自主申报系统共申报';
        foreach($city_arr as $key => $city){
            $nums = isset($city_nums_data[$city]) ? $city_nums_data[$city] : 0;
            $str .= $city.'来义'.$nums.'人,';
            // if($city=='宁波市'){
            //    $str = trim($str,',');
            //    $str .= '(其中北仑区'.$beilun_nums.'人，非北仑区'.$fei_beilun_nums.'人),途径宁波'.(int)$tujing_nums['tujing_ningbo_nums'].'人（来自行程码数据）,来自宁波和途径宁波共'.(int)$tujing_nums['all_ningbo_nums'].'人,';
            // }
        }
        $str = trim($str,',');
        $str .= '(其中上虞申报'.$shangyu_nums.'人)';
        $str .= '。';

        Log::error($str);
        test_log($str);
        if(isset($param['is_debug']) && $param['is_debug'] == 1){
            var_dump($str);
        }else{
            if(Config::get('app.app_host') == 'dev'){
                return 11;
            }
            // 发送短信
            $smsTool = new SmsTool();
            $zuijin_phone = Db::name('system_config')->where('menu_name','=','zuijin_phone')->value('value');
            if($zuijin_phone != ''){
                $res = $smsTool->sendSms($zuijin_phone, $str);
                var_dump($res);
            }else{
                test_log('zuijin_phone is empty');
            }
        }
        // var_dump('dsdds');
        // var_dump($ssdata);
        // var_dump($str);
    }



    public function tongji(){
        $param = $this->request->param();
        $today_time = strtotime(Date('Y-m-d'));


        test_log('mysql_slave tongji shenbao');

        $ssdata = Db::connect('mysql_slave')->name('declare')
            ->field('
                count(id) as total_nums,
                sum(if( declare_type="leave",1,0)) as leave_nums,
                sum(if( declare_type="come",1,0)) as come_nums,
                sum(if( declare_type="riskarea",1,0)) as riskarea_nums,
                sum(if( declare_type="barrier",1,0)) as barrier_nums,
                sum(if( declare_type="quarantine",1,0)) as quarantine_nums,
                


                sum(if(  create_time>'.$today_time.' ,1,0)) as today_total_nums,
                sum(if( declare_type="leave" and create_time>'.$today_time.' ,1,0)) as today_leave_nums,
                sum(if( declare_type="come" and create_time>'.$today_time.' ,1,0)) as today_come_nums,
                sum(if( declare_type="riskarea" and create_time>'.$today_time.' ,1,0)) as today_riskarea_nums,
                sum(if( declare_type="barrier" and create_time>'.$today_time.' ,1,0)) as today_barrier_nums,
                sum(if( declare_type="quarantine" and create_time>'.$today_time.' ,1,0)) as today_quarantine_nums


            ')

            // sum(if( city_id="301",1,0)) as ningbo_nums,
            // sum(if( city_id="301" && county_id="2783",1,0)) as beilun_nums,
            // sum(if( city_id="301" && county_id<>"2783",1,0)) as fei_beilun_nums,
            // sum(if( city_id<>"301" && travel_route like "%宁波%" ,1,0)) as tujing_ningbo_nums,
            

            // sum(if( city_id="301" and create_time>'.$today_time.',1,0)) as today_ningbo_nums,
            // sum(if( city_id="301" && county_id="2783" and create_time>'.$today_time.',1,0)) as today_beilun_nums,
            // sum(if( city_id="301" && county_id<>"2783" and create_time>'.$today_time.',1,0)) as today_fei_beilun_nums,
            // sum(if( city_id<>"301" && travel_route like "%宁波%" and create_time>'.$today_time.',1,0)) as today_tujing_ningbo_nums



            ->select()->toArray();

        $data = $ssdata[0];

        $user_data =Db::connect('mysql_slave')->name('declare')
            ->field('
                count(distinct id_card) as person_nums
            ')
            ->select();

        $user_today_data = Db::connect('mysql_slave')->name('declare')
            ->field('
                count(distinct id_card) as total_person_nums
            ')
            ->where('create_time','>',$today_time)
            ->select();


        // $person_nums = Db::name('user')->count();
        // $today_person_nums = Db::name('user')->where('create_time','>',$today_time)->count();


        $returnData = [];
        $returnData['declare'] = $data;
        $returnData['person_nums'] = $user_data[0]['person_nums'];
        $returnData['total_person_nums'] = $user_today_data[0]['total_person_nums'];
        // $returnData['person_nums'] = $person_nums;
        // $returnData['total_person_nums'] = $today_person_nums;

        // $str = "目前截至".Date('Y-m-d H:i:s')."共".$returnData['person_nums']."人共".$data['total_nums']."条申报记录，其中外出".$data['leave_nums']."条，来义".$data['come_nums']."条，高风险".$data['riskarea_nums']."条，卡口".$data['barrier_nums']."条，上虞".$data['quarantine_nums']."条,宁波".$data['ningbo_nums']."人（其中北仑区".$data['beilun_nums']."人,非北仑区".$data['fei_beilun_nums']."人）,途径宁波".$data['tujing_ningbo_nums']."人（来自行程码数据）；
        // 其中今天增加".$returnData['total_person_nums']."人，增加".$data['today_total_nums']."条申报记录，增加外出".$data['today_leave_nums']."条，来义".$data['today_come_nums']."条，高风险".$data['today_riskarea_nums']."条，卡口".$data['today_barrier_nums']."条，上虞".$data['today_quarantine_nums']."条,宁波".$data['today_ningbo_nums']."人（其中北仑区".$data['today_beilun_nums']."人,非北仑区".$data['today_fei_beilun_nums']."人）,途径宁波".$data['today_tujing_ningbo_nums']."人（来自行程码数据）。";

        // $str = "目前截至".Date('Y-m-d H:i:s')."共".$returnData['person_nums']."人共".$data['total_nums']."条申报记录，其中外出".(int)$data['leave_nums']."条，来义".(int)$data['come_nums']."条，高风险".(int)$data['riskarea_nums']."条，卡口".(int)$data['barrier_nums']."条，上虞".(int)$data['quarantine_nums']."条；
        // 其中今天增加".(int)$returnData['total_person_nums']."人，增加".(int)$data['today_total_nums']."条申报记录，增加外出".(int)$data['today_leave_nums']."条，来义".(int)$data['today_come_nums']."条，高风险".(int)$data['today_riskarea_nums']."条，卡口".(int)$data['today_barrier_nums']."条，上虞".(int)$data['today_quarantine_nums']."条。";

        $str = "截至".Date('Y-m-d H:i:s')."共".$returnData['person_nums']."人共".$data['total_nums']."条申报记录，其中外出".(int)$data['leave_nums']."条，来义".(int)$data['come_nums']."条，高风险".(int)$data['riskarea_nums']."条；
        其中今天增加".(int)$returnData['total_person_nums']."人，增加".(int)$data['today_total_nums']."条申报记录，增加外出".(int)$data['today_leave_nums']."条，来义".(int)$data['today_come_nums']."条，高风险".(int)$data['today_riskarea_nums']."条。";


        Log::error($str);
        test_log($str);
        if(isset($param['is_debug']) && $param['is_debug'] == 1){
            var_dump($str);
        }else{
            if(Config::get('app.app_host') == 'dev'){
                return 11;
            }
            // 发送短信
            $smsTool = new SmsTool();
            $tongji_phone = Db::name('system_config')->where('menu_name','=','tongji_phone')->value('value');
            if($tongji_phone != ''){
                $res = $smsTool->sendSms($tongji_phone, $str);
                var_dump($res);
            }else{
                test_log('tongji_phone is empty');
            }
        }


    }
    
    // 删除测试人员的账号,方便多次授权测试
    public function sccszh(){
        $param = $this->request->param();
        $phone = $param['phone'];
        if(in_array($phone,['15669389002','13625896500','13868960803','13735675918','13516964723','15857941559'])){
            $res = Db::name('user')->where('phone','=',$phone)->delete();
            echo $res;
        }
    }

}
