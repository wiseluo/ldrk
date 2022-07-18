<?php

namespace app\services\applet;

use app\services\user\BaseServices;
use app\dao\PlaceDao;
use app\dao\PlaceTypeDao;
use app\dao\DistrictDao;
use \behavior\WechatAppletTool;
use crmeb\services\SwooleTaskService;
use \behavior\SmsTool;
use app\services\SgzxServices;
use think\facade\Cache;

class PlaceServices extends BaseServices
{
    public function __construct(PlaceDao $dao)
    {
        $this->dao = $dao;
    }

    public function getTypeList($param)
    {
        $hasCache = Cache::get('PlaceTypeList');
        if($hasCache){
            // test_log('from PlaceTypeList Cache');
            return json_decode($hasCache,true);
        }else{
            $data =  app()->make(PlaceTypeDao::class)->getList($param);
            if($data){
                // test_log('from PlaceTypeList DB');
                Cache::set('PlaceTypeList',json_encode($data),36000);
                return $data;
            }
            return [];
        }
    }

    public function readService($user_id)
    {
        $place = $this->dao->get(['user_id'=> $user_id]);
        if($place) {
            $place->append(['place_classify_text','applet_qrcode_arr']);
            //$place['applet_qrcode'] = str_replace('_qrcode.png', '_qrcode_v2.png', $place['applet_qrcode']);
            return ['status' => 1, 'msg'=> '成功', 'data'=> $place];
        }else{
            return ['status' => 0, 'msg'=> '不存在'];
        }
    }
    
    public function applyV2Service($param, $user_id)
    {
        // test_log('applyV2Service');
        $place = $this->dao->get(['user_id'=> $user_id]);
        if($place) {
            return ['status' => 0, 'msg' => '您已申请过场所码，不能重复申请'];
        }
        $place_type_id = $param['place_type_id'];
        $place_type = isset($param['place_type']) ? $param['place_type'] : '';
        if($param['place_classify'] == 'gov') {
            $place_type_id = 32;
            $place_type = '党政机关';
        }else if($param['place_classify'] == 'unit') {
            $place_type_id = 33;
            $place_type = '事业单位';
        }else if($param['place_classify'] == 'social') {
            $place_type_id = 34;
            $place_type = '社会组织';
        }
        if(isset($param['credit_code']) && $param['credit_code'] != '') {
            $credit_code = trim($param['credit_code']);
        }else{
            $credit_code = '';
        }
        $name = trim($param['name']);
        $short_name = trim($param['short_name']);
        if($param['place_classify'] == 'company') {
            $sgzx_res = app()->make(SgzxServices::class)->enterpriseInfo($credit_code);
            if($sgzx_res['status'] == 0) {
                return ['status'=> 0, 'msg'=> '社会信用代码证错误'];
            }
            if($sgzx_res['data']['companyName'] != $name) {
                return ['status'=> 0, 'msg'=> '名称需与营业执照中的名称一致：'.$sgzx_res['data']['companyName']];
            }
        }
        // test_log('WechatAppletTool');
        $code = randomCode(12);
        $wechatAppletTool = new WechatAppletTool();
        $applet_qrcode = $wechatAppletTool->appletPlaceQrcode($code, $short_name);
        if($applet_qrcode['status'] == 0) {
            // test_log('applet_qrcode status 0');
            return ['status' => 0, 'msg' => '操作失败-'. $applet_qrcode['msg']];
        }
        $yw_street = app()->make(DistrictDao::class)->getNameById($param['yw_street_id']);
        $data = [
            'code' => $code,
            'applet_qrcode' => $applet_qrcode['data'],
            'name' => $name,
            'short_name' => $short_name,
            'place_classify' => $param['place_classify'],
            'place_type_id' => $place_type_id,
            'place_type' => $place_type,
            'link_man' => $param['link_man'],
            'link_phone' => $param['link_phone'],
            'yw_street_id' => $param['yw_street_id'],
            'yw_street' => $yw_street,
            'addr' => $param['addr'],
            'credit_code' => $credit_code,
            'source' => 'apply',
            'user_id' => $user_id,
            'lng' => $param['lng'],
            'lat' => $param['lat'],
        ];
        // test_log('start save');
        try {
            $this->dao->save($data);
            //发送短信
            // $smsTool = new SmsTool();
            // $res = $smsTool->sendSms($param['link_phone'], '您已成功申请到场所码，请打开义乌防疫小程序查看');
            // if($res['status'] == 1) {
            //     return ['status'=> 1, 'msg'=> '操作成功'];
            // }else{
            //     return ['status'=> 0, 'msg'=> '短信发送失败! 状态：' . $res['msg']];
            // }
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }

    public function updateService($param, $user_id)
    {
        $place = $this->dao->get(['user_id'=> $user_id]);
        if($place == null) {
            return ['status' => 0, 'msg' => '您未申请过场所码'];
        }
        $place_type_id = $param['place_type_id'];
        $place_type = $param['place_type'];
        if($param['place_classify'] == 'gov') {
            $place_type_id = 32;
            $place_type = '党政机关';
        }else if($param['place_classify'] == 'unit') {
            $place_type_id = 33;
            $place_type = '事业单位';
        }else if($param['place_classify'] == 'social') {
            $place_type_id = 34;
            $place_type = '社会组织';
        }
        if(isset($param['credit_code']) && $param['credit_code'] != '') {
            $credit_code = trim($param['credit_code']);
        }else{
            $credit_code = '';
        }
        $name = trim($param['name']);
        $short_name = trim($param['short_name']);
        if($param['place_classify'] == 'company') {
            $sgzx_res = app()->make(SgzxServices::class)->enterpriseInfo($credit_code);
            if($sgzx_res['status'] == 0) {
                return ['status'=> 0, 'msg'=> '社会信用代码证错误'];
            }
            if($sgzx_res['data']['companyName'] != $param['name']) {
                return ['status'=> 0, 'msg'=> '名称需与营业执照中的名称一致'];
            }
        }
        $yw_street = app()->make(DistrictDao::class)->getNameById($param['yw_street_id']);
        $data = [
            'name' => $name,
            'short_name' => $short_name,
            'place_classify' => $param['place_classify'],
            'place_type_id' => $place_type_id,
            'place_type' => $place_type,
            'link_man' => $param['link_man'],
            'link_phone' => $param['link_phone'],
            'yw_street_id' => $param['yw_street_id'],
            'yw_street' => $yw_street,
            'addr' => $param['addr'],
            'credit_code' => $credit_code,
            'lng' => $param['lng'],
            'lat' => $param['lat'],
        ];
        try {
            $this->dao->update($place['id'], $data);
            if($param['short_name'] != $place['short_name']) {
                SwooleTaskService::place()->taskType('place')->data(['action'=>'placeQrcodeWatermarkService','param'=> ['file_path' => $place['applet_qrcode'], 'version' => 'v2', 'short_name'=> $param['short_name']]])->push();
            }
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }

    public function buildAppletCodeAndGetImage($limit=1){
        $data = $this->dao->getNeedInit($limit);
        if($data){
            foreach($data as $key => $value){
                SwooleTaskService::declare()->taskType('place')->data(['action'=>'buildAppletCodeAndGetImageTask','param'=> $value])->push();
            }
            return ['status' => 1, 'msg' => '触发'.count($data).'个'];
        }else{
            return ['status' => 1, 'msg' => '触发0个'];
        }
    }

    public function deleteService($id, $user_id)
    {
        $place = $this->dao->get($id);
        if($place == null) {
            return ['status' => 0, 'msg' => '场所码不存在'];
        }
        if($user_id != $place['user_id']) {
            return ['status' => 0, 'msg' => '您不是该场所码的联络员，不能删除'];
        }
        
        try {
            $this->dao->softDelete($id);
            Cache::delete('place_code_'.$place['place_code']);
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }
}
