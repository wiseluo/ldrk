<?php
namespace app\controller\ainat;

use think\facade\App;
use crmeb\basic\BaseController;
use app\services\ainat\AdminServices;
use app\validate\ainat\AdminValidate;
use \behavior\SmsVerifyTool;
use think\facade\Config;

/**
 * 后台登陆
 * Class Login
 * @package app\controller\ainat
 */
class Login extends BaseController
{

    /**
     * Login constructor.
     * @param App $app
     * @param SystemAdminServices $services
     */
    public function __construct(App $app, AdminServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    protected function initialize()
    {
        // TODO: Implement initialize() method.
    }

    /**
     * 获取后台登录页轮播图以及LOGO
     * @return mixed
     */
    public function info()
    {
        return $this->success($this->services->getLoginInfo());
    }

    public function smslogin()
    {
        $param = $this->request->param();
        $validate = new AdminValidate();
        if(!$validate->scene('smslogin')->check($param)) {
            return $this->fail($validate->getError());
        }
        if(Config::get('app.app_host') == 'dev') { //测试环境不验证
            $sms_res = ['status'=> 1, 'msg'=> '成功'];
        }else{
            $smsVerifyTool = new SmsVerifyTool();
            $sms_res = $smsVerifyTool->verifySmsCode('smslogin', $param['phone'], $param['smscode']);
        }
        
        if($sms_res['status'] == 0) {
            return $this->fail($sms_res['msg']);
        }
        return $this->success($this->services->smslogin($param['phone']) );
    }

}
