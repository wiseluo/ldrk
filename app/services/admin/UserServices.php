<?php

namespace app\services\admin;

use app\services\admin\BaseServices;
use app\dao\UserDao;
use app\dao\UserSubDao;

class UserServices extends BaseServices
{
    public function __construct(UserDao $dao)
    {
        $this->dao = $dao;
    }

    public function getListService($param)
    {
        return $this->dao->getUserList($param);
    }

    public function readService($id, $admin=[])
    {
        $user = $this->dao->getUser($id, $admin);
        if($user) {
            return ['status' => 1, 'msg'=> '成功', 'data'=> $user];
        }else{
            return ['status' => 0, 'msg'=> '不存在'];
        }
    }

    public function getSubChangeListService($param)
    {
        return app()->make(UserSubDao::class)->getSubChangeList($param);
    }

}
