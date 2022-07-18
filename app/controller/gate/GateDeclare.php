<?php

namespace app\controller\gate;

use think\facade\App;
use crmeb\basic\BaseController;
use app\validate\gate\GateDeclareValidate;
use app\services\gate\GateDeclareServices;

class GateDeclare extends BaseController
{
    public function __construct(App $app, GateDeclareServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    public function addHistory(){
        $param = $this->request->param();
        $validate = new GateDeclareValidate();
        if(!$validate->scene('addHistory')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->services->addHistoryService($param);
        if($res['status'] == 1) {
            return show(200, $res['msg'], $res['data']);
        }else if($res['status'] == 2) {
            return show(4002, $res['msg']);
        }else{
            return show(400, $res['msg']);
        }
    }


    // public function result()
    // {
    //     $param = $this->request->param();
    //     $validate = new PlaceDeclareValidate();
    //     if(!$validate->scene('result')->check($param)) {
    //         return show(400, $validate->getError());
    //     }

    //     $res = $this->services->resultService($param);
    //     if($res['status'] == 1) {
    //         return show(200, $res['msg'], $res['data']);
    //     }else if($res['status'] == 2) {
    //         return show(4002, $res['msg']);
    //     }else{
    //         return show(400, $res['msg']);
    //     }
    // }

    public function resultWhole()
    {
        $param = $this->request->param();
        $validate = new GateDeclareValidate();
        if(!$validate->scene('resultWhole')->check($param)) {
            return show(400, $validate->getError());
        }

        $res = $this->services->resultWholeService($param);
        if($res['status'] == 1) {
            return show(200, $res['msg'], $res['data']);
        }else if($res['status'] == 2) {
            return show(4002, $res['msg']);
        }else{
            return show(400, $res['msg']);
        }
    }

    // 根据身份证获取人员管控信息
    public function ryxx(){
        $param = $this->request->param();
        if(!isset($param['id_card'])){
            return show(400, 'id_card参数必传');
        }
        $res = $this->services->ryxxService($param);
        if($res['status'] == 1) {
            return show(200, $res['msg'], $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }

}
