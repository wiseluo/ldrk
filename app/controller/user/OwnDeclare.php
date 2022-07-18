<?php

namespace app\controller\user;

use think\facade\App;
use crmeb\basic\BaseController;
use app\validate\user\OwnDeclareValidate;
use app\services\user\OwnDeclareServices;
use \behavior\SmsVerifyTool;
use think\facade\Config;
use app\services\SgzxServices;
use behavior\SsjptActualTool;
use app\services\FysjServices;

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
            return show(40001, $res_check['msg']);
        }

        if(Config::get('app.app_host') != 'dev') { //测试环境不验证
            $smsVerifyTool = new SmsVerifyTool();
            $sms_res = $smsVerifyTool->verifySmsCode('declare_verify', $param['phone'], $param['sms_code']);
            if($sms_res['status'] == 0) {
                return show(40001, $sms_res['msg']);
            }
        }
        $param['id_card'] = strtoupper(trim($this->request->param('id_card', '')));
        $param['street_id'] = $this->request->param('street_id', 0);
        $param['street'] = $this->request->param('street', '');
        $res = $this->services->ownDeclareLeaveService($param);
        if($res['status']) {
            return show(200, $res['msg']);
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
            return show(40001, $res_check['msg']);
        }
        
        if(Config::get('app.app_host') != 'dev') { //测试环境不验证
            $smsVerifyTool = new SmsVerifyTool();
            $sms_res = $smsVerifyTool->verifySmsCode('declare_verify', $param['phone'], $param['sms_code']);
            if($sms_res['status'] == 0) {
                return show(40001, $sms_res['msg']);
            }
        }
        $param['id_card'] = strtoupper(trim($this->request->param('id_card', '')));
        $param['street_id'] = $this->request->param('street_id', 0);
        $param['street'] = $this->request->param('street', '');
        $res = $this->services->ownDeclareComeService($param);

        $return_data = [];
        $return_data = $this->_getMoreInfo($param,'come');
        
        if($res['status']) {
            return show(200, $res['msg'],$return_data);
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
            return show(40001, $res_check['msg']);
        }
        
        if(Config::get('app.app_host') != 'dev') { //测试环境不验证
            $smsVerifyTool = new SmsVerifyTool();
            $sms_res = $smsVerifyTool->verifySmsCode('declare_verify', $param['phone'], $param['sms_code']);
            if($sms_res['status'] == 0) {
                return show(40001, $sms_res['msg']);
            }
        }
        $param['id_card'] = strtoupper(trim($this->request->param('id_card', '')));
        $param['street_id'] = $this->request->param('street_id', 0);
        $param['street'] = $this->request->param('street', '');
        $res = $this->services->ownDeclareRiskareaService($param);

        $return_data = [];
        $return_data = $this->_getMoreInfo($param,'riskarea');

        if($res['status']) {
            return show(200, $res['msg'],$return_data);
        }else{
            return show(400, $res['msg']);
        }
    }

    public function ownDeclareBarrier()
    {
        $param = $this->request->param();
        $validate = new OwnDeclareValidate();
        if(!$validate->scene('barrierDeclare')->check($param)) {
            return show(40001, $validate->getError());
        }
        
        if(Config::get('app.app_host') != 'dev') { //测试环境不验证
            $smsVerifyTool = new SmsVerifyTool();
            $sms_res = $smsVerifyTool->verifySmsCode('declare_verify', $param['phone'], $param['sms_code']);
            if($sms_res['status'] == 0) {
                return show(40001, $sms_res['msg']);
            }
        }
        $param['id_card'] = strtoupper(trim($this->request->param('id_card', '')));
        $res = $this->services->ownDeclareBarrierService($param);
        
        $return_data = [];
        $return_data = $this->_getMoreInfo($param,'barrier');
        
        if($res['status']) {
            return show(200, $res['msg'],$return_data);
        }else{
            return show(400, $res['msg']);
        }
    }

    public function ownDeclareQuarantine()
    {
        $param = $this->request->param();
        $validate = new OwnDeclareValidate();
        if(!$validate->scene('quarantine')->check($param)) {
            return show(40001, $validate->getError());
        }
        
        if(Config::get('app.app_host') != 'dev') { //测试环境不验证
            $smsVerifyTool = new SmsVerifyTool();
            $sms_res = $smsVerifyTool->verifySmsCode('declare_verify', $param['phone'], $param['sms_code']);
            if($sms_res['status'] == 0) {
                return show(40001, $sms_res['msg']);
            }
        }
        $param['id_card'] = strtoupper(trim($this->request->param('id_card', '')));
        $param['street_id'] = $this->request->param('street_id', 0);
        $param['street'] = $this->request->param('street', '');
        $res = $this->services->ownDeclareQuarantineService($param);
        
        $return_data = [];
        $return_data = $this->_getMoreInfo($param,'quarantine');
        
        if($res['status']) {
            return show(200, $res['msg'],$return_data);
        }else{
            return show(400, $res['msg']);
        }
    }

    public function other()
    {
        $param = $this->request->param();
        $validate = new OwnDeclareValidate();
        if(!$validate->scene('quarantine')->check($param)) {
            return show(40001, $validate->getError());
        }
        
        if(Config::get('app.app_host') != 'dev') { //测试环境不验证
            $smsVerifyTool = new SmsVerifyTool();
            $sms_res = $smsVerifyTool->verifySmsCode('declare_verify', $param['phone'], $param['sms_code']);
            if($sms_res['status'] == 0) {
                return show(40001, $sms_res['msg']);
            }
        }
        $param['id_card'] = strtoupper(trim($this->request->param('id_card', '')));
        $param['street_id'] = $this->request->param('street_id', 0);
        $param['street'] = $this->request->param('street', '');
        $res = $this->services->other($param);
        
        $return_data = [];
        $return_data = $this->_getMoreInfo($param,'come');
        
        if($res['status']) {
            return show(200, $res['msg'],$return_data);
        }else{
            return show(400, $res['msg']);
        }
    }


    public function lastLeave()
    {
        $param = $this->request->param();
        $validate = new OwnDeclareValidate();
        if(!$validate->scene('lastLeave')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->services->lastLeaveService($param);
        return show(200, '成功', $res);
    }

    private function _checkTaiwanXiangganAomen($param){
        if($param['province_id'] == 24){
            // 台湾目前只有省
            return ['status'=>1,'msg'=>'pass'];
        }else if($param['province_id'] == 26 || $param['province_id'] == 27){
            // 香港和澳门只判到区
            if( $param['city_id'] > 0){
                return ['status'=>1,'msg'=>'pass'];
            }else{
                return ['status'=>0,'msg'=>'请选择到区'];
            }
        }else{
            if( $param['county_id'] > 0){
                return ['status'=>1,'msg'=>'pass'];
            }else{
                return ['status'=>0,'msg'=>'省市县必填选择完整'];
            }
        }
    }

    public function getMoreInfoBySign(){
        $param = $this->request->param();
        if($param['id_card'] == ''){
            return show(400, '请传身份证号');
        }
        if($param['real_name'] == ''){
            return show(400, '请传姓名');
        }
        if($param['phone'] == ''){
            return show(400, '请传手机号');
        }
        if($param['sign_out_time'] == ''){
            return show(400, '请sign_out_time');
        }
        if($param['sign'] == ''){
            return show(400, '请sign');
        }
        // 验证签名
        $right_sign = $this->_build_sign($param['id_card'],$param['phone'],$param['sign_out_time']);
        // if($param['sign_out_time'] < time()){
        //     return show(400, '已签名超时,请重新申报');
        // }
        if( $right_sign != $param['sign']){
            return show(400, '签名错误');
        }

        $declare_type = isset($param['declare_type']) ? $param['declare_type'] : 'come';
        $return_data = $this->_getMoreInfo($param,$declare_type);
        return show(200, '成功',$return_data);
    }


    private function _getMoreInfo($param,$declare_type='come'){
        // 获取健康码情况给前端
        $fysjService = app()->make(FysjServices::class);
        $phone = isset($param['phone']) ? $param['phone'] : '';
        $jkm_res =  $fysjService->getJkmActualService($param['id_card'],$phone);

        $jkm_info['jkm_time'] = $jkm_res['jkm_time'];
        $jkm_info['jkm_mzt'] = $jkm_res['jkm_mzt'];
        $jkm_info['jkm_date'] = $jkm_info['jkm_time'] ? Date('Y-m-d',strtotime($jkm_info['jkm_time'])) : null;

        // 获取省核酸检测情况给前端
        $hsjc_res = $fysjService->getShsjcService($param['real_name'], $param['id_card']);

        $hsjc_info['hsjc_result'] = $hsjc_res['hsjc_result'];
        $hsjc_info['hsjc_time'] = $hsjc_res['hsjc_time'];
        $hsjc_info['hsjc_date'] = Date('Y-m-d',strtotime($hsjc_res['hsjc_time']));


        $return_data = [];
        $return_data['jkm_info'] = $jkm_info;
        $return_data['hsjc_info'] = $hsjc_info;
        $return_data['ywhsjc_info'] = null;
        // 同时将信息 进行 合并加工
        $hsjc_time = null;
        if($hsjc_info['hsjc_time'] != null){
            $hsjc_time = $hsjc_info['hsjc_time'];
        }

        if($hsjc_time &&  strtotime($hsjc_time) > (time() - 24*3600) ){
            $return_data['hsjc_24_info'] = '有';
        }else{
            $return_data['hsjc_24_info'] = '无';
        }


        $return_data['real_name'] = $param['real_name'];
        $return_data['phone'] = $param['phone'];
        $return_data['id_card'] = $param['id_card'];
        $return_data['jkm'] = $return_data['jkm_info']['jkm_mzt'];
        $return_data['last_datetime'] = Date('m月d日 H:i');
        $return_data['declare_type'] = $declare_type;
        // 将 'ldrk' + id_card + phone + sign_out_time + 'ldrk' 
        $return_data['sign_out_time'] = time() + 24*3600; // 一天的有效期,该字段前端已经不用
        $return_data['sign'] = $this->_build_sign($return_data['id_card'],$return_data['phone'],$return_data['sign_out_time']);

        return $return_data;
    }

    private function _build_sign($id_card,$phone,$sign_out_time){
         // 将 'ldrk' + id_card + phone + sign_out_time + 'ldrk' 
         return md5('ldrk'.$id_card.$phone.$sign_out_time . 'ldrk');
    }

}
