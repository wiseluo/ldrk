<?php

namespace app\controller\applet;

use think\facade\App;
use crmeb\basic\BaseController;
use app\validate\applet\SmsValidate;
use \behavior\SmsVerifyTool;
use think\facade\Config;
use \behavior\SsjptTool;

class Sms extends BaseController
{
    public function __construct(App $app)
    {
        parent::__construct($app);
    }

    public function smsCode()
    {
        $param = $this->request->param();
        $validate = new SmsValidate();
        if(!$validate->scene('smsCode')->check($param)) {
            return show(400, $validate->getError());
        }
        // if(Config::get('app.app_host') == 'dev') { //测试环境
        //     return show(200, '测试不真实发送');
        // }
        $smsVerifyTool = new SmsVerifyTool();
        $res = $smsVerifyTool->sendSmsCode($param['prefix'], $param['phone']);
        if($res['status']) {
            return show(200, $res['msg']);
        }else{
            return show(400, $res['msg']);
        }
    }

    public function xcmdx()
    {
        $param = $this->request->param();
        // 用户的行程码有效期是否还有12小时
        // $user = $this->request->userInfo();
        // if($user['phone'] != $param['phone']){
        //     return show(400, '不能冒用别人的手机');
        // }
        // if( time() - strtotime($user['xcm_gettime']) < 3600 ){  // 43200
        //     return show(400, '满1小时后可重新校验,请稍后再试');
        // }
        $validate = new SmsValidate();
        if(!$validate->scene('xcmdx')->check($param)) {
            return show(400, $validate->getError());
        }
        // if(Config::get('app.app_host') == 'dev') { //测试环境
        //     return show(200, '测试不真实发送');
        // }
        $ssjptTool = new SsjptTool();
        $res =  $ssjptTool->skxcmdxjk($param['phone']);
        if($res['status'] == 1) {
            return show(200, $res['msg'], $res['data']);
        }else if($res['status'] == 2) {
            return show(4005, $res['msg']);
        }else{
            return show(400, $res['msg'] .'，请关闭该窗口,继续使用场所码');
        }
    }
}
