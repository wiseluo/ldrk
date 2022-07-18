<?php

namespace app\controller;

use app\services\ZzsbViewServices;
use app\services\SgzxServices;
use behavior\FaceTool;
use think\facade\Db;
use Curl\Curl;
use think\facade\Cache;
use \behavior\SsjptTool;
use \behavior\RkkTool;
use behavior\SsjptActualTool;

class TestController
{

    public function test_tmp_log_test(){
        test_tmp_log('测试 set sss:1',__METHOD__,'测试');
        test_tmp_log('测试 set sss:2',__METHOD__,'测试');
        test_tmp_log('测试 set sss:3',__METHOD__,'测试1');
        test_tmp_log('测试 set sss:4',__METHOD__,'测试1');
        test_tmp_log('测试 set sss:5',__METHOD__,'测试2');
        test_tmp_log('测试 set sss:6',__METHOD__,'测试2');
        test_tmp_log('测试 set sss:7',__METHOD__,'测试3');
        test_tmp_log('测试 set sss:8',__METHOD__,'测试4');
    }


    public function test_tmp_log_all(){
        $table = app("swoole.table.tmp_log");
        foreach($table as $key => $value){
            test_log($value);
        }
    }

    public function test_tmp_log_clear(){
        $table = app("swoole.table.tmp_log");
        test_log('delete before table count:'.$table->count());
        foreach($table as $key => $value){
            $table->del($value['key']);
        }
        test_log('table count:'.$table->count());
    }

    public function checkIp(){
        return true;
        $ip = app()->request->ip();
        if(in_array($ip,['112.124.1.163','47.98.144.82'])){
            return true;//['status'=>1,'msg'=>'白名单内'];
        }else{
            return false;
        }
    }

    public function test_set_redis_cache(){
        echo Cache::set('test_set_redis_cache',Date("Y-m-d H:i:s"),3600);
    }

    public function test_get_redis_cache(){
        echo  Cache::get('test_set_redis_cache');
    }

    public function facecheck(){
        $param = request()->param();
        if(!isset($param['real_name']) || !isset($param['id_card']) || !isset($param['face_img'])){
            return show(400, '需要real_name，id_card，face_img等参数');
        }
        $faceTool = new FaceTool();
        $res = $faceTool ->faceCheck($param['real_name'],$param['id_card'],$param['face_img']);
        return show(200, 'ok',$res);
    }


    public function test_build_applet_code(){
        $curl = new Curl();
        $uniqid = randomCode(12);
        $url = 'http://localhost:30399/buildAppletCodeByUserId?uniqid='.$uniqid;
        $curl->get($url);
        if($curl->error) {
            return ['status'=> 0, 'msg'=> $curl->error_code . $curl->error_message];
        }
        $res = $curl->response;
        $curl->close();
        return show(200, 'ok', $res);
    }

    public function test_phone_jkm(){
        $phone = request()->param('phone', '');
        if($phone == '') {
            return show(400, '手机必填');
        }
        $ssjptTool = new SsjptTool();
        $res =  $ssjptTool->skPhoneToJkm($phone);
        if($res['status'] == 1) {
            return show(200, 'ok', $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }


    public function test_zzsb_view()
    {
        $lastid = request()->param('lastid');
        if($lastid == '') {
            return show(400, 'lastid必填');
        }
        $data = app()->make(ZzsbViewServices::class)->zzsb_view($lastid);
        return show(200,'ok',$data);
    }
    
    public function test_csm_ryxx()
    {
        $id_card = request()->param('id_card');
        if($id_card == '') {
            return show(400, 'id_card必填');
        }
        $data = app()->make(ZzsbViewServices::class)->csm_ryxx($id_card);
        return show(200,'ok',$data);
    }
    
    public function qsjkmxxcx()
    {
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

    // 查看健康码实时接口
    public function jkmss(){
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
    
    public function hsjccysj()
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

    public function sxgymyfjzxxcx()
    {
        $id_card = request()->param('id_card', '');
        if($id_card == '') {
            return show(400, 'id_card必填');
        }
        $ssjptTool = new SsjptTool();
        $res =  $ssjptTool->skxgymyfjzxxcx($id_card);
        if($res['status'] == 1) {
            return show(200, 'ok', $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }

    public function shsjcjk()
    {
        if(!$this->checkIp()){
            return show(400, '不在白名单内');
        }

        $id_card = request()->param('id_card', '');
        if($id_card == '') {
            return show(400, 'id_card必填');
        }
        $real_name = request()->param('real_name', '');
        if($real_name == '') {
            return show(400, 'real_name必填');
        }
        $ssjptTool = new SsjptTool();
        $res =  $ssjptTool->skhsjcjk($real_name, $id_card);
        if($res['status'] == 1) {
            return show(200, 'ok', $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }

    // public function ywhsjcjk()
    // {
    //     $id_card = request()->param('id_card', '');
    //     if($id_card == '') {
    //         return show(400, 'id_card必填');
    //     }
    //     $real_name = request()->param('real_name', '');
    //     if($real_name == '') {
    //         return show(400, 'real_name必填');
    //     }
    //     $res = app()->make(SgzxServices::class)->ywhsjcjk($real_name, $id_card);
    //     if($res['status']) {
    //         var_dump($res);
    //         return show(200, 'ok', $res['data']);
    //     }else{
    //         return show(200, $res['msg']);
    //     }
    // }
    public function test_log(){
        $data = Db::name('test')->order('id','desc')->limit(5)->select()->toArray();
        var_dump($data);
    }
    public function test()
    {
        $post = request()->param();
        var_dump($post);
    }

    public function enterpriseInfo()
    {
        $credit_code = request()->param('credit_code', '');
        if($credit_code == '') {
            return show(400, 'credit_code必填');
        }
        $res = app()->make(SgzxServices::class)->enterpriseInfo($credit_code);
        if($res['status'] == 1) {
            return show(200, 'ok', $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }

    public function skxcmdx()
    {
        $phone = request()->param('phone', '');
        $ssjptTool = new SsjptTool();
        $res =  $ssjptTool->skxcmdxjk($phone);
        if($res['status'] == 1) {
            return show(200, 'ok', $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }

    public function skxcm()
    {
        $phone = request()->param('phone', '');
        $sms_code = request()->param('sms_code', '');
        $city_code = request()->param('city_code', '');
        $ssjptTool = new SsjptTool();
        $res =  $ssjptTool->skxcmjk($phone, $sms_code, $city_code);
        if($res['status'] == 1) {
            return show(200, 'ok', $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }

    public function sktest()
    {
        $id_card = request()->param('id_card', '');
        $ssjptTool = new SsjptTool();
        $res =  $ssjptTool->skxgymyfjzxxcx($id_card);
        if($res['status'] == 1) {
            return show(200, 'ok', $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }
    
    public function sklssws()
    {
        $name = request()->param('name', '');
        if($name == '') {
            return show(400, 'name必填');
        }
        $credit_code = request()->param('credit_code', '');
        if($credit_code == '') {
            return show(400, 'credit_code必填');
        }
        $ssjptTool = new SsjptTool();
        $res =  $ssjptTool->sklssws($name, $credit_code);
        if($res['status'] == 1) {
            return show(200, 'ok', $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }

    public function ywhjrk()
    {
        $real_name = request()->param('real_name', '');
        if($real_name == '') {
            return show(400, 'real_name必填');
        }
        $id_card = request()->param('id_card', '');
        if($id_card == '') {
            return show(400, 'id_card必填');
        }
        $rkkTool = new RkkTool();
        $hj_res = $rkkTool->getByYWHJRK($real_name, $id_card);
        if($hj_res['status'] == 1) {
            return show(200, 'ok', $hj_res['data']);
        }else{
            return show(400, $hj_res['msg']);
        }
    }
    
    public function snrk()
    {
        $real_name = request()->param('real_name', '');
        if($real_name == '') {
            return show(400, 'real_name必填');
        }
        $id_card = request()->param('id_card', '');
        if($id_card == '') {
            return show(400, 'id_card必填');
        }
        $rkkTool = new RkkTool();
        $hj_res = $rkkTool->getBySNRK($real_name, $id_card);
        if($hj_res['status'] == 1) {
            return show(200, 'ok', $hj_res['data']);
        }else{
            return show(400, $hj_res['msg']);
        }
    }
    
    public function qgrkk()
    {
        $real_name = request()->param('real_name', '');
        if($real_name == '') {
            return show(400, 'real_name必填');
        }
        $id_card = request()->param('id_card', '');
        if($id_card == '') {
            return show(400, 'id_card必填');
        }
        $rkkTool = new RkkTool();
        $hj_res = $rkkTool->getByQGRKK($real_name, $id_card);
        if($hj_res['status'] == 1) {
            return show(200, 'ok', $hj_res['data']);
        }else{
            return show(400, $hj_res['msg']);
        }
    }

    public function get_json(){
        $data = Db::table('todo')->select();
        echo json_encode($data);
    }


}