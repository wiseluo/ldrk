<?php

namespace app\controller\applet;

use think\facade\App;
use crmeb\basic\BaseController;
use app\validate\applet\CarDeclareValidate;
use app\services\applet\CarDeclareServices;
use crmeb\services\SwooleTaskService;

class CarDeclare extends BaseController
{
    public function __construct(App $app, CarDeclareServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    // 货车申报提交
    public function post()
    {
        $param = $this->request->param();
        $validate = new CarDeclareValidate();
        if(!$validate->scene('post')->check($param)) {
            return show(400, $validate->getError());
        }
        $param['id_card'] = strtoupper($this->request->param('id_card', ''));
        $res = $this->services->postService($param);
        if($res['status']) {
            $param['id'] = $res['data']['id'];
            SwooleTaskService::user()->taskType('carDeclare')->data(['action'=>'getTravelFromApi','param'=>$param])->push();
            return show(200, $res['msg'], ['sign'=>$res['data']['sign']]);
        }else{
            return show(400, $res['msg']);
        }
    }

    // 获取申报结果通过sign
    public function getTravelBySign(){
        $param = $this->request->param();
        $sign  = (string)$param['sign'];
        $res = $this->services->getTravelBySignService($sign);
        if($res['status']) {
            return show(200, $res['msg'], $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }
    
}
