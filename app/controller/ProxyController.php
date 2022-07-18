<?php

namespace app\controller;

use behavior\SsjptTool;
use behavior\SsjptActualTool;

class ProxyController
{

    public function checkIp(){
        // return true;
        $ip = app()->request->ip();
        if(in_array($ip,['112.124.1.163','47.98.144.82'])){
            return true;//['status'=>1,'msg'=>'白名单内'];
        }else{
            return false;
        }
    }

    public function skPhoneToJkm(){
        if(!$this->checkIp()){
            return show(400, '不在白名单内');
        }
        $phone = request()->param('phone', '');
        $times = request()->param('times', 0);
        if($phone == '') {
            return show(400, '手机必填');
        }
        $ssjptTool = new SsjptTool();
        $res =  $ssjptTool->skPhoneToJkm($phone,$times);
        if($res['status'] == 1) {
            return show(200, 'ok', $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }
    
    public function skIdcardToJkm()
    {
        if(!$this->checkIp()){
            return show(400, '不在白名单内');
        }
        $id_card = request()->param('id_card', '');
        $times = request()->param('times', 0);
        $is_all = request()->param('is_all', 0);
        if($id_card == '') {
            return show(400, 'id_card必填');
        }
        $ssjptTool = new SsjptTool();
        $res =  $ssjptTool->skIdcardToJkm($id_card,$times,$is_all);
        if($res['status'] == 1) {
            return show(200, 'ok', $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }


    public function skxcmdxjk()
    {
        if(!$this->checkIp()){
            return show(400, '不在白名单内');
        }
        $phone = request()->param('phone', '');
        $times = request()->param('times', 0);
        $ssjptTool = new SsjptTool();
        $res =  $ssjptTool->skxcmdxjk($phone,$times);
        if($res['status'] == 1) {
            return show(200, 'ok', $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }

    public function skxcmjk()
    {
        if(!$this->checkIp()){
            return show(400, '不在白名单内');
        }
        $phone = request()->param('phone', '');
        $verification = request()->param('verification', '');
        $city_code = request()->param('city_code', '');
        $ssjptTool = new SsjptTool();
        $res =  $ssjptTool->skxcmjk($phone, $verification, $city_code);
        if($res['status'] == 1) {
            return show(200, 'ok', $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }
    public function skhsjcjk()
    {
        if(!$this->checkIp()){
            return show(400, '不在白名单内');
        }
        $times = request()->param('times', 0);
        $id_card = request()->param('id_card', '');
        if($id_card == '') {
            return show(400, 'id_card必填');
        }
        $real_name = request()->param('real_name', '');
        if($real_name == '') {
            return show(400, 'real_name必填');
        }
        $ssjptTool = new SsjptTool();
        $res =  $ssjptTool->skhsjcjk($real_name, $id_card,$times);
        if($res['status'] == 1) {
            return show(200, 'ok', $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }

    public function skxgymyfjzxxcx()
    {
        if(!$this->checkIp()){
            return show(400, '不在白名单内');
        }
        $id_card = request()->param('id_card', '');
        $times = request()->param('times', 0);
        if($id_card == '') {
            return show(400, 'id_card必填');
        }
        $ssjptTool = new SsjptTool();
        $res =  $ssjptTool->skxgymyfjzxxcx($id_card,$times);
        if($res['status'] == 1) {
            return show(200, 'ok', $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }
    
    public function sklssws()
    {
        if(!$this->checkIp()){
            return show(400, '不在白名单内');
        }
        $name = request()->param('name', '');
        $times = request()->param('times', 0);
        if($name == '') {
            return show(400, 'name必填');
        }
        $credit_code = request()->param('credit_code', '');
        if($credit_code == '') {
            return show(400, 'credit_code必填');
        }
        $ssjptTool = new SsjptTool();
        $res =  $ssjptTool->sklssws($name, $credit_code,$times);
        if($res['status'] == 1) {
            return show(200, 'ok', $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }
    // 查看健康码实时接口
    public function skIdcardAndPhoneToJkm(){
        if(!$this->checkIp()){
            return show(400, '不在白名单内');
        }
        $id_card = request()->param('id_card', '');
        $phone = request()->param('phone', '');
        $times = request()->param('times', 0);
        $is_all = request()->param('is_all', 0);
        if($id_card == '') {
            return show(400, 'id_card必填');
        }
        if($phone == '') {
            return show(400, 'phone必填');
        }
        $ssjptTool = new SsjptActualTool();
        $res =  $ssjptTool->skIdcardAndPhoneToJkm($id_card,$phone,$times,$is_all);
        if($res['status'] == 1) {
            return show(200, 'ok', $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }

    public function skGetHsjcCollectTime()
    {
        $id_card = request()->param('id_card', '');
        $times = request()->param('times', 0);
        if($id_card == '') {
            return show(400, 'id_card必填');
        }
        $ssjptTool = new SsjptActualTool();
        $res = $ssjptTool->skGetHsjcCollectTime($id_card,$times);
        if($res['status'] == 1) {
            return show(200, 'ok', $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }
}