<?php

namespace app\services\applet;

use app\services\applet\BaseServices;
use \behavior\SsjptTool;
use \behavior\QrcodeTool;
use app\dao\UserManagerDao;
use app\dao\PlaceDao;
use app\dao\PlaceDeclareDao;
use app\dao\UserDao;
use app\dao\PersonalCodeDao;
use app\dao\RiskDistrictDao;
use app\dao\RiskDistrictProDao;
use app\dao\UserHsjcLogDao;
use crmeb\services\SwooleTaskService;
use think\facade\Config;
use think\facade\Cache;
use app\services\PlaceServices;
use think\facade\Db;

class PlaceDeclareServices extends BaseServices
{
    public function __construct(PlaceDeclareDao $dao)
    {
        $this->dao = $dao;
    }

    public function placeDeclareService($place_code, $userInfo, $for_test=0, $place=null)
    {
        if($place == null){
            $place = app()->make(PlaceDao::class)->get(['code'=> $place_code]);
        }
        if($place == null) {
            return ['status' => 0, 'msg' => '场所不存在'];
        }
        // 如果需要强制识别人脸
        if(isset($place['is_need_face']) && $place['is_need_face'] == 1){
            // 已经人脸识别
            if( time() - $userInfo['face_time'] > 3){
                return ['status' => 2, 'msg' => '超3秒，需重新识别'];
            }else{
                // 不做返回
            }
        }

        $sign = md5($place_code .'|'. $userInfo['id_card'] .'|'. microtime(true));

        if($userInfo['xcm_gettime'] == null || (time() - strtotime($userInfo['xcm_gettime']) > 86400)) { // 获取超过24小时
            $xcm_result = 0;
        }else{
            $xcm_result = $userInfo['xcm_result'];
        }
        if($xcm_result == 0 && $userInfo['xcm_gettime']){
            // 如果是早上的 7点到9点 时间段，用户有最近一次短信验证不超过3天，且是绿色，就直接返回行程卡绿色
            $Hi = Date('Hi');
            if($Hi >= 700  && $Hi <= 900 &&  (time() - strtotime($userInfo['xcm_gettime']) < 259200 ) && $userInfo['xcm_result'] == 1 ){
                $xcm_result = '1';
            }
        }
        $xcm_verify_high_risk_place = explode(',', $this->getXcmVerifyHighRiskPlace());
        if(count($xcm_verify_high_risk_place) > 0 && in_array($place['code'], $xcm_verify_high_risk_place)) { // 需要再次验证重点中高风险地区的场所码，再次查询
            $xcm_result = 0;
        }

        $data = [
            'sign' => $sign,
            'place_code' => $place_code,
            'place_name' => $place['name'],
            'place_short_name' => $place['short_name'],
            'place_addr' => $place['addr'],
            'link_man' => $place['link_man'],
            'link_phone' => $place['link_phone'],
            'real_name' => $userInfo['real_name'],
            'id_card' => $userInfo['id_card'],
            'phone' => $userInfo['phone'],
            'yw_street_id' => $place['yw_street_id'],
            'yw_street' => $place['yw_street'],
            'xcm_result' => $xcm_result,
            'create_date' => date('Y-m-d'),
            'create_datetime' => date('Y-m-d H:i:s'),
        ];
        try {
            $place_declare = $this->dao->save($data);
            $place_declare['watermark_url'] = $place['watermark_url'];
            if($for_test == 1){
                return ['status' => 1, 'msg' => '操作成功', 'data'=> $place_declare];
            }
            SwooleTaskService::place()->taskType('place')->data(['action'=>'placeDeclareTaskService','param'=> ['real_name' => $userInfo['real_name'], 'id_card' => $userInfo['id_card'], 'place_declare_id'=> $place_declare['id'], 'sign'=> $sign]])->push();
            return ['status' => 1, 'msg' => '操作成功', 'data'=> $place_declare];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }
    
    //已废弃
    // public function resultService($param)
    // {
    //     $declare = $this->dao->get(['sign' => $param['sign']]);
    //     if($declare == null) {
    //         return ['status' => 0, 'msg' => '该申报不存在'];
    //     }
    //     switch($param['type']) {
    //         case 'ymjz':
    //             if($declare['xgymjz_get'] == 1) {
    //                 return ['status' => 1, 'msg' => '成功', 'data'=> ['vaccination'=> $declare['vaccination'], 'vaccination_date'=> $declare['vaccination_date'], 'vaccination_times'=> $declare['vaccination_times']]];
    //             }else{
    //                 return ['status' => 2, 'msg' => '数据获取中，请稍等'];
    //             }
    //             break;
    //         case 'jkm':
    //             if($declare['jkm_get'] == 1) {
    //                 // $user = app()->make(UserDao::class)->get(['id_card'=> $declare['id_card']]);
    //                 // $user_qrcode = Cache::get('place_declare_user_qrcode_'. $user['uniqid']);
    //                 // if($user_qrcode == null) {
    //                 //     $qrcode_color = 'green';
    //                 //     if($declare['jkm_mzt'] == '绿码') {
    //                 //         $qrcode_color = 'green';
    //                 //     }else if($declare['jkm_mzt'] == '黄码') {
    //                 //         $qrcode_color = 'yellow';
    //                 //     }else if($declare['jkm_mzt'] == '红码') {
    //                 //         $qrcode_color = 'red';
    //                 //     }
    //                 //     $qrcodeTool = new QrcodeTool();
    //                 //     $qrcode = $qrcodeTool->getDataUri(Config::get('app.app_domain'). '/csm/#/?uniqid='. $user['uniqid'], $qrcode_color);
    //                 //     Cache::set('place_declare_user_qrcode_'. $user['uniqid'], $qrcode, 86400); //24小时
    //                 // }else{
    //                 //     $qrcode = $user_qrcode;
    //                 // }
    //                 return ['status' => 1, 'msg' => '成功', 'data'=> ['jkm_time'=> $declare['jkm_time'], 'jkm_mzt'=> $declare['jkm_mzt']]];
    //             }else{
    //                 return ['status' => 2, 'msg' => '数据获取中，请稍等'];
    //             }
    //             break;
    //         case 'hsjc':
    //             if($declare['hsjc_get'] != 0) {
    //                 return ['status' => 1, 'msg' => '成功', 'data'=> ['hsjc_time'=> $declare['hsjc_time'], 'hsjc_jcjg'=> $declare['hsjc_jcjg'], 'hsjc_result'=> $declare['hsjc_result']]];
    //             }else{
    //                 return ['status' => 2, 'msg' => '数据获取中，请稍等'];
    //             }
    //             break;
    //         case 'xcm':
    //             return ['status' => 1, 'msg' => '成功', 'data'=> ['phone'=> $declare['phone'], 'xcm_result'=> $declare['xcm_result']]];
    //             break;
    //         case 'ryxx':
    //             return ['status' => 1, 'msg' => '成功', 'data'=> ['ryxx_result'=> $declare['ryxx_result']]];
    //             break;
    //         default:
    //             return ['status' => 0, 'msg' => '类型不存在'];
    //             break;
    //     }
    // }

    public function resultWholeService($param)
    {
        $declare = $this->dao->get(['sign' => $param['sign']]);
        if($declare == null) {
            return ['status' => 0, 'msg' => '该申报不存在'];
        }
        // 如果declare['id_card'] 与 $userInfo['id_card'] 不一致，可能是代扫
        $userInfo = app()->make(UserDao::class)->get(['id_card'=> $declare['id_card']]);

        if($declare['jkm_mzt'] == '绿码') {
            $qrcode = Config::get('app.app_domain') .'/statics/images/default_code_pic/default_green.png';
        }else if($declare['jkm_mzt'] == '黄码') {
            $qrcode = Config::get('app.app_domain') .'/statics/images/default_code_pic/default_yellow.png';
        }else if($declare['jkm_mzt'] == '红码') {
            $qrcode = Config::get('app.app_domain') .'/statics/images/default_code_pic/default_red.png';
        }else{
            $qrcode = '';
        }
        $hsjc_time_diff_str = '';
        $hsjc_hours = 0;
        $hsjc_value = 0;
        $hsjc_value_unit = '小时';
        $hsjc_color = '绿色';
        $hsjc_cktxt = $declare['hsjc_result'];
        if($declare['hsjc_time']) {
            $hsjc_time_diff = time() - strtotime($declare['hsjc_time']);
            $hsjc_time_day = bcdiv((string)$hsjc_time_diff, '86400');
            $hsjc_hours = bcdiv((string)$hsjc_time_diff, '3600');
            if($hsjc_time_day <= 1) {
                $hsjc_time_diff_str = '24小时内';
            }else if($hsjc_time_day <= 2) {
                $hsjc_time_diff_str = '48小时内';
            }else if($hsjc_time_day <= 3) {
                $hsjc_time_diff_str = '72小时内';
            }
            if($hsjc_hours <= 72) {
                $hsjc_value_unit = '小时';
                $hsjc_value = (int)$hsjc_hours;
                $hsjc_cktxt = $declare['hsjc_result'];
            }else{
                $hsjc_value_unit = '小时';
                $hsjc_value = 72;
                $hsjc_cktxt = '未检';
                $hsjc_color = '灰色';
            }
            $hsjc_time_diff_str = '距今'. $hsjc_time_day .'天';
        }
        $xcm_time_diff = null;
        $xcm_gettime = null;

        if($userInfo['xcm_gettime']) {
            $between_time = 86400 - (time() - strtotime($userInfo['xcm_gettime']));
            $xcm_time_diff = $between_time * 1000;
            if($between_time < 0){
                // 如果是早上的 7点到9点 时间段，用户有最近一次短信验证不超过3天，且是绿色，就直接返回行程卡绿色
                $Hi = Date('Hi');
                if($Hi >= 700  && $Hi <= 900 &&  (time() - strtotime($userInfo['xcm_gettime']) < 259200 ) && $userInfo['xcm_result'] == 1 ){
                    $xcm_result = $declare['xcm_result']; // 在扫码时已经赋值1,为了统一展示和真实的记录（此处用了变量）
                    $xcm_gettime = $xcm_gettime = Date('Y-m-d H:i:s',time() - 86400);
                    $xcm_time_diff = 0;
                }
            }
        }
        if($xcm_gettime == null){
            $xcm_result = $declare['xcm_result'];
            $xcm_gettime = $userInfo['xcm_gettime'];
        }

        $data = [
            'vaccination'=> $declare['vaccination'],
            'vaccination_date'=> $declare['vaccination_date'],
            'vaccination_times'=> $declare['vaccination_times'],
            'xgymjz_get'=> $declare['xgymjz_get'],
            'jkm_time'=> $declare['jkm_time'],
            'jkm_mzt'=> $declare['jkm_mzt'],
            'jkm_get'=> $declare['jkm_get'],
            'hsjc_time'=> $declare['hsjc_time'],
            'hsjc_jcjg'=> $declare['hsjc_jcjg'],
            'hsjc_result'=> $declare['hsjc_result'],
            'hsjc_get'=> $declare['hsjc_get'],
            'qrcode'=> $qrcode,
            'phone'=> $declare['phone'],
            'xcm_result'=> $xcm_result,
            'xcm_gettime' => $xcm_gettime,
            'xcm_time_diff' => $xcm_time_diff,
            'ryxx_result'=> $declare['ryxx_result'],
            'ryxx_cc'=> isset($userInfo['ryxx_cc']) ? $userInfo['ryxx_cc'] : ($declare['ryxx_result'] == '' ? '绿' : '黄'), // #无需管控
            'hsjc_time_diff_str'=> $hsjc_time_diff_str,
            'hsjc_hours'=> (int)$hsjc_hours,
            'hsjc_color'=> $hsjc_color,
            'hsjc_value_unit' => $hsjc_value_unit,
            'hsjc_value' => $hsjc_value,
            'hsjc_cktxt' => $hsjc_cktxt,
            'ryxx_more' => $this->_ryxx_more($declare['ryxx_result'],$userInfo)
        ];
        return ['status' => 1, 'msg' => '成功', 'data'=> $data];
    }

    public function scanCodeService($param, $userInfo, $for_test=0)
    {
        $cachePlace = Cache::get('place_code_'.$param['place_code']);
        if($cachePlace){
            // test_log('来自缓存');
            $place = $cachePlace;
        }else{
            // test_log('来自数据库');
            $place = app()->make(PlaceDao::class)->get(['code'=> $param['place_code']]);
            if($place){
                $cachePlace = Cache::set('place_code_'.$param['place_code'],$place,7200);
            }
        }

        if($place == null) {
            return ['status' => 0, 'msg' => '场所不存在'];
        }
        return $this->placeDeclareService($param['place_code'], $userInfo,$for_test,$place);
        // if($for_test){
        //     return $this->placeDeclareService($param['place_code'], $userInfo,$for_test,$place);
        // }
        // if($userInfo['csmsb_sign'] == '') { //未申报过
        //     return $this->placeDeclareService($param['place_code'], $userInfo,$for_test,$place);
        // }
        // $declare = $this->dao->get(['sign'=> $userInfo['csmsb_sign']]);
        // if($declare == null) {
        //     return $this->placeDeclareService($param['place_code'], $userInfo,$for_test,$place);
        // }
        // $declare_time = strtotime($declare['create_time']);
        // $curtime = time();
        // // 主要防止1秒内上千请求的暴力提交
        // if($param['place_code'] == $declare['place_code'] && $curtime - $declare_time < 2) { //2秒内扫同一场所，直接返回申报信息
        //     return ['status' => 1, 'msg' => '成功', 'data'=> $declare];
        // }
        // return $this->placeDeclareService($param['place_code'], $userInfo,$for_test,$place);
    }

    public function replaceScanService($param)
    {
        $cachePlace = Cache::get('place_code_'. $param['place_code']);
        if($cachePlace){
            // test_log('来自缓存');
            $place = $cachePlace;
        }else{
            // test_log('来自数据库');
            $place = app()->make(PlaceDao::class)->get(['code'=> $param['place_code']]);
            if($place){
                $cachePlace = Cache::set('place_code_'.$param['place_code'],$place,600);
            }
        }
        if($place == null) {
            return ['status' => 0, 'msg' => '场所不存在'];
        }
        $user = app()->make(UserDao::class)->get(['uniqid'=> $param['uniqid']]);
        if($user == null) {
            return ['status' => 0, 'msg' => '用户不存在'];
        }
        $personal_code = app()->make(PersonalCodeDao::class)->get(['id_card'=> $user['id_card']]);
        // if($personal_code == null) {
        //     return ['status' => 0, 'msg' => '个人码不存在'];
        // }

        $sign = md5($param['place_code'] .'|'. $user['id_card'] .'|'. microtime(true));

        $xcm_result = '0';
        if($user['xcm_gettime'] == null || (time() - strtotime($user['xcm_gettime']) > 86400)) { // 获取超过24小时
            $xcm_result = '3';
        }else{
            $xcm_result = $user['xcm_result'];
        }
        
        $xcm_verify_high_risk_place = explode(',', $this->getXcmVerifyHighRiskPlace());
        if(count($xcm_verify_high_risk_place) > 0 && in_array($place['code'], $xcm_verify_high_risk_place)) { // 需要再次验证重点中高风险地区的场所码，再次查询
            if($personal_code && $personal_code['phone'] != '') { //有个人手机号，则验证
                $xcm_result = '0';
            }
        }

        $data = [
            'sign' => $sign,
            'place_code' => $param['place_code'],
            'place_name' => $place['name'],
            'place_short_name' => $place['short_name'],
            'place_addr' => $place['addr'],
            'link_man' => $place['link_man'],
            'link_phone' => $place['link_phone'],
            'real_name' => $user['real_name'],
            'id_card' => $user['id_card'],
            'phone' => $personal_code['phone'],
            'yw_street_id' => $place['yw_street_id'],
            'yw_street' => $place['yw_street'],
            'xcm_result' => $xcm_result,
            'create_date' => date('Y-m-d'),
            'create_datetime' => date('Y-m-d H:i:s'),
        ];
        try {
            $place_declare = $this->dao->save($data);
            $place_declare['watermark_url'] = $place['watermark_url'];
            SwooleTaskService::place()->taskType('place')->data(['action'=>'placeDeclareTaskService','param'=> ['real_name' => $user['real_name'], 'id_card' => $user['id_card'], 'place_declare_id'=> $place_declare->id, 'sign'=> $sign]])->push();
            return ['status' => 1, 'msg' => '操作成功', 'data'=> $place_declare];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }

    public function getXcmVerifyHighRiskPlace()
    {
        $xcm_verify_high_risk_place = Cache::get('xcm_verify_high_risk_place');
        if($xcm_verify_high_risk_place == null) {
            // $xcm_verify_high_risk_place = Db::name('system_config')->where('menu_name', 'xcm_verify_high_risk_place')->value('value');
            $data = Db::name('place')->field('code')->whereNull('delete_time')->where('xcm_level','=',2)->select()->toArray();
            $xcm_verify_high_risk_place = implode(',',array_column($data,'code'));
            Cache::set('xcm_verify_high_risk_place', $xcm_verify_high_risk_place, 600);
        }
        return (string)$xcm_verify_high_risk_place;
    }

    public function xcmResultService($param, $userInfo)
    {
        // if($userInfo['phone'] != $param['phone']){
        //     return ['status' => 0, 'msg' => '不能冒用别人的手机'];
        // }
        $declare = $this->dao->get(['sign' => $param['sign']]);
        if($declare == null) {
            return ['status' => 0, 'msg' => '该申报不存在'];
        }
        $city_code = app()->make(RiskDistrictDao::class)->getCityCodeConcat();
        $ssjptTool = new SsjptTool();
        $xcm_res =  $ssjptTool->skxcmjk($param['phone'], $param['sms_code'], $city_code);
        if($xcm_res['status'] == 0) {
            return ['status' => 0, 'msg' => '行程码短信-'. $xcm_res['msg']];
        }else if($xcm_res['status'] == 2) {
            return ['status' => 2, 'msg' => '行程码短信-'. $xcm_res['msg']];
        }
        try {
            $xcm_result = $xcm_res['data']['value'];
            $xcm_verify_high_risk_place = explode(',', $this->getXcmVerifyHighRiskPlace());
            if( count($xcm_verify_high_risk_place) > 0 && in_array($declare['place_code'], $xcm_verify_high_risk_place)) { // 需要再次验证重点中高风险地区的场所码，再次查询
                $high_city_code = app()->make(RiskDistrictProDao::class)->getCityCodeConcat();
                $high_xcm_res =  $ssjptTool->skxcmjk($param['phone'], $param['sms_code'], $high_city_code);
                if($high_xcm_res['status'] == 0) {
                    return ['status' => 0, 'msg' => '行程码短信-'. $high_xcm_res['msg']];
                }else if($high_xcm_res['status'] == 2) {
                    return ['status' => 2, 'msg' => '行程码短信-'. $high_xcm_res['msg']];
                }
                if($high_xcm_res['data']['value'] == 2) {
                    $xcm_result = 9;
                }
            }
            $data['xcm_result'] = (string)$xcm_result;
            $data['ryxx_cc'] = $userInfo['ryxx_cc']; // 原先的颜色,user表里
            $data['ryxx_result'] = $declare['ryxx_result']; // 申报里面的
            if($xcm_result == 2 && $declare['ryxx_result'] == ''){ // #无需管控
                $data['ryxx_result'] = '行程卡带星人员';
                $data['ryxx_cc'] = '黄';
            }
            if($xcm_result == 1 && $declare['ryxx_result'] == '行程卡带星人员'){
                $data['ryxx_result'] = '';
                $data['ryxx_cc'] = '绿';
            }
            if($xcm_result == 9 && $declare['ryxx_result'] == ''){
                $data['ryxx_result'] = '行程卡带星人员';
                $data['ryxx_cc'] = '红';
            }
            if($data['ryxx_result'] != ' 义乌场所码将于6月3日前融合，下次请用支付宝扫“金华防疫码”'){
                $data['ryxx_result'] .= ' 义乌场所码将于6月3日前融合，下次请用支付宝扫“金华防疫码”';
            }
            $declare->save($data);
            $data['xcm_gettime']= date('Y-m-d H:i:s');
            app()->make(UserDao::class)->update(['id_card'=> $declare['id_card']], $data);

            $data['xcm_time_diff'] = 86400000;
            return ['status' => 1, 'msg' => '成功', 'data'=>  $data];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }
    public function xcmResultForQrcodeService($param)
    {
        // $declare = $this->dao->get(['sign' => $param['sign']]);
        // if($declare == null) {
        //     return ['status' => 0, 'msg' => '该申报不存在'];
        // }
        $city_code = app()->make(RiskDistrictDao::class)->getCityCodeConcat();
        $ssjptTool = new SsjptTool();
        $xcm_res =  $ssjptTool->skxcmjk($param['phone'], $param['sms_code'], $city_code);
        if($xcm_res['status'] == 0) {
            return ['status' => 0, 'msg' => '行程码短信-'. $xcm_res['msg']];
        }else if($xcm_res['status'] == 2) {
            return ['status' => 2, 'msg' => '行程码短信-'. $xcm_res['msg']];
        }
        try {
            $data['xcm_result'] = $xcm_res['data']['value'];
            // if($xcm_res['data']['value'] == 2 && $declare['ryxx_result'] == '无需管控'){
            //     $data['ryxx_result'] = '行程卡带星人员';
            // }
            // $declare->save($data);
            $data['xcm_gettime']= date('Y-m-d H:i:s');
            app()->make(UserDao::class)->update(['id_card'=> $param['id_card']], $data);
            $data['xcm_time_diff'] = 86400000;
            return ['status' => 1, 'msg' => '成功', 'data'=>  $data];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }
    public function myQrcodeResultService($userInfo){
        $param['real_name'] = $userInfo['real_name'];
        $param['id_card'] = $userInfo['id_card'];
        $info = app()->make(PlaceServices::class)->placeDeclareTaskService($param); // 同步去获取
        if($info){
            // 重新组装，尽量精简
            $newinfo['id_card'] = $userInfo['id_card'];
            $newinfo['real_name'] = $userInfo['real_name'];
            $newinfo['phone'] = $userInfo['phone'];
            // $newinfo['vaccination_times'] = $info['vaccination_times'];
            // $newinfo['jkm_time'] = $info['jkm_time'];
            $newinfo['jkm_mzt'] = $info['jkm_mzt'];
            $newinfo['hsjc_time'] = $info['hsjc_time'];
            $newinfo['hsjc_result'] = $info['hsjc_result'];
            // // $newinfo['hsjc_jcjg'] = $info['hsjc_jcjg'];
            // $newinfo['xcm_result'] = $userInfo['xcm_result'];
            $newinfo['ryxx_result'] = $info['ryxx_result'];
            $newinfo['ryxx_cc'] = $info['ryxx_cc'];
            // 行程卡
            if($userInfo['xcm_gettime'] == null || (time() - strtotime($userInfo['xcm_gettime']) > 86400)) { // 获取超过24小时
                $newinfo['xcm_result'] = 0;
            }else{
                $newinfo['xcm_result'] = $userInfo['xcm_result'];
            }
            $qrcode_str = '';
            $qrcode_str = 'UK'.base64_encode( json_encode($newinfo) ).randomCode(2);
            return ['status' => 1, 'msg' => '成功', 'data'=> ['info'=>$newinfo,'qrcode_str'=>$qrcode_str] ];
        }else{
            return ['status' => 0, 'msg' => '查询失败'];
        }
    }

    public function myQrcodeResultServiceV2($userInfo){
        $param['real_name'] = $userInfo['real_name'];
        $param['id_card'] = $userInfo['id_card'];

        $sign = md5('gate|'. $userInfo['id_card'] .'|'. microtime(true));
        
        $param['sign'] = $sign;
        $info = app()->make(PlaceServices::class)->placeDeclareTaskService($param); // 同步去获取
        // SwooleTaskService::place()->taskType('place')->data(['action'=>'placeDeclareTaskService','param'=> ['real_name' => $userInfo['real_name'], 'id_card' => $userInfo['id_card'], 'sign'=> $sign]])->push();
        // 重新组装，尽量精简
        $newinfo['id_card'] = $userInfo['id_card'];
        $newinfo['real_name'] = $userInfo['real_name'];
        $newinfo['phone'] = $userInfo['phone'];
        // $newinfo['vaccination_times'] = $info['vaccination_times'];
        // $newinfo['jkm_time'] = $info['jkm_time'];
        $newinfo['jkm_mzt'] = $info['jkm_mzt'];
        $newinfo['hsjc_time'] = $info['hsjc_time'];
        $newinfo['hsjc_result'] = $info['hsjc_result'];
        // // $newinfo['hsjc_jcjg'] = $info['hsjc_jcjg'];
        // $newinfo['xcm_result'] = $userInfo['xcm_result'];
        $newinfo['ryxx_result'] = $info['ryxx_result'];
        $newinfo['ryxx_cc'] = $info['ryxx_cc'];
        // 行程卡
        if($userInfo['xcm_gettime'] == null || (time() - strtotime($userInfo['xcm_gettime']) > 86400)) { // 获取超过24小时
            $newinfo['xcm_result'] = 0;
        }else{
            $newinfo['xcm_result'] = $userInfo['xcm_result'];
        }
        $qrcode_str = '';
        // $qrcode_str = 'UK'.base64_encode( json_encode($newinfo) ).randomCode(2);
        $qrcode_str = $sign;
        return ['status' => 1, 'msg' => '成功', 'data'=> ['info'=>$newinfo,'qrcode_str'=>$qrcode_str] ];
    }

    public function hsjcResultLogService($param, $user)
    {
        if($param['id_card'] != '') {
            $user = app()->make(UserDao::class)->get(['id_card'=> $param['id_card']]);
        }
        $userHsjcLogDao = app()->make(UserHsjcLogDao::class);
        $hsjc_list = $userHsjcLogDao->getSixRecentList($user['id_card']);
        if(count($hsjc_list) <= 3) {
            $skhl_list = Db::connect('mysql_shengku')->table('dsc_jh_dm_037_pt_patientinfo_sc_delta_new')->where('sfzh','=',$user['id_card'])->order('checktime','desc')->limit(6)->select()->toArray();
            foreach($skhl_list as $v) {
                $has = 0;
                foreach($hsjc_list as $n) {
                    if($n['hsjc_time'] == $v['checktime']) {
                        $has = 1;
                    }
                }
                if($has == 1) {
                    continue;
                }
                $hsjc_log_data = [
                    'card_type' => 'id',
                    'id_card' => $user['id_card'],
                    'real_name' => $user['real_name'],
                    'hsjc_time' => $v['checktime'],
                    'hsjc_result' => $v['result'],
                    'hsjc_jcjg' => $v['jgmc'],
                    'hsjc_date' => date('Y-m-d', strtotime($v['checktime'])),
                    'create_datetime' => date('Y-m-d H:i:s'),
                ];
                $userHsjcLogDao->save($hsjc_log_data);
            }
            $hsjc_list = $userHsjcLogDao->getSixRecentList($user['id_card']);
        }
        return ['status'=> 1, 'msg'=> '成功', 'data'=> $hsjc_list];
    }

    private function _ryxx_more($ryxx_result,$userInfo){
        $return = null;
        if($ryxx_result != ''){
            $info = Db::name('yw_rygk')->where('idcard','=',$userInfo['id_card'])->find();
            if($info){
                if($info['town'] != '' ){
                    $return[] = '镇街：'.$info['town'];
                }
                if($info['village'] != '' ){
                    $return[] = '村社：'.$info['village'];
                }
                if($info['company_name'] != '' ){
                    $return[] = '企业名称：'.$info['company_name'];
                }
                if($info['lxname'] != ''){
                    $return[] = '联系人：'.$info['lxname'];
                }
                if($info['lxphone'] != ''){
                    $return[] = '联系电话：'.$info['lxphone'];
                }
                if($info['person_classification'] != ''){
                    $return[] = '人员类型：'.$info['person_classification'];
                }
                if($info['frequency'] > 0){
                    $return[] = '检测频次：'.$info['frequency'].'天';
                }
                if($info['source'] != ''){
                    $return[] = '来源：'.$info['source'];
                }
            }
        }
        return $return;
    }

    public function reverseScanService($param, $user_id)
    {
        $place = app()->make(PlaceDao::class)->get(['code'=> $param['place_code']]);
        if($place == null) {
            return ['status' => 0, 'msg' => '场所码不存在'];
        }
        $user_admin = app()->make(UserManagerDao::class)->get(['user_id'=> $user_id]);
        if($user_admin == null) {
            return ['status' => 0, 'msg' => '您没有权限反扫场所码'];
        }
        $yesterday_scan_num = $this->dao->getYesterdayScanNum($param['place_code']);
        if($yesterday_scan_num > 100) {
            $yesterday_scan_num = '大于100';
        }
        $scan_num = $this->dao->getTodayScanNum($param['place_code']);
        if($scan_num > 100) {
            $scan_num = '大于100';
        }
        $data = [
            'id'=> $place['id'],
            'code'=> $place['code'],
            'name'=> $place['name'],
            'short_name'=> $place['short_name'],
            'link_man'=> $place['link_man'],
            'link_phone'=> $place['link_phone'],
            'yw_street'=> $place['yw_street'],
            'addr'=> $place['addr'],
            'lng'=> $place['lng'],
            'lat'=> $place['lat'],
            'scan_num'=> $scan_num,
            'yesterday_scan_num'=> $yesterday_scan_num,
        ];
        return ['status' => 1, 'msg' => '成功', 'data'=> $data];
    }
}
