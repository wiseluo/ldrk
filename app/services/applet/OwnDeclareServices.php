<?php

namespace app\services\applet;

use app\services\applet\BaseServices;
use app\services\MessageServices;
use app\dao\OwnDeclareDao;
use app\dao\UserDao;
use app\dao\BarrierDistrictDao;

class OwnDeclareServices extends BaseServices
{
    public function __construct(OwnDeclareDao $dao)
    {
        $this->dao = $dao;
    }

    public function ownDeclareLeaveService($param, $userInfo)
    {
        $data = [
            'declare_type' => 'leave',
            'real_name' => $userInfo['real_name'],
            'card_type' => 'id',
            'id_card' => $userInfo['id_card'],
            'phone' => $userInfo['phone'],
            'province_id' => $param['province_id'],
            'province' => $param['province'],
            'city_id' => $param['city_id'],
            'city' => $param['city'],
            'county_id' => $param['county_id'],
            'county' => $param['county'],
            'street_id' => $param['street_id'],
            'street' => $param['street'],
            'leave_time' => $param['leave_time'],
            'expect_return_time' => $param['expect_return_time'],
            'ocr_result' => 3,
            'id_verify_result' => 1,
        ];
        
        try {
            $declare = $this->dao->save($data);
            return ['status' => 1, 'msg' => '申报成功','data'=>['id'=> $declare->id]];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '申报失败-'. $e->getMessage()];
        }
    }
    
    public function ownDeclareComeService($param, $userInfo)
    {
        $data = [
            'declare_type' => 'come',
            'real_name' => $userInfo['real_name'],
            'card_type' => 'id',
            'id_card' => $userInfo['id_card'],
            'phone' => $userInfo['phone'],
            'province_id' => $param['province_id'],
            'province' => $param['province'],
            'city_id' => $param['city_id'],
            'city' => $param['city'],
            'county_id' => $param['county_id'],
            'county' => $param['county'],
            'street_id' => $param['street_id'],
            'street' => $param['street'],
            'yw_street_id' => $param['yw_street_id'],
            'yw_street' => $param['yw_street'],
            'address' => $param['address'],
            'arrival_time' => $param['arrival_time'],
            'travel_img' => $param['travel_img'],
            'has_report' => $param['has_report'],
            'id_verify_result' => 1,
        ];
        
        try {
            $declare = $this->dao->save($data);
            //发送短信
            $message_data = [
                'receive_id'=> 0,
                'receive'=> $data['real_name'],
                'phone'=> $data['phone'],
                'source_id'=> $declare->id,
            ];
            $param_data = [
                'real_name'=> $data['real_name'],
                'datetime'=> date('Y年m月d日 H:i'),
                'declare_type'=> '来返义',
                'id_card'=> $data['id_card'],
                'phone'=> $data['phone'],
                'yw_street'=> $data['yw_street'],
            ];
            app()->make(MessageServices::class)->asyncMessage('template002', $message_data, $param_data);
            return ['status' => 1, 'msg' => '申报成功','data'=>['id'=> $declare->id]];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '申报失败-'. $e->getMessage()];
        }
    }
    
    public function ownDeclareRiskareaService($param, $userInfo)
    {
        $data = [
            'declare_type' => 'riskarea',
            'real_name' => $userInfo['real_name'],
            'card_type' => 'id',
            'id_card' => $userInfo['id_card'],
            'phone' => $userInfo['phone'],
            'province_id' => $param['province_id'],
            'province' => $param['province'],
            'city_id' => $param['city_id'],
            'city' => $param['city'],
            'county_id' => $param['county_id'],
            'county' => $param['county'],
            'street_id' => $param['street_id'],
            'street' => $param['street'],
            'leave_riskarea_time' => $param['leave_riskarea_time'],
            'yw_street_id' => $param['yw_street_id'],
            'yw_street' => $param['yw_street'],
            'address' => $param['address'],
            'arrival_time' => $param['arrival_time'],
            'arrival_transport_mode' => $param['arrival_transport_mode'],
            'arrival_sign' => $param['arrival_sign'],
            'travel_img' => $param['travel_img'],
            'has_report' => $param['has_report'],
            'is_warning' => 1,
            'id_verify_result' => 1,
        ];

        try {
            $declare = $this->dao->save($data);
            //发送短信
            $message_data = [
                'receive_id'=> 0,
                'receive'=> $data['real_name'],
                'phone'=> $data['phone'],
                'source_id'=> $declare->id,
            ];
            $param_data = [
                'real_name'=> $data['real_name'],
                'datetime'=> date('Y年m月d日 H:i'),
                'declare_type'=> '中高风险来义',
                'id_card'=> $data['id_card'],
                'phone'=> $data['phone'],
                'yw_street'=> $data['yw_street'],
            ];
            app()->make(MessageServices::class)->asyncMessage('template002', $message_data, $param_data);
            return ['status' => 1, 'msg' => '申报成功','data'=>['id'=>$declare->id]];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '申报失败-'. $e->getMessage()];
        }
    }

    public function ownDeclareBarrierService($param, $userInfo)
    {
        $data = [
            'declare_type' => 'barrier',
            'real_name' => $userInfo['real_name'],
            'card_type' => 'id',
            'id_card' => $userInfo['id_card'],
            'phone' => $userInfo['phone'],
            'yw_street_id' => $param['yw_street_id'],
            'yw_street' => $param['yw_street'],
            'ocr_result' => 3,
            'isasterisk' => $param['isasterisk'],
            'id_verify_result' => 1,
        ];
        if(isset($param['city_id']) && $param['city_id'] != '') { //兼容旧版本
            $data['province_id'] = 22;
            $data['province'] = '浙江省';
            $data['city_id'] = $param['city_id'];
            $data['city'] = $param['city'];
        }else{
            $barrierDistrictDao = app()->make(BarrierDistrictDao::class);
            $barrier_district = $barrierDistrictDao->get($param['district_id']);
            if($barrier_district == null) {
                return ['status' => 0, 'msg' => '卡口申报城市不存在'];
            }
            $data['province_id'] = $barrier_district['province_id'];
            $data['province'] = $barrier_district['province'];
            $data['city_id'] = $barrier_district['city_id'];
            $data['city'] = $barrier_district['city'];
            $data['county_id'] = $barrier_district['county_id'];
            $data['county'] = $barrier_district['county'];
            $data['street_id'] = $barrier_district['street_id'];
            $data['street'] = $barrier_district['street'];
        }
        
        try {
            $declare = $this->dao->save($data);
            //发送短信
            $message_data = [
                'receive_id'=> 0,
                'receive'=> $data['real_name'],
                'phone'=> $data['phone'],
                'source_id'=> $declare->id,
            ];
            $param_data = [
                'real_name'=> $data['real_name'],
                'datetime'=> date('Y年m月d日 H:i'),
                'declare_type'=> '卡口来义',
                'id_card'=> $data['id_card'],
                'phone'=> $data['phone'],
                'yw_street'=> $data['yw_street'],
            ];
            app()->make(MessageServices::class)->asyncMessage('template002', $message_data, $param_data);
            return ['status' => 1, 'msg' => '申报成功','data'=>['id'=>$declare->id]];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '申报失败-'. $e->getMessage()];
        }
    }

    public function ownDeclareQuarantineService($param, $userInfo)
    {
        $data = [
            'declare_type' => 'quarantine',
            'real_name' => $userInfo['real_name'],
            'card_type' => 'id',
            'id_card' => $userInfo['id_card'],
            'phone' => $userInfo['phone'],
            'province_id' => 22,
            'province' => '浙江省',
            'city_id' => 307,
            'city' => '绍兴市',
            'county_id' => 2840,
            'county' => '上虞区',
            'street_id' => 0,
            'street' => '',
            'yw_street_id' => isset($param['yw_street_id']) ? $param['yw_street_id'] : 0,
            'yw_street' => isset($param['yw_street']) ? $param['yw_street'] : '',
            'ocr_result' => 3,
            'isasterisk' => 1,
            'arrival_time' => Date('Y-m-d'),
            'id_verify_result' => 1,
        ];

        try {
            $declare = $this->dao->save($data);
            //发送短信
            $message_data = [
                'receive_id'=> 0,
                'receive'=> $data['real_name'],
                'phone'=> $data['phone'],
                'source_id'=> $declare->id,
            ];
            $param_data = [
                'real_name'=> $data['real_name'],
                'datetime'=> date('Y年m月d日 H:i'),
                'declare_type'=> '防疫隔离',
                'id_card'=> $data['id_card'],
                'phone'=> $data['phone'],
                'yw_street'=> $data['yw_street'],
            ];
            app()->make(MessageServices::class)->asyncMessage('template002', $message_data, $param_data);
            return ['status' => 1, 'msg' => '申报成功','data'=>['id'=>$declare->id]];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '申报失败-'. $e->getMessage()];
        }
    }

    public function resultWholeService($param, $userInfo)
    {
        $declare = $this->dao->get($param['id']);
        if($declare == null) {
            return ['status' => 0, 'msg' => '该申报不存在'];
        }
        if( strtotime($declare['hsjc_time']) > (time() - 24*3600) ){
            $hsjc_24_info = '有';
        }else{
            $hsjc_24_info = '无';
        }
        $data = [
            'real_name'=> $userInfo['real_name'],
            'phone'=> $userInfo['phone'],
            'id_card'=> $userInfo['id_card'],
            'vaccination'=> $declare['vaccination'],
            'vaccination_date'=> $declare['vaccination_date'],
            'vaccination_times'=> $declare['vaccination_times'],
            'xgymjz_get'=> $declare['xgymjz_get'],
            'jkm_time'=> $declare['jkm_time'],
            'jkm_mzt'=> $declare['jkm_mzt'],
            'jkm_get'=> $declare['jkm_get'],
            'hsjc_time'=> $declare['hsjc_time'],
            'hsjc_jcjg'=> $declare['hsjc_jcjg'],
            'hsjc_result'=> $declare['hsjc_result'],
            'hsjc_get'=> $declare['hsjc_get'],
            'xcm_result'=> $declare['xcm_result'],
            'xcm_gettime' => $userInfo['xcm_gettime'],
            'ryxx_result'=> $declare['ryxx_result'],
            'ryxx_cc'=> isset($userInfo['ryxx_cc']) ? $userInfo['ryxx_cc'] : ($declare['ryxx_result'] == '无需管控' ? '绿' : '黄'),
            'hsjc_24_info'=> $hsjc_24_info,
            'last_datetime' => Date('m月d日 H:i'),
            'sign' => md5('ldrk'. $userInfo['id_card'].$userInfo['phone'].time() .'ldrk'),
        ];
        return ['status' => 1, 'msg' => '成功', 'data'=> $data];
    }


    public function lastLeaveService($userInfo)
    {
        $param['id_card'] = $userInfo['id_card'];
        return $this->dao->getLastLeaveDeclare($param);
    }
}
