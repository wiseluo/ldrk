<?php

namespace app\services\admin;

use app\services\admin\BaseServices;
use app\dao\UserDao;
use app\dao\UserManagerDao;

class UserManagerServices extends BaseServices
{
    public function __construct(UserManagerDao $dao)
    {
        $this->dao = $dao;
    }

    public function getListService($param)
    {
        return $this->dao->getList($param);
    }

    public function readService($id)
    {
        $manager = $this->dao->get($id);
        if($manager) {
            return ['status' => 1, 'msg'=> '成功', 'data'=> $manager];
        }else{
            return ['status' => 0, 'msg'=> '不存在'];
        }
    }

    public function saveService($param)
    {
        $user = app()->make(UserDao::class)->get($param['id']);
        if($user == null) {
            return ['status' => 0, 'msg'=> '该人员不存在'];
        }
        $data = [
            'user_id' => $user['id'],
            'real_name' => $user['real_name'],
            'id_card' => $user['id_card'],
            'phone' => $user['phone'],
            'yw_street_id' => $param['yw_street_id'],
            'yw_street' => $param['yw_street'],
        ];
        try {
            $this->dao->save($data);
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }

    public function updateService($param, $id)
    {
        $manager = $this->dao->get($id);
        if($manager == null) {
            return ['status' => 0, 'msg'=> '该记录不存在'];
        }
        $data = [
            'yw_street_id' => $param['yw_street_id'],
            'yw_street' => $param['yw_street'],
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
        $manager = $this->dao->get($id);
        if($manager == null) {
            return ['status' => 0, 'msg' => '该记录不存在'];
        }
        try {
            $this->dao->delete($id);
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }
}
