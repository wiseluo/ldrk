<?php
declare (strict_types=1);

namespace app\services\user;

use app\services\user\BaseServices;
use app\dao\PlaceDao;
use app\dao\PlaceDeclareDao;
use app\dao\UserDao;
use app\dao\RiskDistrictDao;
use \behavior\XcmTool;
use \behavior\QrcodeTool;
use crmeb\services\SwooleTaskService;
use think\facade\Config;
use think\facade\Cache;

class PlaceDeclareServices extends BaseServices
{
    public function __construct(PlaceDeclareDao $dao)
    {
        $this->dao = $dao;
    }

    public function placeDeclareService($param)
    {
        $place = app()->make(PlaceDao::class)->get(['code'=> $param['place_code']]);
        if($place == null) {
            return ['status' => 0, 'msg' => '场所不存在'];
        }
        $city_code = app()->make(RiskDistrictDao::class)->getCityCodeConcat();
        $xcmTool = new XcmTool();
        $xcm_res = $xcmTool->xcmjk($param['phone'], $param['sms_code'], $city_code);
        if($xcm_res['status'] == 0) {
            return ['status' => 0, 'msg' => '行程码短信-'. $xcm_res['msg']];
        }
        $sign = md5($param['place_code'] .'|'. $param['id_card'] .'|'. time());
        $data = [
            'sign' => $sign,
            'place_code' => $param['place_code'],
            'place_name' => $place['name'],
            'place_unit' => $place['unit'],
            'real_name' => $param['real_name'],
            'id_card' => $param['id_card'],
            'phone' => $param['phone'],
            'yw_street_id' => $place['yw_street_id'],
            'yw_street' => $place['yw_street'],
            'xcm_result' => $xcm_res['data']['value'],
        ];
        try {
            $place_declare = $this->dao->save($data);
            //创建用户
            $userDao = app()->make(UserDao::class);
            $user = $userDao->get(['id_card'=> $param['id_card']]);
            if($user == null) {
                $user_data = [
                    'phone' => $param['phone'],
                    'real_name' => $param['real_name'],
                    'id_card' => $param['id_card'],
                    'card_type' => 'id',
                    'uniqid' => randomCode(12),
                ];
                $userDao->save($user_data);
            }
            SwooleTaskService::place()->taskType('place')->data(['action'=>'placeDeclareTaskService','param'=> ['real_name' => $param['real_name'], 'id_card' => $param['id_card'], 'place_declare_id'=> $place_declare->id]])->push();
            return ['status' => 1, 'msg' => '操作成功', 'data'=>['sign'=> $sign]];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }
    
    public function resultService($param)
    {
        $declare = $this->dao->get(['sign' => $param['sign']]);
        if($declare == null) {
            return ['status' => 0, 'msg' => '该申报不存在'];
        }
        switch($param['type']) {
            case 'xcm':
                return ['status' => 1, 'msg' => '成功', 'data'=> ['phone'=> $declare['phone'], 'xcm_result'=> $declare['xcm_result']]];
                break;
            case 'jkm':
                if($declare['jkm_get'] == 1) {
                    return ['status' => 1, 'msg' => '成功', 'data'=> ['jkm_time'=> $declare['jkm_time'], 'jkm_mzt'=> $declare['jkm_mzt']]];
                }else{
                    return ['status' => 2, 'msg' => '数据获取中，请稍等'];
                }
                break;
            case 'hsjc': 
                if($declare['hsjc_get'] != 0) {
                    return ['status' => 1, 'msg' => '成功', 'data'=> ['hsjc_time'=> $declare['hsjc_time'], 'hsjc_jcjg'=> $declare['hsjc_jcjg'], 'hsjc_result'=> $declare['hsjc_result']]];
                }else{
                    return ['status' => 2, 'msg' => '数据获取中，请稍等'];
                }
                break;
            case 'ymjz':
                if($declare['xgymjz_get'] == 1) {
                    return ['status' => 1, 'msg' => '成功', 'data'=> ['vaccination'=> $declare['vaccination'], 'vaccination_date'=> $declare['vaccination_date'], 'vaccination_times'=> $declare['vaccination_times']]];
                }else{
                    return ['status' => 2, 'msg' => '数据获取中，请稍等'];
                }
                break;
            default:
                return ['status' => 0, 'msg' => '类型不存在'];
                break;
        }
    }

    public function detailService($sign)
    {
        $declare = $this->dao->get(['sign' => $sign]);
        if($declare == null) {
            return ['status' => 0, 'msg' => '该申报不存在'];
        }
        $user = app()->make(UserDao::class)->get(['id_card'=> $declare['id_card']]);
        $qrcode_color = 'green';
        if($declare['jkm_mzt'] == '绿码') {
            $qrcode_color = 'green';
        }else if($declare['jkm_mzt'] == '黄码') {
            $qrcode_color = 'yellow';
        }else if($declare['jkm_mzt'] == '红码') {
            $qrcode_color = 'red';
        }
        $user_qrcode = Cache::get('place_declare_user_qrcode_'. $user['uniqid']);
        if($user_qrcode == null) {
            $qrcodeTool = new QrcodeTool();
            $qrcode = $qrcodeTool->getDataUri(Config::get('app.app_domain'). '/csm/#/?uniqid='. $user['uniqid'], $qrcode_color);
            Cache::set('place_declare_user_qrcode_'. $user['uniqid'], $qrcode, 7200);
        }else{
            $qrcode = $user_qrcode;
        }
        $data = [
            'sign' => $declare['sign'],
            'place_code' => $declare['place_code'],
            'place_name' => $declare['place_name'],
            'place_unit' => $declare['place_unit'],
            'real_name' => $declare['real_name'],
            'id_card' => $declare['id_card'],
            'qrcode'=> $qrcode
        ];
        return ['status' => 1, 'msg' => '成功', 'data'=> $data];
    }

    public function scanCodeService($param)
    {
        $place = app()->make(PlaceDao::class)->get(['code'=> $param['place_code']]);
        if($place == null) {
            return ['status' => 0, 'msg' => '场所不存在'];
        }
        $declare = $this->dao->get(['sign'=> $param['sign']]);
        if($declare == null) {
            return ['status' => 2, 'msg' => '申报信息不存在，请重新申报'];
        }
        $declare_time = strtotime($declare['create_time']);
        $curtime = time();
        if($curtime - $declare_time > 86400) { //超过24小时，返回重新申报
            return ['status' => 2, 'msg' => '请重新申报'];
        }
        if($param['place_code'] == $declare['place_code'] && $curtime - $declare_time < 7200) { //2小时内扫同一场所，直接返回申报信息
            $detail_res = $this->detailService($declare['sign']);
            return ['status' => 1, 'msg' => '成功', 'data'=> $detail_res['data']];
        }
        //扫不同场所或者大于2小时小于24小时扫同一场所，都新增申报记录
        $sign = md5($param['place_code'] .'|'. $declare['id_card'] .'|'. time());
        
        $data = [
            'sign' => $sign,
            'place_code' => $param['place_code'],
            'place_name' => $place['name'],
            'place_unit' => $place['unit'],
            'real_name' => $declare['real_name'],
            'id_card' => $declare['id_card'],
            'phone' => $declare['phone'],
            'yw_street_id' => $declare['yw_street_id'],
            'yw_street' => $declare['yw_street'],
            'id_verify_result' => $declare['id_verify_result'],
            'age' => $declare['age'],
            'vaccination' => $declare['vaccination'],
            'vaccination_date' => $declare['vaccination_date'],
            'vaccination_times' => $declare['vaccination_times'],
            'xgymjz_get' => $declare['xgymjz_get'],
            'jkm_time' => $declare['jkm_time'],
            'jkm_mzt' => $declare['jkm_mzt'],
            'jkm_get' => $declare['jkm_get'],
            'hsjc_time' => $declare['hsjc_time'],
            'hsjc_jcjg' => $declare['hsjc_jcjg'],
            'hsjc_result' => $declare['hsjc_result'],
            'hsjc_get' => $declare['hsjc_get'],
            'xcm_result' => $declare['xcm_result'],
        ];
        try {
            $this->dao->save($data);
            $detail_res = $this->detailService($sign);
            return ['status' => 1, 'msg' => '操作成功', 'data'=> $detail_res['data']];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }

    public function placeReadService($code)
    {
        $place = app()->make(PlaceDao::class)->get(['code'=> $code]);
        if($place == null) {
            return ['status' => 0, 'msg' => '场所不存在'];
        }
        return ['status' => 1, 'msg' => '成功', 'data'=> $place];
    }
}
