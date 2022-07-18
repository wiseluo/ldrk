<?php

namespace app\controller\user;

use think\facade\App;
use crmeb\basic\BaseController;
use app\validate\user\PlaceDeclareValidate;
use app\services\user\PlaceDeclareServices;

class PlaceDeclare extends BaseController
{
    public function __construct(App $app, PlaceDeclareServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    //场所码申报
    public function placeDeclare()
    {
        $param = $this->request->param();
        $validate = new PlaceDeclareValidate();
        if(!$validate->scene('placeDeclare')->check($param)) {
            return show(400, $validate->getError());
        }

        $res = $this->services->placeDeclareService($param);
        if($res['status']) {
            return show(200, $res['msg'], $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }
    
    public function result()
    {
        $param = $this->request->param();
        $validate = new PlaceDeclareValidate();
        if(!$validate->scene('result')->check($param)) {
            return show(400, $validate->getError());
        }

        $res = $this->services->resultService($param);
        if($res['status'] == 1) {
            return show(200, $res['msg'], $res['data']);
        }else if($res['status'] == 2) {
            return show(4002, $res['msg']);
        }else{
            return show(400, $res['msg']);
        }
    }

    public function detail()
    {
        $param = $this->request->param();
        $validate = new PlaceDeclareValidate();
        if(!$validate->scene('detail')->check($param)) {
            return show(400, $validate->getError());
        }

        $res = $this->services->detailService($param['sign']);
        if($res['status'] == 1) {
            return show(200, $res['msg'], $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }

    public function scanCode()
    {
        $param = $this->request->param();
        $validate = new PlaceDeclareValidate();
        if(!$validate->scene('scanCode')->check($param)) {
            return show(400, $validate->getError());
        }

        $res = $this->services->scanCodeService($param);
        if($res['status'] == 1) {
            return show(200, $res['msg'], $res['data']);
        }else if($res['status'] == 2) {
            return show(4002, $res['msg']);
        }else{
            return show(400, $res['msg']);
        }
    }

    public function placeRead($code)
    {
        $res = $this->services->placeReadService($code);
        if($res['status']) {
            return show(200, $res['msg'], $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }
}
