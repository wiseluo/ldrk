<?php

namespace app\services\applet;

use app\services\applet\BaseServices;
use app\dao\UserDao;
use app\dao\UserSubDao;
use app\dao\RiskDistrictDao;
use app\model\UserSub;
use behavior\FaceTool;
use \behavior\SsjptTool;
use crmeb\services\SwooleTaskService;

class UserServices extends BaseServices
{
    public function __construct(UserDao $dao)
    {
        $this->dao = $dao;
    }

    public function xcmVerifyServices($param, $user_id)
    {
        $city_code = app()->make(RiskDistrictDao::class)->getCityCodeConcat();
        $ssjptTool = new SsjptTool();
        $xcm_res =  $ssjptTool->skxcmjk($param['phone'], $param['sms_code'], $city_code);
        if($xcm_res['status'] == 0) {
            return ['status' => 0, 'msg' => '行程码短信-'. $xcm_res['msg']];
        }else if($xcm_res['status'] == 2) {
            return ['status' => 2, 'msg' => '行程码短信-'. $xcm_res['msg']];
        }
        try {
            $data = ['xcm_result'=> $xcm_res['data']['value'], 'xcm_gettime'=> date('Y-m-d H:i:s')];
            app()->make(UserDao::class)->update(['id'=> $user_id], $data);
            return ['status' => 1, 'msg' => '成功', 'data'=> ['xcm_result' => $xcm_res['data']['value']]];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }

    public function replaceXcmVerifyServices($param, $user)
    {
        if($user['phone'] == $param['phone']) {
            return ['status' => 0, 'msg' => '不能用自己的手机号验证'];
        }
        $city_code = app()->make(RiskDistrictDao::class)->getCityCodeConcat();
        $ssjptTool = new SsjptTool();
        $xcm_res =  $ssjptTool->skxcmjk($param['phone'], $param['sms_code'], $city_code);
        if($xcm_res['status'] == 0) {
            return ['status' => 0, 'msg' => '行程码短信-'. $xcm_res['msg']];
        }else if($xcm_res['status'] == 2) {
            return ['status' => 2, 'msg' => '行程码短信-'. $xcm_res['msg']];
        }
        try {
            $data = ['phone'=> $param['phone'], 'xcm_result'=> $xcm_res['data']['value'], 'xcm_gettime'=> date('Y-m-d H:i:s')];
            app()->make(UserDao::class)->update(['id_card'=> $param['id_card']], $data);
            return ['status' => 1, 'msg' => '成功', 'data'=> ['xcm_result' => $xcm_res['data']['value']]];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }

    public function cleanService($user_id)
    {
        $res = $this->dao->update($user_id, ['openid'=> '', 'csmsb_sign'=> '']);
        if($res) {
            return ['status' => 1, 'msg'=> '成功'];
        }else{
            return ['status' => 0, 'msg'=> '操作失败'];
        }
    }

    // public function checkFrequencyService($param, $user_id)
    // {
    //     $res = $this->dao->update($user_id, ['check_frequency'=> $param['check_frequency']]);
    //     if($res) {
    //         // 重新计算数值
    //         SwooleTaskService::company()->taskType('company')->data(['action'=>'taskCompanyCheckFrequencyCountByUserId','param'=> ['user_id' => $user_id]])->push();
    //         return ['status' => 1, 'msg'=> '成功'];
    //     }else{
    //         return ['status' => 0, 'msg'=> '操作失败'];
    //     }
    // }
    
    public function subInfoReadService($user_id)
    {
        $sub = app()->make(UserSubDao::class)->get(['user_id'=> $user_id]);
        if($sub) {
            return ['status' => 1, 'msg'=> '成功', 'data'=> $sub];
        }else{
            return ['status' => 0, 'msg'=> '不存在'];
        }
    }

    public function subInfoService($param, $user_id)
    {
        $data = [
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
            'arrival_time' => $param['arrival_time'],
            'community_id' => $param['community_id'],
            'community' => $param['community'],
        ];
        
        try {
            $userSubDao = app()->make(UserSubDao::class);
            $sub = $userSubDao->get(['user_id'=> $user_id]);
            if($sub) {
                if(time() - strtotime($sub['update_time']) < 129600) { //36小时修改一次
                    return ['status' => 0, 'msg' => '36小时只能修改一次'];
                }
                $userSubDao->update($sub['id'], $data);
            }else{
                $data['user_id'] = $user_id;
                $userSubDao->save($data);
            }
            return ['status' => 1, 'msg' => '编辑成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '编辑失败-'. $e->getMessage()];
        }
    }

    public function faceCheckService($param, $user_id)
    {
        try {
            $FaceTool = app()->make(FaceTool::class);
            $res = $FaceTool->faceCheck($param['real_name'],$param['id_card'],$param['face_img']);
            if($res['status']) {
                $is_true = ( isset($res['data']['compStatus']) && $res['data']['compStatus'] == 1 )? 1 : 0;
                if($is_true){
                    $this->dao->update($user_id,['face_time'=>time()]);
                    return ['status' => 1, 'msg' => '比对成功'];
                }else{
                    return ['status' => 0, 'msg' => '比对失败'];
                }
            }else{
                return $res;
            }
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '失败-'. $e->getMessage()];
        }
    }


}
