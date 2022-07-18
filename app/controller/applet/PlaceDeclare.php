<?php

namespace app\controller\applet;

use think\facade\App;
use crmeb\basic\BaseController;
use app\validate\applet\PlaceDeclareValidate;
use app\services\applet\PlaceDeclareServices;

class PlaceDeclare extends BaseController
{
    public function __construct(App $app, PlaceDeclareServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
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

    public function resultWhole()
    {
        $param = $this->request->param();
        $validate = new PlaceDeclareValidate();
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

    public function scanCode()
    {
        $param = $this->request->param();
        $validate = new PlaceDeclareValidate();
        if(!$validate->scene('scanCode')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->services->scanCodeService($param, $this->request->userInfo());
        if($res['status'] == 2) {
            return show(4008, $res['msg']);
        }
        if($res['status'] == 1) {
            return show(200, $res['msg'], $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }

    public function replaceScan()
    {
        $param = $this->request->param();
        $validate = new PlaceDeclareValidate();
        if(!$validate->scene('replaceScan')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->services->replaceScanService($param);
        if($res['status'] == 1) {
            return show(200, $res['msg'], $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }

    public function testScanCode()
    {
        $param = $this->request->param();
        // $validate = new PlaceDeclareValidate();
        // if(!$validate->scene('scanCode')->check($param)) {
        //     return show(400, $validate->getError());
        // }
        if( (string)$this->request->param('place_code') == ''){
            return show(400, '场所码错误');
        }
        $for_test = 1;
        $userDao = app()->make(\app\dao\UserAuthDao::class);

        $id = rand(10000,30000);
        $userInfo = $userDao->get($id);
        if($userInfo){
            $res = $this->services->scanCodeService($param, $userInfo,$for_test);
        }else{
            return show(400, 'user_id 不存在');
        }
        if($res['status'] == 1) {
            return show(200, $res['msg'], ['id'=>$res['data']['id']]);
        }else if($res['status'] == 2) {
            return show(4002, $res['msg']);
        }else{
            return show(400, $res['msg']);
        }
    }

    public function xcmResult()
    {
        $param = $this->request->param();
        $validate = new PlaceDeclareValidate();
        if(!$validate->scene('xcmResult')->check($param)) {
            return show(400, $validate->getError());
        }

        $res = $this->services->xcmResultService($param,$this->request->userInfo());
        if($res['status'] == 1) {
            return show(200, $res['msg'], $res['data']);
        }else if($res['status'] == 2) {
            return show(4005, $res['msg']);
        }else{
            return show(400, $res['msg'] .'，请关闭该窗口,继续使用场所码');
        }
    }
    public function resultForQrcode()
    {
        $param = $this->request->param();
        $validate = new PlaceDeclareValidate();
        if(!$validate->scene('resultForQrcode')->check($param)) {
            return show(400, $validate->getError());
        }

        $res = $this->services->xcmResultForQrcodeService($param);
        if($res['status'] == 1) {
            return show(200, $res['msg'], $res['data']);
        }else if($res['status'] == 2) {
            return show(4005, $res['msg']);
        }else{
            return show(400, $res['msg'] .'，请关闭该窗口,继续使用场所码');
        }
    }
    
    public function ryxxResult()
    {
        $param = $this->request->param();
        $validate = new PlaceDeclareValidate();
        if(!$validate->scene('ryxxResult')->check($param)) {
            return show(400, $validate->getError());
        }

        $res = $this->services->ryxxResultService($param, $this->request->userInfo());
        if($res['status'] == 1) {
            return show(200, $res['msg'], $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }

    public function myQrcodeResult(){
        $res = $this->services->myQrcodeResultServiceV2($this->request->userInfo());
        if($res['status'] == 1) {
            return show(200, $res['msg'], $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }

    public function hsjcResultLog()
    {
        $param = $this->request->param();
        // $validate = new PlaceDeclareValidate();
        // if(!$validate->scene('hsjcResultLog')->check($param)) {
        //     return show(400, $validate->getError());
        // }
        $param['id_card'] = $this->request->param('id_card', '');
        $res = $this->services->hsjcResultLogService($param, $this->request->tokenUser());
        if($res['status'] == 1) {
            return show(200, $res['msg'], $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }
    
    public function reverseScan()
    {
        $param = $this->request->param();
        $validate = new PlaceDeclareValidate();
        if(!$validate->scene('reverseScan')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->services->reverseScanService($param, $this->request->tokenUser()['id']);
        if($res['status'] == 1) {
            return show(200, $res['msg'], $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }

}
