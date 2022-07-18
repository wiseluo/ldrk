<?php

namespace app\controller\applet;

use think\facade\App;
use crmeb\basic\BaseController;
use app\services\applet\LoginServices;
use app\validate\applet\LoginValidate;

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
    public function login()
    {
        $param = $this->request->param();
        $validate = new LoginValidate();
        if (!$validate->scene('login')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->service->loginService($param);
        if ($res['status'] == 1) {
            return show(200, '成功', $res['data']);
        } else if ($res['status'] == 2) {
            return show(4003, $res['msg'], $res['data']);
        } else {
            return show(400, $res['msg']);
        }
    }


    // public function test()
    // {
    //     $uid = $this->request->get('uid');

    //     $res = $this->service->test($uid);
    //     return show(200, '成功', $res);
    // }

    // public function authphone()
    // {
    //     $param = $this->request->param();
    //     $validate = new LoginValidate();
    //     if(!$validate->scene('authphone')->check($param)) {
    //         return show(400, $validate->getError());
    //     }
    //     $res = $this->service->autoRegisterService($param, 'authphone');
    //     if($res['status'] == 1) {
    //         return show(200, '成功', $res['data']);
    //     }else if($res['status'] == 2) {
    //         return show(4003, $res['msg'], $res['data']);
    //     }else{
    //         return show(400, $res['msg']);
    //     }
    // }

    // public function encryptedPhone()
    // {
    //     $param = $this->request->param();
    //     $validate = new LoginValidate();
    //     if(!$validate->scene('encryptedPhone')->check($param)) {
    //         return show(400, $validate->getError());
    //     }
    //     $res = $this->service->autoRegisterService($param, 'encryptedphone');
    //     if($res['status'] == 1) {
    //         return show(200, '成功', $res['data']);
    //     }else if($res['status'] == 2) {
    //         return show(4003, $res['msg'], $res['data']);
    //     }else{
    //         return show(400, $res['msg']);
    //     }
    // }

    public function authphoneV2()
    {
        $param = $this->request->param();
        $validate = new LoginValidate();
        if (!$validate->scene('authphone')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->service->autoRegisterV2Service($param, 'authphone');
        if ($res['status'] == 1) {
            return show(200, '成功', $res['data']);
        } else if ($res['status'] == 2) {
            return show(4003, $res['msg'], $res['data']);
        } else if ($res['status'] == 3) {
            return show(4005, $res['msg'], $res['data']);
        } else {
            return show(400, $res['msg']);
        }
    }

    public function encryptedPhoneV2()
    {
        $param = $this->request->param();
        $validate = new LoginValidate();
        if (!$validate->scene('encryptedPhone')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->service->autoRegisterV2Service($param, 'encryptedphone');
        if ($res['status'] == 1) {
            return show(200, '成功', $res['data']);
        } else if ($res['status'] == 2) {
            return show(4003, $res['msg'], $res['data']);
        } else if ($res['status'] == 3) {
            return show(4005, $res['msg'], $res['data']);
        } else {
            return show(400, $res['msg']);
        }
    }

    //小程序注册
    public function register()
    {
        $param = $this->request->param();
        $validate = new LoginValidate();
        if (!$validate->scene('register')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->service->registerService($param);
        if ($res['status']) {
            return show(200, '成功', $res['data']);
        } else {
            return show(400, $res['msg']);
        }
    }

    //身份证识别注册
    public function registerIdcardRecogn()
    {
        $param = $this->request->param();
        $validate = new LoginValidate();
        if (!$validate->scene('registerIdcardRecogn')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->service->registerIdcardRecognService($param);
        if ($res['status']) {
            return show(200, '成功', $res['data']);
        } else {
            return show(400, $res['msg']);
        }
    }
}
