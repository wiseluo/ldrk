<?php

declare (strict_types=1);

namespace app\dao;

use app\dao\BaseDao;
use app\model\OwnDeclare;
use think\facade\Db;

class OwnDeclareDao extends BaseDao
{
    
    protected function setModel(): string
    {
        return OwnDeclare::class;
    }

    public function getList($param)
    {
        $where = $this->_param_where2($param);

        return $this->getModel()
                ->alias('o') 
                ->leftJoin('yw_students s', 'o.id_card=s.id_card')
                ->field('o.*,NOT isnull(s.id) as is_student')
                ->where($where)
                ->order('o.id desc')
                ->append(['destination','card_type_text'])
                ->paginate($param['size'])
                ->toArray();
    }

    public function getDataError($param,$declare_type='leave')
    {
        $where = $this->_param_where($param);
        if( isset($param['keyword']) && $param['keyword']) {
            $where[] = ['real_name|phone|id_card', 'LIKE', '%'. $param['keyword'] .'%'];
        }
        
        switch($declare_type){
            case 'leave':
                $where[] = ['id_verify_result','=',2];
                break;
            case 'come':
                $where[] =  function ($query)  {
                    $query->whereRaw('id_verify_result = :id_v_s or ocr_result = :ocr_r ', ['id_v_s' => 2, 'ocr_r' => 2]);
                };
                break;
            case 'riskarea':
                $where[] =  function ($query)  {
                    $query->whereRaw('id_verify_result = :id_v_s or ocr_result = :ocr_r ', ['id_v_s' => 2, 'ocr_r' => 2]);
                };
                break;
        }
        if(!isset($param['size'])){
            $param['size'] = 20;
        }
        $where[] = ['declare_type','=',$declare_type];
        return $this->getModel()::where($where)
                ->order('id desc')
                ->append(['error_infos','card_type_text','destination','arrival_transport_mode_text'])
                ->paginate($param['size'])
                ->toArray();
    }

    public function getTodayMany($param=[]){

        $where = [];
        if(isset($param['date']) && $param['date'] != ''){
            $where[] =  function ($query) use($param)  {
                $query->whereDay('create_time', $param['date'] );
            };
        }else{
            $where[] =  function ($query) use($param)  {
                $query->whereDay('create_time', Date('Y-m-d') );
            };
        }

        return $this->getModel()::where($where)
                ->field('
                    real_name,
                    group_concat(id) as ids,
                    SUM(if(declare_type="leave",1,0)) as leave_nums,
                    SUM(if(declare_type="come",1,0)) as come_nums,
                    SUM(if(declare_type="riskarea",1,0)) as riskarea_nums
                ')
                ->group('id_card')
                ->having('count(id)>1')
                ->select();
    }


    public function getDataWarning($param,$warning_type='backouttime')
    {
        $where = $this->_param_where($param);
        if(!isset($param['size'])){
            $param['size'] = 20;
        }
        switch($warning_type){
            // 未按时返义
            case 'backouttime':
                $where[] =  function ($query)  {
                    $query->where('is_back','=',0);
                    $query->where('expect_return_time','<',Date('Y-m-d'));
                };
                return $this->getModel()::alias('d')
                        ->leftJoin('message_record mc', 'd.id=mc.source_id')
                        ->field('d.*,mc.content sms_content, mc.send_time sms_send_time')
                        ->where($where)
                        ->order('d.id desc')
                        ->append(['card_type_text','destination'])
                        ->paginate($param['size'])
                        ->toArray();
                break;
            // 中高风险密接人员
            case 'riskarea':
                // $where[] = ['declare_type','=','riskarea']; // todo 用另一个字段代替
                $where[] =  function ($query)  {
                    $query->whereRaw('declare_type = :declare_type or is_warning = :is_warning ', ['declare_type' => 'riskarea', 'is_warning' => 1]);
                };
                return $this->getModel()::where($where)
                        ->order('id desc')
                        ->append(['card_type_text','destination','warning_from_text','warning_rule_text','is_to_oa','arrival_transport_mode_text'])
                        ->paginate($param['size'])
                        ->toArray();

                break;
        }
    }

    public function getTravelAsterisk($param)
    {
        $where = $this->_param_where($param);
        $where[] = ['declare_type', '<>', 'leave'];
        
        return $this->getModel()::where($where)
                ->order('id desc')
                ->append(['destination','card_type_text'])
                ->paginate($param['size'])
                ->toArray();
    }

    public function getJkmmzt($param)
    {
        $where = $this->_param_where($param);
        $where[] = ['jkm_mzt', '<>', '绿码'];
        
        return $this->getModel()::where($where)
                ->order('id desc')
                ->append(['destination','card_type_text'])
                ->paginate($param['size'])
                ->toArray();
    }
    
    private function _param_where2($param)
    {
        $where = [];
        //分段导出
        if(isset($param['_where_id_lg']) && $param['_where_id_lg'] > 0) {
            $where[] = ['o.id', '<', $param['_where_id_lg']];
        }
        if( isset($param['declare_type']) && $param['declare_type'] != '') {
            $where[] = ['o.declare_type', '=', $param['declare_type'] ];
        }

        if( isset($param['real_name']) && $param['real_name'] != '') {
            $where[] = ['o.real_name', 'LIKE', '%'. $param['real_name'] .'%'];
        }
        if( isset($param['arrival_time']) && $param['arrival_time'] != '') {
            $where[] = function ($query) use($param)  {
                $query->where('o.arrival_time','=',$param['arrival_time']);
            };
        }

        if( isset($param['card_type']) && $param['card_type'] != '') {
            $where[] = ['o.card_type', '=', $param['card_type']];
        }
        if( isset($param['id_card']) && $param['id_card'] != '') {
            $where[] = ['o.id_card', 'LIKE', '%'. $param['id_card'] .'%'];
        }
        if( isset($param['phone']) && $param['phone'] != '') {
            $where[] = ['o.phone', 'LIKE', '%'. $param['phone'] .'%'];
        }
        if( isset($param['leave_time']) && $param['leave_time'] != '') {
            $where[] = ['o.leave_time', '=', $param['leave_time']];
        }
        if( isset($param['expect_return_time']) && $param['expect_return_time'] != '') {
            $where[] = ['o.expect_return_time', '=', $param['expect_return_time']];
        }
        if( isset($param['isasterisk']) && $param['isasterisk'] !== '') {
            $where[] = ['o.isasterisk', '=', $param['isasterisk']];
        }
        if( isset($param['start_date']) && $param['start_date']) {
            $where[] = ['o.create_time', '>', strtotime($param['start_date'].' 00:00:00') ];
        }
        if( isset($param['end_date']) && $param['end_date']) {
            $where[] = ['o.create_time', '<=', strtotime($param['end_date'].' 23:59:59') ];
        }
        if( isset($param['create_date']) && $param['create_date']) {
            $where[] = function ($query) use($param)  {
                $query->whereDay('o.create_time',$param['create_date']);
            };
        }
        if( isset($param['error_types']) && $param['error_types'] != '') {
            if( strstr($param['error_types'],'id_verify_result') ){
                $where[] = ['o.id_verify_result', '=', 2 ];
            }
            if( strstr($param['error_types'],'ocr_result') ){
                $where[] = ['o.ocr_result', '=', 2 ];
            }
        }
        if( isset($param['province_id']) && $param['province_id'] > 0) {
            $where[] = ['o.province_id', '=', $param['province_id']];
        }
        if( isset($param['city_id']) && $param['city_id'] > 0) {
            $where[] = ['o.city_id', '=', $param['city_id']];
        }
        if( isset($param['county_id']) && $param['county_id'] > 0) {
            $where[] = ['o.county_id', '=', $param['county_id']];
        }
        if( isset($param['street_id']) && $param['street_id'] > 0) {
            $where[] = ['o.street_id', '=', $param['street_id']];
        }
        if( isset($param['yw_street']) && $param['yw_street'] != '') {
            $where[] = ['o.yw_street', 'LIKE', '%'. $param['yw_street'] .'%'];
        }
        if( isset($param['o.yw_street_id']) && $param['yw_street_id']  > 0) {
            $where[] = ['o.yw_street_id', '=', $param['yw_street_id']];
        }
        if( isset($param['address']) && $param['address'] != '') {
            $where[] = ['o.address', 'LIKE', '%'. $param['address'] .'%'];
        }
        if( isset($param['travel_route']) && $param['travel_route'] != '') {
            $where[] = ['o.travel_route', 'LIKE', '%'. $param['travel_route'] .'%'];
        }
        if( isset($param['leave_riskarea_time']) && $param['leave_riskarea_time'] != '') {
            $where[] = ['o.leave_riskarea_time', '=', $param['leave_riskarea_time']];
        }
        if( isset($param['arrival_transport_mode']) && $param['arrival_transport_mode'] != '') {
            $where[] = ['o.arrival_transport_mode', '=', $param['arrival_transport_mode']];
        }
        if( isset($param['arrival_sign']) && $param['arrival_sign'] != '') {
            $where[] = ['o.arrival_sign', '=', $param['arrival_sign']];
        }
        return $where;
    }

    private function _param_where($param)
    {
        $where = [];
        //分段导出
        if(isset($param['_where_id_lg']) && $param['_where_id_lg'] > 0) {
            $where[] = ['id', '<', $param['_where_id_lg']];
        }
        if( isset($param['declare_type']) && $param['declare_type'] != '') {
            $where[] = ['declare_type', '=', $param['declare_type'] ];
        }

        if( isset($param['real_name']) && $param['real_name'] != '') {
            $where[] = ['real_name', 'LIKE', '%'. $param['real_name'] .'%'];
        }
        if( isset($param['arrival_time']) && $param['arrival_time'] != '') {
            $where[] = function ($query) use($param)  {
                $query->where('arrival_time','=',$param['arrival_time']);
            };
        }

        if( isset($param['card_type']) && $param['card_type'] != '') {
            $where[] = ['card_type', '=', $param['card_type']];
        }
        if( isset($param['id_card']) && $param['id_card'] != '') {
            $where[] = ['id_card', 'LIKE', '%'. $param['id_card'] .'%'];
        }
        if( isset($param['phone']) && $param['phone'] != '') {
            $where[] = ['phone', 'LIKE', '%'. $param['phone'] .'%'];
        }
        if( isset($param['leave_time']) && $param['leave_time'] != '') {
            $where[] = ['leave_time', '=', $param['leave_time']];
        }
        if( isset($param['expect_return_time']) && $param['expect_return_time'] != '') {
            $where[] = ['expect_return_time', '=', $param['expect_return_time']];
        }
        if( isset($param['isasterisk']) && $param['isasterisk'] !== '') {
            $where[] = ['isasterisk', '=', $param['isasterisk']];
        }
        if( isset($param['start_date']) && $param['start_date']) {
            $where[] = ['create_time', '>', strtotime($param['start_date'].' 00:00:00') ];
        }
        if( isset($param['end_date']) && $param['end_date']) {
            $where[] = ['create_time', '<=', strtotime($param['end_date'].' 23:59:59') ];
        }
        if( isset($param['create_date']) && $param['create_date']) {
            $where[] = function ($query) use($param)  {
                $query->whereDay('create_time',$param['create_date']);
            };
        }
        if( isset($param['error_types']) && $param['error_types'] != '') {
            if( strstr($param['error_types'],'id_verify_result') ){
                $where[] = ['id_verify_result', '=', 2 ];
            }
            if( strstr($param['error_types'],'ocr_result') ){
                $where[] = ['ocr_result', '=', 2 ];
            }
        }
        if( isset($param['province_id']) && $param['province_id'] > 0) {
            $where[] = ['province_id', '=', $param['province_id']];
        }
        if( isset($param['city_id']) && $param['city_id'] > 0) {
            $where[] = ['city_id', '=', $param['city_id']];
        }
        if( isset($param['county_id']) && $param['county_id'] > 0) {
            $where[] = ['county_id', '=', $param['county_id']];
        }
        if( isset($param['street_id']) && $param['street_id'] > 0) {
            $where[] = ['street_id', '=', $param['street_id']];
        }
        if( isset($param['yw_street']) && $param['yw_street'] != '') {
            $where[] = ['yw_street', 'LIKE', '%'. $param['yw_street'] .'%'];
        }
        if( isset($param['yw_street_id']) && $param['yw_street_id']  > 0) {
            $where[] = ['yw_street_id', '=', $param['yw_street_id']];
        }
        if( isset($param['address']) && $param['address'] != '') {
            $where[] = ['address', 'LIKE', '%'. $param['address'] .'%'];
        }
        if( isset($param['travel_route']) && $param['travel_route'] != '') {
            $where[] = ['travel_route', 'LIKE', '%'. $param['travel_route'] .'%'];
        }
        if( isset($param['leave_riskarea_time']) && $param['leave_riskarea_time'] != '') {
            $where[] = ['leave_riskarea_time', '=', $param['leave_riskarea_time']];
        }
        if( isset($param['arrival_transport_mode']) && $param['arrival_transport_mode'] != '') {
            $where[] = ['arrival_transport_mode', '=', $param['arrival_transport_mode']];
        }
        if( isset($param['arrival_sign']) && $param['arrival_sign'] != '') {
            $where[] = ['arrival_sign', '=', $param['arrival_sign']];
        }
        return $where;
    }
    public function getStatistic($param,$statistic_type='leave')
    {
        $where = $this->_param_where($param);

        if( isset($param['keyword']) && $param['keyword']) {
            $where[] = ['real_name|phone|id_card', 'LIKE', '%'. $param['keyword'] .'%'];
        }

        if( isset($param['start_date']) && $param['start_date']) {
            $where[] = ['create_time', '>', strtotime($param['start_date'].' 00:00:00') ];
        }
        if( isset($param['end_date']) && $param['end_date']) {
            $where[] = ['create_time', '<=', strtotime($param['end_date'].' 23:59:59') ];
        }

        switch($statistic_type){
            case 'fromwhere':
                // 省
                if( isset($param['province']) && $param['province'] != '') {
                    $where[] = ['province', 'LIKE', '%'. $param['province'] .'%'];
                }

                $where[] = ['declare_type','in',['come','riskarea']];
                return $this->getModel()::where($where)
                        ->field('
                                province,
                                SUM(if(declare_type="come",1,0)) as come_nums,
                                SUM(if(declare_type="riskarea",1,0)) as riskarea_nums,
                                SUM(if(declare_type in ("riskarea","come"),1,0)) as total_nums
                        ')
                        ->group('province_id')
                        ->select();
                break;
            case 'ywstreet':
                // 街道
                if( isset($param['yw_street']) && $param['yw_street'] != '') {
                    $where[] = ['yw_street', 'LIKE', '%'. $param['yw_street'] .'%'];
                }

                $where[] = ['declare_type','in',['come','riskarea']];
                return $this->getModel()::where($where)
                        ->field('
                                yw_street,
                                SUM(if(declare_type="come",1,0)) as come_nums,
                                SUM(if(declare_type="riskarea",1,0)) as riskarea_nums,
                                SUM(if(declare_type in ("riskarea","come"),1,0)) as total_nums
                        ')
                        ->group('yw_street_id')
                        ->select();
                break;
            case 'age':
                return $this->_tmp_age_nums($where);
                break;

        }
    }    


    // 获取年龄段段的  临时可能被取消的方法
    private function _tmp_age_nums($where){
        $data = $this->getModel()::where($where)
            ->field('
                    SUM(if( age>0 and age<=16 and declare_type="leave",1,0)) as leave_nums_0_16,
                    SUM(if( age>0 and age<=16 and declare_type="come",1,0)) as come_nums_0_16,
                    SUM(if( age>0 and age<=16 and declare_type="riskarea",1,0)) as riskarea_nums_0_16,
                    SUM(if( age>0 and age<=16 and declare_type in ("riskarea","come","leave"),1,0)) as total_nums_0_16,

                    SUM(if( age>16 and age<=35 and declare_type="leave",1,0)) as leave_nums_17_35,
                    SUM(if( age>16 and age<=35 and declare_type="come",1,0)) as come_nums_17_35,
                    SUM(if( age>16 and age<=35 and declare_type="riskarea",1,0)) as riskarea_nums_17_35,
                    SUM(if( age>16 and age<=35 and declare_type in ("riskarea","come","leave"),1,0)) as total_nums_17_35,

                    SUM(if( age>35 and age<=60 and declare_type="leave",1,0)) as leave_nums_36_60,
                    SUM(if( age>35 and age<=60 and declare_type="come",1,0)) as come_nums_36_60,
                    SUM(if( age>35 and age<=60 and declare_type="riskarea",1,0)) as riskarea_nums_36_60,
                    SUM(if( age>35 and age<=60 and declare_type in ("riskarea","come","leave"),1,0)) as total_nums_36_60,

                    SUM(if( age>60 and declare_type="leave",1,0)) as leave_nums_61_100,
                    SUM(if( age>60 and declare_type="come",1,0)) as come_nums_61_100,
                    SUM(if( age>60 and declare_type="riskarea",1,0)) as riskarea_nums_61_100,
                    SUM(if( age>60 and declare_type in ("riskarea","come","leave"),1,0)) as total_nums_61_100

            ')
            ->select()->toArray();
 
        $ssdata = $data[0];
        $new_data = [];
        foreach($ssdata as $key => $value){
            // var_dump($key);
            $tmp = explode('_nums_',$key);
            $item = [];
            $item['name'] = $tmp[1];
            $new_data[$item['name']]['name'] = str_replace('_','~',$item['name']) ;
            if(strstr($key,'leave')){
                $new_data[$item['name']]['leave_nums'] = $value;
            }
            if(strstr($key,'come')){
                $new_data[$item['name']]['come_nums'] = $value;
            }
            if(strstr($key,'riskarea')){
                $new_data[$item['name']]['riskarea_nums'] = $value;
            }
            if(strstr($key,'total')){
                $new_data[$item['name']]['total_nums'] = $value;
            }
        }
        return array_values($new_data);
    }
    
    //获取最新一次离义申报
    public function getLastLeaveDeclare($param)
    {
        $where = [];
        $where[] = ['id_card', '=', $param['id_card']];
        $where[] = ['declare_type', '=', 'leave'];
        return $this->getModel()::where($where)
                ->field('id,province_id,province,city_id,city,county_id,county,leave_time')
                ->order('id desc')
                ->find();
    }

    //获取最新一次来义申报
    public function getLastComeDeclare($id_card)
    {
        $where = [];
        $where[] = ['id_card', '=', $id_card];
        $where[] = ['declare_type', '<>', 'leave'];
        return $this->getModel()::where($where)
                ->field('id,travel_img,travel_route,declare_type,control_state')
                ->order('id desc')
                ->find();
    }

    //3个月内超期未返义人员列表
    public function getOverdueReturnTimeList()
    {
        $where = [];
        $where[] = ['position_type', '=', 'leave'];
        $where[] = ['send_sms', '=', 0];
        $where[] = ['create_time', '>=', strtotime('-3 month')];
        $where[] = ['expect_return_time', '<', date('Y-m-d')];
        return $this->getModel()::field('id,id_card,real_name,phone')
                ->where($where)
                ->select()
                ->toArray();
    }

    //获取未认证身份证的数据
    public function getUnVerifyIdCardList()
    {
        $where = [];
        $where[] = ['id_verify_result', '=', 0];
        
        return $this->getModel()::where($where)
                ->field('id,card_type,id_card,real_name,phone,declare_type,expect_return_time')
                ->order('id desc')
                ->limit(50)
                ->select()
                ->toArray();
    }

    public function getUnJkmList(){
        $where = [];
        $where[] = ['jkm_get', '=', 0]; // 未获得过健康码
        
        return $this->getModel()::where($where)
                ->field('id,card_type,id_card,real_name,phone,declare_type,expect_return_time')
                ->order('id desc') // 倒序
                ->limit(50)
                ->select()
                ->toArray();
    }

    // 未获得过省核酸检测
    public function getUnShsjcList(){
        $where = [];
        $where[] = ['hsjc_get', '=', 0];
        
        return $this->getModel()::where($where)
                ->field('id,card_type,id_card,real_name,phone,declare_type,expect_return_time')
                ->order('id desc') // 倒序
                ->limit(50)
                ->select()
                ->toArray();
    }

    // 已获得过省核酸检测但结果为空
    public function getUnYwhsjcList(){
        $where = [];
        $where[] = ['hsjc_get', '=', 1];
        
        return $this->getModel()::where($where)
                ->field('id,card_type,id_card,real_name,phone,declare_type,expect_return_time')
                ->whereNull('hsjc_time')
                ->order('id desc') // 倒序
                ->limit(50)
                ->select()
                ->toArray();
    }

    //未获得新冠疫苗接种的数据
    public function getUnXgymjzList()
    {
        $where = [];
        //$where[] = ['id_verify_result', '>', 0]; //已认证身份
        $where[] = ['xgymjz_get', '=', 0];
        
        return $this->getModel()::where($where)
                ->field('id,card_type,id_card,real_name,phone,declare_type,expect_return_time')
                ->order('id desc')
                ->limit(500)
                ->select()
                ->toArray();
    }

    //获取未ocr识别的数据
    public function getDeclareUnOcr()
    {
        $where = [];
        $where[] = ['ocr_result', '=', 0]; //未ocr识别
        $where[] = ['declare_type', '<>', 'leave'];
        
        return $this->getModel()::where($where)
                ->field('id,id_card,declare_type,travel_img')
                ->order('id desc')
                ->limit(50)
                ->select()
                ->toArray();
    }

    // 今日的申报数量和总申报数量
    public function todayAndTotalNums(){
        $today_time = strtotime(Date('Y-m-d'));
        $ssdata = Db::name('declare')
            ->field('
                count(id) as total_nums,
                sum(if( declare_type="leave",1,0)) as leave_nums,
                sum(if( declare_type="come",1,0)) as come_nums,
                sum(if( declare_type="riskarea",1,0)) as riskarea_nums,
                sum(if( create_time>'.$today_time.' ,1,0)) as today_total_nums,
                sum(if( declare_type="leave" and create_time>'.$today_time.' ,1,0)) as today_leave_nums,
                sum(if( declare_type="come" and create_time>'.$today_time.' ,1,0)) as today_come_nums,
                sum(if( declare_type="riskarea" and create_time>'.$today_time.' ,1,0)) as today_riskarea_nums
            ')
            ->select()->toArray();

        $data = $ssdata[0];
        return $data;
    }

    // 今天来义的高风险人员信息
    public function todayRiskareaData(){
        $param['start_date'] = Date('Y-m-d');
        $param['end_date'] = Date('Y-m-d');
        $param['size'] = 10;
        return $this->getList($param);
    }

    // 今天未按时返义的镇街统计
    public function todayBackouttimeGroupByStreet(){
        $where = [];

        $where[] = ['declare_type','=','leave'];
        $where[] =  function ($query)  {
            $query->where('is_back','=',0);
            $query->where('expect_return_time','=',Date('Y-m-d'));
        };
        return $this->getModel()::where($where)
                ->field(' province,count(id) as count_nums ')
                ->group('province')
                ->select()->toArray();
    }

    public function backouttimeGroupByProvince(){
        $where = [];

        $where[] = ['declare_type','=','leave'];
        $where[] =  function ($query)  {
            $query->where('is_back','=',0);
            $query->where('expect_return_time','<',Date('Y-m-d'));
        };
        return $this->getModel()::where($where)
                ->field(' province,count(id) as count_nums ')
                ->group('province')
                ->select()->toArray();
    }

    /* 大屏数据 start */
    public function getScreenDeclareByDate($param)
    {
        $where = [];
        $where[] = ['create_time', '>=', $param['start_time']];
        $where[] = ['create_time', '<=', $param['end_time']];
        
        return $this->getModel()::where($where)
                ->field('FROM_UNIXTIME(create_time, "%m-%d") time,count(id) count,declare_type')
                ->group('time,declare_type')
                ->select()
                ->toArray();
    }

    public function getScreenDeclareByHour($param)
    {
        $where = [];
        $where[] = ['create_time', '>=', $param['start_time']];
        $where[] = ['create_time', '<=', $param['end_time']];
        
        return $this->getModel()::where($where)
                ->field('FROM_UNIXTIME(create_time, "%H") time,count(id) count,declare_type')
                ->group('time,declare_type')
                ->select()
                ->toArray();
    }

    // 今天的
    public function getScreenSourceProvince()
    {
        $where = [];
        // $where[] = ['create_time', '>=', strtotime('-14 day')];
        $where[] = ['create_time', '>=', strtotime(Date('Y-m-d'))];
        $where[] = ['declare_type', '<>', 'leave'];
        
        return $this->getModel()::where($where)
                ->field('count(id) count,province')
                ->group('province_id')
                ->order('count', 'desc')
                ->select()
                ->toArray();
    }

    public function getFloatingPopulation()
    {
        $where = [];
        $where[] = ['create_time', '>=', strtotime('-7 day')];
        
        // 因为用time进行group有 01-07  12-31 的排序bug，
        // 所以用了yeartime进行group
        return $this->getModel()::where($where)
                ->field('FROM_UNIXTIME(create_time, "%m-%d") time,FROM_UNIXTIME(create_time, "%Y-%m-%d") yeartime,sum(IF(declare_type <> "leave", 1, 0)) come_count, sum(IF(declare_type = "leave", 1, 0)) leave_count')
                ->group('yeartime')
                ->select()
                ->toArray();

    }

    // 今天的
    public function getRiskareaCome()
    {
        $where = [];
        //$where[] = ['is_warning', '=', 1];
        $where[] = ['declare_type', '=', 'riskarea'];
        //$where[] = ['create_time', '>=', strtotime('-7 day')];
        $where[] = ['create_time', '>=', strtotime(Date('Y-m-d'))];
        
        return $this->getModel()::where($where)
                ->field('id,real_name,yw_street,province,arrival_time')
                ->select()
                ->toArray();
    }

    public function getRiskareaYWStreet()
    {
        $where = [];
        //$where[] = ['is_warning', '=', 1];
        $where[] = ['declare_type', '=', 'riskarea'];
        //$where[] = ['create_time', '>=', strtotime('-7 day')];
        $where[] = ['create_time', '>=', strtotime(Date('Y-m-d'))];
        
        return $this->getModel()::where($where)
                ->field('count(id) count,yw_street_id,yw_street')
                ->group('yw_street_id')
                ->select()
                ->toArray();
    }

    public function getComeYWStreet()
    {
        $where = [];
        $where[] = ['declare_type', '<>', 'leave'];
        //$where[] = ['create_time', '>=', strtotime('-7 day')];
        $where[] = ['create_time', '>=', strtotime(Date('Y-m-d'))];
        
        return $this->getModel()::where($where)
                ->field('count(id) count,yw_street_id,yw_street')
                ->group('yw_street_id')
                ->select()
                ->toArray();
    }

    public function getRiskareaProvinceCome()
    {
        $where = [];
        //$where[] = ['is_warning', '=', 1];
        $where[] = ['declare_type', '=', 'riskarea'];
        $where[] = ['create_time', '>=', strtotime(date('Y-m-d'))];
        
        return $this->getModel()::where($where)
                ->field('count(id) count,province')
                ->group('province_id')
                ->select()
                ->toArray();
    }

    public function getRiskareaProvinceLeave()
    {
        $where = [];
        $where[] = ['declare_type', '=', 'leave'];
        $where[] = ['create_time', '>=', strtotime(date('Y-m-d'))];
        
        return $this->getModel()::where($where)
                ->field('count(id) count,province')
                ->group('province_id')
                ->select()
                ->toArray();
    }

    public function getOwnDeclareNums(){
        $today_time = strtotime(Date('Y-m-d'));
        $ssdata = Db::name('declare')
            ->field('
                count(id) as total_nums,
                sum(if( declare_type="leave",1,0)) as leave_nums,
                sum(if( declare_type="come",1,0)) as come_nums,
                sum(if( declare_type="riskarea",1,0)) as riskarea_nums,
                sum(if(  create_time>'.$today_time.' ,1,0)) as today_total_nums,
                sum(if( declare_type="leave" and create_time>'.$today_time.' ,1,0)) as today_leave_nums,
                sum(if( declare_type="come" and create_time>'.$today_time.' ,1,0)) as today_come_nums,
                sum(if( declare_type="riskarea" and create_time>'.$today_time.' ,1,0)) as today_riskarea_nums
            ')
            ->select()->toArray();

        $data = $ssdata[0];
        return $data;
    }

    public function getControlNums(){
        $today_time = strtotime(Date('Y-m-d'));

        $where = [];

        $where[] =  function ($query)  {
            $query->whereRaw('declare_type = :declare_type or is_warning = :is_warning ', ['declare_type' => 'riskarea', 'is_warning' => 1]);
        };

        $data = Db::name('declare')
            ->field('
                concat(province,city) as diqu,
                count(id) as nums,
                SUM(if(declare_type = "riskarea",1,0)) as zzsb_nums,
                SUM(if(declare_type <> "riskarea" and is_warning=1,1,0)) as xtjc_nums,
                SUM(if(control_state in ("无需管控","重复","退回-不在义乌"),1,0)) as not_nums,
                SUM(if(control_state in ("日常健康监测","已隔离"),1,0)) as has_nums,
                SUM(if(control_state in ("结束管控"),1,0)) as over_nums,
                SUM(if(control_state = "" and declare_type="riskarea",1,0)) as todo_nums,
                SUM(if(control_state not in ("结束管控","日常健康监测","已隔离","无需管控","重复","退回-不在义乌",""),1,0)) as other_nums

            ')
            ->where($where)
            ->group('province,city')
            ->select()->toArray();

        // 进行某个字段的排序

        $last_nums = array_column($data,'nums');
        array_multisort($last_nums,SORT_DESC,$data);
        
        

        $total_nums = 0;
        $has_nums = 0;
        $over_nums = 0;

        
        foreach($data as $key => $value){
            // 目前 other 全部归为 无需管控
            $data[$key]['not_nums'] += $value['other_nums'];
            $data[$key]['other_nums'] += 0;
            // 需合计的字段
            $total_nums += $value['nums'];
            $has_nums += $value['has_nums'];
            $over_nums += $value['over_nums'];
        }
        $statistics['total_nums'] = $total_nums;
        $statistics['has_nums'] = $has_nums;
        $statistics['over_nums'] = $over_nums;

        $return['list']       = $data;
        $return['statistics'] = $statistics;

        return $return;
    }


    /* 大屏数据 end */

    public function getPre3DayDateNums(){
        $where = [];
        $where[] = ['create_time', '>=', strtotime('-30 day')];
        $where[] = ['create_time', '<', strtotime(Date('Y-m-d').' 00:00:00')];
        
        return $this->getModel()::where($where)
                ->field('
                    FROM_UNIXTIME(create_time, "%Y-%m-%d") date,
                    province,
                    SUM( if(declare_type="leave",1,0) ) as leave_nums,
                    SUM( if(declare_type="come",1,0) ) as come_nums,
                    SUM( if(declare_type="riskarea",1,0) ) as riskarea_nums,
                    count(id) as total_nums')
                ->group('date,province')
                ->select()
                ->toArray();
    }

    public function getRecentComeList($param)
    {
        $where = $this->_param_where($param);
        $where[] = ['declare_type', '=', 'come'];
        
        return $this->getModel()::where($where)
                ->order('id desc')
                ->append(['destination'])
                ->paginate($param['size'])
                ->toArray();
    }

}
