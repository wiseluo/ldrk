<?php
declare (strict_types=1);

namespace app\services\user;

use app\services\user\BaseServices;
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

    public function ownDeclareLeaveService($param)
    {
        $data = [
            'declare_type' => 'leave',
            'real_name' => $param['real_name'],
            'card_type' => $param['card_type'],
            'id_card' => $param['id_card'],
            'phone' => $param['phone'],
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
        ];
        if($param['card_type'] != 'id') {
            $data['id_verify_result'] = 3;
        }
        
        try {
            $declare = $this->dao->save($data);
            //创建用户
            $userDao = app()->make(UserDao::class);
            $user = $userDao->get(['id_card'=> $param['id_card']]);
            if($user == null) {
                $user_data = [
                    'phone' => $param['phone'],
                    'real_name' => $param['real_name'],
                    'id_card' => $param['id_card'],
                    'card_type' => $param['card_type'],
                    'uniqid' => randomCode(12),
                ];
                $userDao->save($user_data);
            }
            return ['status' => 1, 'msg' => '申报成功','data'=>['id'=>$declare->id]];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '申报失败-'. $e->getMessage()];
        }
    }
    
    public function ownDeclareComeService($param)
    {
        $data = [
            'declare_type' => 'come',
            'real_name' => $param['real_name'],
            'card_type' => $param['card_type'],
            'id_card' => $param['id_card'],
            'phone' => $param['phone'],
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
        ];
        if($param['card_type'] != 'id') {
            $data['id_verify_result'] = 3;
        }
        
        try {
            $declare = $this->dao->save($data);
            //创建用户
            $userDao = app()->make(UserDao::class);
            $user = $userDao->get(['id_card'=> $param['id_card']]);
            if($user == null) {
                $user_data = [
                    'phone' => $param['phone'],
                    'real_name' => $param['real_name'],
                    'id_card' => $param['id_card'],
                    'card_type' => $param['card_type'],
                    'uniqid' => randomCode(12),
                ];
                $userDao->save($user_data);
            }
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
            return ['status' => 1, 'msg' => '申报成功','data'=>['id'=>$declare->id]];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '申报失败-'. $e->getMessage()];
        }
    }
    
    public function ownDeclareRiskareaService($param)
    {
        $data = [
            'declare_type' => 'riskarea',
            'real_name' => $param['real_name'],
            'card_type' => $param['card_type'],
            'id_card' => $param['id_card'],
            'phone' => $param['phone'],
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
        ];
        if($param['card_type'] != 'id') {
            $data['id_verify_result'] = 3;
        }

        try {
            $declare = $this->dao->save($data);
            //创建用户
            $userDao = app()->make(UserDao::class);
            $user = $userDao->get(['id_card'=> $param['id_card']]);
            if($user == null) {
                $user_data = [
                    'phone' => $param['phone'],
                    'real_name' => $param['real_name'],
                    'id_card' => $param['id_card'],
                    'card_type' => $param['card_type'],
                    'uniqid' => randomCode(12),
                ];
                $userDao->save($user_data);
            }
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

    public function ownDeclareBarrierService($param)
    {
        $data = [
            'declare_type' => 'barrier',
            'real_name' => $param['real_name'],
            'card_type' => $param['card_type'],
            'id_card' => $param['id_card'],
            'phone' => $param['phone'],
            'yw_street_id' => $param['yw_street_id'],
            'yw_street' => $param['yw_street'],
            'ocr_result' => 3,
            'isasterisk' => $param['isasterisk'],
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
        
        if($param['card_type'] != 'id') {
            $data['id_verify_result'] = 3;
        }

        try {
            $declare = $this->dao->save($data);
            //创建用户
            $userDao = app()->make(UserDao::class);
            $user = $userDao->get(['id_card'=> $param['id_card']]);
            if($user == null) {
                $user_data = [
                    'phone' => $param['phone'],
                    'real_name' => $param['real_name'],
                    'id_card' => $param['id_card'],
                    'card_type' => $param['card_type'],
                    'uniqid' => randomCode(12),
                ];
                $userDao->save($user_data);
            }
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
                'declare_type'=> '上海来返义', // 原:卡口来义
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

    public function ownDeclareQuarantineService($param)
    {
        $data = [
            'declare_type' => 'quarantine',
            'real_name' => $param['real_name'],
            'card_type' => $param['card_type'],
            'id_card' => $param['id_card'],
            'phone' => $param['phone'],
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
        ];
        if($param['card_type'] != 'id') {
            $data['id_verify_result'] = 3;
        }

        try {
            $declare = $this->dao->save($data);
            //创建用户
            $userDao = app()->make(UserDao::class);
            $user = $userDao->get(['id_card'=> $param['id_card']]);
            if($user == null) {
                $user_data = [
                    'phone' => $param['phone'],
                    'real_name' => $param['real_name'],
                    'id_card' => $param['id_card'],
                    'card_type' => $param['card_type'],
                    'uniqid' => randomCode(12),
                ];
                $userDao->save($user_data);
            }
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

    public function other($param)
    {
        $data = [
            'declare_type' => 'come',
            'real_name' => $param['real_name'],
            'card_type' => $param['card_type'],
            'id_card' => $param['id_card'],
            'phone' => $param['phone'],
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
        ];
        if($param['card_type'] != 'id') {
            $data['id_verify_result'] = 3;
        }

        try {
            $declare = $this->dao->save($data);
            //创建用户
            $userDao = app()->make(UserDao::class);
            $user = $userDao->get(['id_card'=> $param['id_card']]);
            if($user == null) {
                $user_data = [
                    'phone' => $param['phone'],
                    'real_name' => $param['real_name'],
                    'id_card' => $param['id_card'],
                    'card_type' => $param['card_type'],
                    'uniqid' => randomCode(12),
                ];
                $userDao->save($user_data);
            }
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

    public function lastLeaveService($param)
    {
        return $this->dao->getLastLeaveDeclare($param);
    }
}
