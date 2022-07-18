<?php

namespace app\controller\user;

use think\facade\App;
use crmeb\basic\BaseController;
use app\services\user\LoginServices;
use app\validate\user\LoginValidate;

/**
 * 登陆
 * Class Login
 * @package app\controller\supplier
 */
class Login extends BaseController
{
    public function __construct(App $app, LoginServices $service)
    {
        parent::__construct($app);
        $this->service = $service;
    }

    protected function initialize()
    {
        // TODO: Implement initialize() method.
    }

    //小程序登录
    public function appletLogin()
    {
        $param = $this->request->param();
        $validate = new LoginValidate();
        if(!$validate->scene('appletLogin')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->service->appletLoginService($param);
        if($res['status']) {
            return show(200, '成功', $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }

}
