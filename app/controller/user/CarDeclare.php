<?php

namespace app\controller\user;

use think\facade\App;
use crmeb\basic\BaseController;
use app\validate\user\CarDeclareValidate;
use app\services\user\CarDeclareServices;
use \behavior\SmsVerifyTool;
use think\facade\Config;
use crmeb\services\SwooleTaskService;

class CarDeclare extends BaseController
{
    public function __construct(App $app, CarDeclareServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    // 货车申报
    public function post()
    {
        $param = $this->request->param();
        $validate = new CarDeclareValidate();
        if(!$validate->scene('post')->check($param)) {
            return show(400, $validate->getError());
        }

        if(Config::get('app.app_host') != 'dev') { //测试环境不验证
            $smsVerifyTool = new SmsVerifyTool();
            $sms_res = $smsVerifyTool->verifySmsCode('declare_verify', $param['phone'], $param['sms_code']);
            if($sms_res['status'] == 0) {
                return show(40001, $sms_res['msg']);
            }
        }
        $param['id_card'] = strtoupper($this->request->param('id_card', ''));
        $res = $this->services->postService($param);
        if($res['status']) {
            $param['id'] = $res['data']['id'];
            SwooleTaskService::user()->taskType('carDeclare')->data(['action'=>'getTravelFromApi','param'=>$param])->push();
            return show(200, $res['msg'], $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }


    public function getTravel(){
        $param = $this->request->param();
        $id  = (int)$param['id'];
        $res = $this->services->getTravelService($id);
        if($res['status']) {
            return show(200, $res['msg'], $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }
    
}
