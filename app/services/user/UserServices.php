<?php

namespace app\services\user;

use app\services\user\BaseServices;
use crmeb\exceptions\UserException;
use \behavior\IdentityCardTool;
use app\dao\UserDao;

class UserServices extends BaseServices
{
    public function __construct(UserDao $dao)
    {
        $this->dao = $dao;
    }

    public function verifyLogin($userInfo)
    {
        if ($userInfo->status != 1) {
            throw new UserException('该账号未审核通过!', 400);
        }
        $userInfo->last_ip = app('request')->ip();
        $userInfo->last_time = time();
        $userInfo->save();

        return $userInfo;
    }

    public function login($userInfo)
    {
        $tokenInfo = $this->createToken($userInfo->id, 'user');
        return [
            'token' => $tokenInfo['token'],
            'expires_time' => $tokenInfo['params']['exp'],
            'user_info' => [
                'id' => $userInfo->getData('id'),
                'phone' => $userInfo->getData('phone'),
                'real_name' => $userInfo->getData('real_name'),
                'user_type'=>$userInfo->getData('user_type'),
            ],
        ];
    }
    public function loginforsms($userInfo)
    {
        $tokenInfo = $this->createToken($userInfo->id, 'user');
        return [
            'token' => $tokenInfo['token'],
            'expires_time' => $tokenInfo['params']['exp'],
            'user_info' => [
                'id' => $userInfo->getData('id'),
                'phone' => $userInfo->getData('phone'),
                'real_name' => $userInfo->getData('real_name'),
                'user_type'=>$userInfo->getData('user_type'),
            ],
        ];
    }

    public function smslogin($phone)
    {
        $user = $this->dao->getUser(0,[],['phone'=> $phone]);
        if (!$user) {
            throw new UserException('账号不存在!', 400);
        }
        $userInfo = $this->verifyLogin($user);
        return $this->loginforsms($userInfo);
    }

    public function loginByAccount($phone, $password)
    {
        $user = $this->dao->get(['phone'=> $phone]);
        if (!$user) {
            throw new UserException('账号不存在!', 400);
        }
        if (!password_verify($password, $user->pwd)) {
            throw new UserException('账号或密码错误，请重新输入', 400);
        }
        $userInfo = $this->verifyLogin($user);
        return $this->login($userInfo);
    }

    public function getListService($param)
    {
        return $this->dao->getCompanyUserList($param);
    }

    public function readService($id)
    {
        $user = $this->dao->get($id);
        if($user) {
            return ['status' => 1, 'msg'=> '成功', 'data'=> $user];
        }else{
            return ['status' => 0, 'msg'=> '用户不存在或无权限查看'];
        }
    }

    public function getWithTrashedService($id)
    {
        return $this->dao->getWithTrashed($id);
    }
    
    public function updateUserByDeclareService($param)
    {
        $user = $this->dao->get(['id_card' => $param['id_card']]);
        if($user) {
            $user_data = [
                'phone' => $param['phone'],
                'declare_type' => $param['declare_type'],
                'status' => 1,
            ];
            if($param['declare_type'] == 'leave') {
                $user_data['position_type'] = 'leave';
                $user_data['expect_return_time'] = $param['expect_return_time'];
            }else{
                $user_data['position_type'] = 'stay';
            }
            //修改状态
            if($param['card_type'] == 'id' && $user['id_verify_result'] != 1 && $param['id_verify_result'] == 1) { //如果是id身份证且用户未验证通过且本次认证通过，更新身份类型
                $user_data['card_type'] = 'id';
                $user_data['gender'] = IdentityCardTool::getSex($param['id_card']);
                $user_data['birthday'] = IdentityCardTool::getBirthday($param['id_card']);
                $user_data['real_name'] = $param['real_name'];
                $user_data['nation'] = $param['nation'];
                $user_data['permanent_address'] = $param['permanent_address'];
                $user_data['id_verify_result'] = 1;
            }
            if($param['declare_type'] == 'leave') {
                $user_data['expect_return_time'] = $param['expect_return_time'];
                $user_data['sms_send'] = 0;
            }
            $this->dao->update($user['id'], $user_data);
            return ['status' => 1, 'msg' => '修改用户成功', 'data'=> $user['id']];
        }else{
            $user_data = [
                'phone' => $param['phone'],
                'real_name' => $param['real_name'],
                'id_card' => $param['id_card'],
                'card_type' => $param['card_type'],
                'declare_type' => $param['declare_type'],
                'uniqid' => randomCode(12),
                'status' => 1,
            ];
            if($param['declare_type'] == 'leave') {
                $user_data['position_type'] = 'leave';
                $user_data['expect_return_time'] = $param['expect_return_time'];
            }else{
                $user_data['position_type'] = 'stay';
            }
            if($param['card_type'] == 'id') {
                $user_data['gender'] = IdentityCardTool::getSex($param['id_card']);
                $user_data['birthday'] = IdentityCardTool::getBirthday($param['id_card']);
                $user_data['real_name'] = $param['real_name'];
                $user_data['nation'] = $param['nation'];
                $user_data['permanent_address'] = $param['permanent_address'];
                $user_data['id_verify_result'] = 1;
            }
            if($param['declare_type'] == 'leave') {
                $user_data['expect_return_time'] = $param['expect_return_time'];
                $user_data['sms_send'] = 0;
            }
            $user =$this->dao->save($user_data);
            return ['status' => 1, 'msg' => '用户新增成功', 'data'=> $user->id];
        }
    }

    public function updateService(array $param, $id)
    {
        $user = $this->dao->get($id);
        if($user == null) {
            return ['status' => 0, 'msg' => '账号不存在'];
        }
        $data = [
            'phone' => $param['phone'],
            'address' => $param['address'],
        ];

        try {
            $this->dao->update($id, $data);
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }

    public function deleteService($id)
    {
        $user = $this->dao->get($id);
        if($user == null) {
            return ['status' => 0, 'msg' => '用户不存在'];
        }
        try {
            $this->dao->softDelete($id);
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }
    

}
