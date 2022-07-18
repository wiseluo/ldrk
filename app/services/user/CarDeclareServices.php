<?php
declare (strict_types=1);

namespace app\services\user;

use app\services\user\BaseServices;
use app\services\MessageServices;
use app\dao\CarDeclareDao;
use app\dao\UserDao;

class CarDeclareServices extends BaseServices
{
    public function __construct(CarDeclareDao $dao)
    {
        $this->dao = $dao;
    }

    public function getTravelService(int $id){
        $declare = $this->dao->get($id);
        if($declare){

            // 指定信息
            $return = [];
            $return['plate_number'] = $declare['plate_number'];
            $return['travel_route'] = $declare['travel_route'];
            $return['is_warning'] = $declare['is_warning'];
            $return['is_warning_text'] = $declare['is_warning'] == 1 ? '有经过' : '无';
            $return['api_result'] = $declare['api_result'];
            if((string)$declare['travel_data'] != ''){
                $return['travel_data'] = json_decode($declare['travel_data'],true);
            }else{
                $return['travel_data'] = [];
            }
            return ['status' => 1, 'msg' => '成功','data'=>$return];
        }else{
            return ['status' => 0, 'msg' => '失败'];
        }
    }

    public function postService($param)
    {
        $sign = md5($param['id_card'] .'|'. time());
        $data = [
            'sign' => $sign,
            'real_name' => $param['real_name'],
            'id_card' => $param['id_card'],
            'phone' => $param['phone'],
            'plate_number' => $param['plate_number'],
        ];
        
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
                    'card_type' => 'id',
                    'uniqid' => randomCode(12),
                ];
                $userDao->save($user_data);
            }
            return ['status' => 1, 'msg' => '申报成功','data'=>['id'=>$declare->id,'sign'=>$sign]];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '申报失败-'. $e->getMessage()];
        }
    }
    
}
