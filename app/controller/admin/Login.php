<?php
namespace app\controller\admin;

use think\facade\App;
use crmeb\utils\Captcha;
use crmeb\basic\BaseController;
use app\services\admin\system\admin\SystemAdminServices;
use app\validate\admin\setting\SystemAdminValidate;
use \behavior\SmsVerifyTool;
use think\facade\Config;
/**
 * 后台登陆
 * Class Login
 * @package app\controller\admin
 */
class Login extends BaseController
{

    /**
     * Login constructor.
     * @param App $app
     * @param SystemAdminServices $services
     */
    public function __construct(App $app, SystemAdminServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    protected function initialize()
    {
        // TODO: Implement initialize() method.
    }

    /**
     * 验证码
     * @return $this|\think\Response
     */
    public function captcha()
    {
        return app()->make(Captcha::class)->create();
    }

    /**
     * 登陆
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function login()
    {
        $param = $this->request->param();
        $validate = new SystemAdminValidate();
        if(!$validate->scene('login')->check($param)) {
            return $this->fail($validate->getError());
        }
        // if (!app()->make(Captcha::class)->check($param['imgcode'])) {
        //     return $this->fail('验证码错误，请重新输入');
        // }
        return $this->success($this->services->loginByAccount($param['account'], $param['pwd']));
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
        $validate = new SystemAdminValidate();
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
