<?php

namespace app\controller\user;

use crmeb\basic\BaseController;

/**
 * 基类 所有控制器继承的类
 * Class AuthController
 * @package app\controller\admin
 */
class AuthController extends BaseController
{
    /**
     * 当前登陆管理员信息
     * @var
     */
    protected $userInfo;

    /**
     * 当前登陆用户ID
     * @var
     */
    protected $userId;

    /**
     * 当前用户权限
     * @var array
     */
    protected $auth = [];


    /**
     * 初始化
     */
    protected function initialize()
    {
        $this->userInfo = $this->request->userInfo();
    }

}
