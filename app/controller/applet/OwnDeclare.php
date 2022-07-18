<?php

namespace app\controller\applet;

use think\facade\App;
use crmeb\basic\BaseController;
use app\validate\applet\OwnDeclareValidate;
use app\services\applet\OwnDeclareServices;

class OwnDeclare extends BaseController
{
    public function __construct(App $app, OwnDeclareServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    //自主申报
    public function ownDeclareLeave()
    {
        $param = $this->request->param();
        $validate = new OwnDeclareValidate();
        if(!$validate->scene('leaveDeclare')->check($param)) {
            return show(400, $validate->getError());
        }
        // 台湾，香港，澳门的特殊处理
        $res_check = $this->_checkTaiwanXiangganAomen($param);
        if($res_check['status'] == 0){
            return show(400, $res_check['msg']);
        }

        $param['street_id'] = $this->request->param('street_id', 0);
        $param['street'] = $this->request->param('street', '');
        $res = $this->services->ownDeclareLeaveService($param, $this->request->tokenUser());
        if($res['status']) {
            return show(200, $res['msg'], $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }
    
    public function ownDeclareCome()
    {
        $param = $this->request->param();
        $validate = new OwnDeclareValidate();
        if(!$validate->scene('comeDeclare')->check($param)) {
            return show(400, $validate->getError());
        }
        // 台湾，香港，澳门的特殊处理
        $res_check = $this->_checkTaiwanXiangganAomen($param);
        if($res_check['status'] == 0){
            return show(400, $res_check['msg']);
        }
        
        $param['street_id'] = $this->request->param('street_id', 0);
        $param['street'] = $this->request->param('street', '');
        $res = $this->services->ownDeclareComeService($param, $this->request->tokenUser());
        if($res['status']) {
            return show(200, $res['msg'], $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }
    
    public function ownDeclareRiskarea()
    {
        $param = $this->request->param();
        $validate = new OwnDeclareValidate();
        if(!$validate->scene('riskareaDeclare')->check($param)) {
            return show(400, $validate->getError());
        }
        // 台湾，香港，澳门的特殊处理
        $res_check = $this->_checkTaiwanXiangganAomen($param);
        if($res_check['status'] == 0){
            return show(400, $res_check['msg']);
        }
        
        $param['street_id'] = $this->request->param('street_id', 0);
        $param['street'] = $this->request->param('street', '');
        $res = $this->services->ownDeclareRiskareaService($param, $this->request->tokenUser());
        if($res['status']) {
            return show(200, $res['msg'], $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }

    public function ownDeclareBarrier()
    {
        $param = $this->request->param();
        $validate = new OwnDeclareValidate();
        if(!$validate->scene('barrierDeclare')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->services->ownDeclareBarrierService($param, $this->request->tokenUser());
        if($res['status']) {
            return show(200, $res['msg'], $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }

    public function ownDeclareQuarantine()
    {
        $param = $this->request->param();
        $validate = new OwnDeclareValidate();
        if(!$validate->scene('quarantine')->check($param)) {
            return show(400, $validate->getError());
        }
        $param['street_id'] = $this->request->param('street_id', 0);
        $param['street'] = $this->request->param('street', '');
        $res = $this->services->ownDeclareQuarantineService($param, $this->request->tokenUser());
        if($res['status']) {
            return show(200, $res['msg'], $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }

    public function lastLeave()
    {
        $res = $this->services->lastLeaveService($this->request->tokenUser());
        return show(200, '成功', $res);
    }

    public function resultWhole()
    {
        $param = $this->request->param();
        $validate = new OwnDeclareValidate();
        if(!$validate->scene('resultWhole')->check($param)) {
            return show(400, $validate->getError());
        }

        $res = $this->services->resultWholeService($param, $this->request->userInfo());
        if($res['status'] == 1) {
            return show(200, $res['msg'], $res['data']);
        }else if($res['status'] == 2) {
            return show(4002, $res['msg']);
        }else{
            return show(400, $res['msg']);
        }
    }

    private function _checkTaiwanXiangganAomen($param){
        if($param['province_id'] == 24){
            // 台湾目前只有省
            return ['status'=>1, 'msg'=>'pass'];
        }else if($param['province_id'] == 26 || $param['province_id'] == 27){
            // 香港和澳门只判到区
            if( $param['city_id'] > 0){
                return ['status'=>1, 'msg'=>'pass'];
            }else{
                return ['status'=>0,'msg'=>'请选择到区'];
            }
        }else{
            if( $param['county_id'] > 0){
                return ['status'=>1, 'msg'=>'pass'];
            }else{
                return ['status'=>0,'msg'=>'省市县必填选择完整'];
            }
        }
    }

}
