<?php

namespace app\controller\ainat;

use think\facade\App;
use crmeb\basic\BaseController;
use app\validate\ainat\SmsValidate;
use \behavior\SmsVerifyTool;
use think\facade\Config;

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

}
