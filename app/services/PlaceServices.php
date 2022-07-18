<?php

namespace app\services;

use app\dao\PlaceDao;
use app\dao\UserDao;
use app\dao\PlaceDeclareDao;
use app\dao\PlaceDeclareDateNumsDao;
use app\dao\PlaceDeclareHourNumsDao;
use think\facade\Db;
use \behavior\WechatAppletTool;
use Curl\Curl;
use think\facade\Config;
use app\services\SgzxServices;
use app\services\FysjServices;
use app\dao\UserHsjcLogDao;
use app\model\Place;
use app\model\UserHsjcLog;
use behavior\PubTool;

//场所码异步任务服务
class PlaceServices
{
    //场所码申报任务
    public function placeDeclareTaskService($param)
    {
        try{
            $user = app()->make(UserDao::class)->get(['id_card'=> $param['id_card']]);
            if($user == null) {
                return false;
            }
            $declare_data = [];
            if(isset($param['sign'])){
                $user_data = [
                    'csmsb_sign'=> $param['sign'], //最近一次场所码申报签名
                ];
            }else{
                $user_data = [];
            }
            $fysjService = app()->make(FysjServices::class);
            // if($user['jkm_gettime'] == null || (time() - strtotime($user['jkm_gettime']) > 30) || $user['jkm_mzt'] == '查询失败' || $user['jkm_mzt'] == '黄码' || $user['jkm_mzt'] == '红码' || $user['ryxx_result'] !='' ) { // 获取超过24小时
            if($user['jkm_gettime'] == null || (time() - strtotime($user['jkm_gettime']) > 10) || $user['jkm_mzt'] == '查询失败' ) { // #无需管控  获取超过24小时
                // $jkm_res = $fysjService->getJkmService($param['id_card']);
                $jkm_res = $fysjService->getJkmActualService($user['id_card'],$user['phone']);
                $declare_data['jkm_time'] = $jkm_res['jkm_time'];
                $declare_data['jkm_mzt'] = $jkm_res['jkm_mzt'];
                $declare_data['jkm_get'] = 1;
                $user_data['jkm_time'] = $jkm_res['jkm_time'];
                $user_data['jkm_mzt'] = $jkm_res['jkm_mzt'];
                $user_data['jkm_gettime'] = date('Y-m-d H:i:s');
                
            }else{
                $declare_data['jkm_time'] = $user['jkm_time'];
                $declare_data['jkm_mzt'] = $user['jkm_mzt'];
                $declare_data['jkm_get'] = 1;
                $declare_data['ryxx_result'] = $user['ryxx_result'];
            }


            // 核酸检测情况
            if($user['hsjc_gettime'] == null || (time() - strtotime($user['hsjc_gettime']) > 60) || $user['hsjc_result'] == '查询失败') { // 获取超过2小时
                $shsjc_res = $fysjService->getShsjcService($param['real_name'], $param['id_card']);
                if($shsjc_res['hsjc_time'] == null) {
                    // $ywhsjc_res = $fysjService->getYwhsjcService($param['real_name'], $param['id_card']);
                    // $declare_data['hsjc_time'] = $ywhsjc_res['hsjc_time'] ? date('Y-m-d H:i:s', strtotime($ywhsjc_res['hsjc_time'])) : null;
                    // $declare_data['hsjc_result'] = $ywhsjc_res['hsjc_result'];
                    // $declare_data['hsjc_jcjg'] = $ywhsjc_res['hsjc_jcjg'];
                    // $declare_data['hsjc_get'] = 1;
                    // $user_data['hsjc_time'] = $declare_data['hsjc_time'];
                    // $user_data['hsjc_result'] = $declare_data['hsjc_result'];
                    // $user_data['hsjc_jcjg'] = $declare_data['hsjc_jcjg'];
                    // $user_data['hsjc_gettime'] = date('Y-m-d H:i:s');
                    $declare_data['hsjc_time'] = $shsjc_res['hsjc_time'];
                    $declare_data['hsjc_result'] = $shsjc_res['hsjc_result'];
                    $declare_data['hsjc_jcjg'] = $shsjc_res['hsjc_jcjg'];
                    $declare_data['hsjc_get'] = 1;
                    $user_data['hsjc_gettime'] = date('Y-m-d H:i:s');
                    $user_data['hsjc_time'] = $user['hsjc_time']; // 不能删，下面有用

                }else{

                    // receive_time 目前不是真正的采样时间
                    // if($shsjc_res['receive_time']){
                    //     $user_data['receive_time'] = $shsjc_res['receive_time'];
                    // }
                    if( strtotime($user['hsjc_time']) >=  strtotime($shsjc_res['hsjc_time']) ){
                        // 如果用户的核酸时间比省库里的还要新，就取原先用户的
                        $declare_data['hsjc_time'] = $user['hsjc_time'];
                        $declare_data['hsjc_result'] = $user['hsjc_result'];
                        $declare_data['hsjc_jcjg'] = $user['hsjc_jcjg'];
                        $declare_data['hsjc_get'] = 1;
                        $user_data['hsjc_gettime'] = date('Y-m-d H:i:s');
                        $user_data['hsjc_time'] = $user['hsjc_time'];
                    }else{
                        $declare_data['hsjc_time'] = $shsjc_res['hsjc_time'] ? date('Y-m-d H:i:s', strtotime($shsjc_res['hsjc_time'])) : null;
                        $declare_data['hsjc_result'] = $shsjc_res['hsjc_result'];
                        $declare_data['hsjc_jcjg'] = $shsjc_res['hsjc_jcjg'];
                        $declare_data['hsjc_get'] = 1;
                        $user_data['hsjc_time'] = $declare_data['hsjc_time'];
                        $user_data['hsjc_result'] = $declare_data['hsjc_result'];
                        $user_data['hsjc_jcjg'] = $declare_data['hsjc_jcjg'];
                        $user_data['hsjc_gettime'] = date('Y-m-d H:i:s');
                    }
                }
                if( isset($user_data['hsjc_time']) && $user_data['hsjc_time'] && $user['hsjc_time'] && $user_data['hsjc_time'] != $user['hsjc_time'] && strtotime($user_data['hsjc_time']) > strtotime($user['hsjc_time']) ) { //先前一次核酸检测时间
                    $user_data['hsjc_previous_time'] = $user['hsjc_time'];
                    $hsjc_log_data = [
                        'card_type' => 'id',
                        'id_card' => $user['id_card'],
                        'real_name' => $user['real_name'],
                        'hsjc_time' => $declare_data['hsjc_time'],
                        'hsjc_result' => $declare_data['hsjc_result'],
                        'hsjc_jcjg' => $declare_data['hsjc_jcjg'],
                        'hsjc_date' => date('Y-m-d', strtotime($declare_data['hsjc_time'])),
                        'create_datetime' => date('Y-m-d H:i:s'),
                    ];
                    app()->make(UserHsjcLogDao::class)->save($hsjc_log_data);
                }
            }else{
                $declare_data['hsjc_time'] = $user['hsjc_time'];
                $declare_data['hsjc_result'] = $user['hsjc_result'];
                $declare_data['hsjc_jcjg'] = $user['hsjc_jcjg'];
                $declare_data['hsjc_get'] = 1;
            }
            if($user['vaccination_gettime'] == null || (time() - strtotime($user['vaccination_gettime']) > 86400)) {
                $ymjz = $fysjService->getXgymyfjzService($param['id_card']);
                $declare_data['vaccination'] = $ymjz['vaccination'];
                $declare_data['vaccination_date'] = $ymjz['vaccination_date'];
                $declare_data['vaccination_times'] = $ymjz['vaccination_times'];
                $declare_data['xgymjz_get'] = 1;
                if($ymjz['vaccination'] == 1) {
                    $user_data['vaccination'] = $ymjz['vaccination'];
                    $user_data['vaccination_date'] = $ymjz['vaccination_date'];
                    $user_data['vaccination_times'] = $ymjz['vaccination_times'];
                }
                $user_data['vaccination_gettime'] = date('Y-m-d H:i:s');
            }else{
                $declare_data['vaccination'] = $user['vaccination'];
                $declare_data['vaccination_date'] = $user['vaccination_date'];
                $declare_data['vaccination_times'] = $user['vaccination_times'];
                $declare_data['xgymjz_get'] = 1;
            }

            // 人员管控情况
            $ryxx_res = $fysjService->getRyxxServiceV2($param['id_card'],$user);
            $ryxx_type = $ryxx_res['ryxx_result'];
            $ryxx_cc = isset($ryxx_res['ryxx_cc']) ? $ryxx_res['ryxx_cc'] : '绿';
            // if(!isset($ryxx_res['ryxx_cc']) && $ryxx_type != '无需管控' && $ryxx_cc == '绿'){
            //     $ryxx_cc = '黄'; // v1的临时补丁 #无需管控
            // }
            if($ryxx_type == '' || $ryxx_type == '行程卡带星人员') {
                // #无需管控
                if($declare_data['jkm_mzt'] == '黄码') {
                    $ryxx_type = '黄码人员';
                    $ryxx_cc = '黄';
                }
                if($declare_data['jkm_mzt'] == '红码') {
                    $ryxx_type = '红码人员';
                    $ryxx_cc = '红';
                }
            }
            $declare_data['ryxx_result'] = $ryxx_type;
            $user_data['ryxx_result'] = $ryxx_type;
            $user_data['ryxx_cc'] = $ryxx_cc;
            $user_data['ryxx_gettime'] = date('Y-m-d H:i:s');
            // 如果是未按核酸检测的人，则重新核验一下当前的核酸检测情况
            if( strstr($user_data['ryxx_result'],'核酸')){
                if( isset($ryxx_res['rygk']['frequency']) && $ryxx_res['rygk']['frequency'] > 0){
                    // 核验本次核酸，是否真的未按时做
                    if( (time() - strtotime($declare_data['hsjc_time'])) < $ryxx_res['rygk']['frequency']*24*3600 ){
                        // 没有真的未做核酸
                        $declare_data['ryxx_result'] = '';
                        $user_data['ryxx_result'] = '';
                        $user_data['ryxx_cc'] = '绿';
                    }
                }
            }
            $declare_data['ryxx_result'] .= ' 义乌场所码将于6月3日前融合，下次请用支付宝扫“金华防疫码”';
            if(isset($param['place_declare_id'])){
                // 当前服务给场所码服务时,因为存在其他场景，如亮码场景使用
                app()->make(PlaceDeclareDao::class)->update($param['place_declare_id'], $declare_data);
            }
            $user->save($user_data);
            // 
            $declare_data['ryxx_cc'] = $user_data['ryxx_cc']; // 场所
            return $declare_data; // 当前服务给亮码场景使用时用
        }catch(\Exception $e) {
            test_log('placeDeclareTaskService error:'. $e->getMessage());
            return false;
        }
    }

    // public function verifyIdCardService($real_name, $id_card, $place_declare_id)
    // {
    //     try{
    //         $placeDeclareDao = app()->make(PlaceDeclareDao::class);
    //         //人口库查询
    //         $rkk_res = app()->make(SgzxServices::class)->rkkService($real_name, $id_card);
    //         $data = [];
    //         if($rkk_res['status'] == 1) {
    //             $data['nation'] = $rkk_res['data']['nation'];
    //             $data['permanent_address'] = $rkk_res['data']['permanent_address'];
    //             $data['id_verify_result'] = 1;
    //             $placeDeclareDao->update($place_declare_id, ['id_verify_result'=> 1, 'age'=> IdentityCardTool::getAge($id_card)]);
    //         }else{ //人口库验证失败，不保存更新用户信息
    //             $data['id_verify_result'] = 2;
    //             $placeDeclareDao->update($place_declare_id, ['id_verify_result'=> 2]);
    //         }
            
    //         app()->make(UserDao::class)->update(['id_card'=> $id_card], $data);
    //         return true;
    //     }catch(\Exception $e) {
    //         test_log('placeDeclareTask verifyIdCardService error:'. $e->getMessage());
    //     }
    // }

    // public function getJkmService($id_card)
    // {
    //     try{
    //         $jkm_time = null;
    //         $jkm_mzt = '查询失败';
    //         //全省健康码基本信息查询
    //         //$jkm_res = app()->make(SgzxServices::class)->qsjkmxxcx($id_card);
    //         $ssjptTool = new SsjptTool();
    //         $jkm_res =  $ssjptTool->skIdcardToJkm($id_card);
    //         if($jkm_res['status'] == 1) {
    //             $jkm_time = isset($jkm_res['data']['zjgxsj']) ? date('Y-m-d H:i:s', strtotime($jkm_res['data']['zjgxsj'])) : null;
    //             $jkm_mzt = isset($jkm_res['data']['mzt']) ? $jkm_res['data']['mzt'] : '查询成功-数据为空' ;

    //             // 2022.01.31 按照码发放地：金华市，更新时间为近10天的条件来判断为有效的红黄码，其他的红黄码都设置为绿码
    //             if( isset($jkm_res['data']['mffd']) && $jkm_res['data']['mffd'] == '金华市'){
    //                 if($jkm_mzt == '黄码') {
    //                     // 查看，是不是超过10天，如果是目前先改为绿色
    //                     if( time() - strtotime($jkm_time) > 10*24*3600){
    //                         $jkm_mzt = '绿码';
    //                     }
    //                 }else if($jkm_mzt == '红码') {
    //                     // 查看，是不是超过10天，如果是目前先改为绿色
    //                     if( time() - strtotime($jkm_time) > 10*24*3600){
    //                         $jkm_mzt = '绿码';
    //                     }
    //                 }
    //             }else{
    //                 // 其他地区
    //                 if($jkm_mzt == '黄码') {

    //                 }else if($jkm_mzt == '红码') {
    //                     system_error_log(__METHOD__,$id_card.'红码转绿码',$id_card.'的红码时间为:'.$jkm_time);
    //                 }
    //                 $jkm_mzt = '绿码';
    //             }

    //             // if($jkm_mzt == '黄码') {
    //             //     // 查看，是不是超过60天，如果是目前先改为绿色
    //             //     if( time() - strtotime($jkm_time) > 30*24*3600){
    //             //         $jkm_mzt = '绿码';
    //             //     }
    //             // }else if($jkm_mzt == '红码') {
    //             //     // 查看，是不是超过45天，如果是目前先改为绿色
    //             //     if( time() - strtotime($jkm_time) > 3888000){
    //             //         $jkm_mzt = '绿码';
    //             //     }
    //             // }
    //         }
    //         return ['jkm_time'=> $jkm_time, 'jkm_mzt'=> $jkm_mzt];
    //     } catch (\Exception $e){
    //         test_log('placeDeclareTask getJkmService error:'. $e->getMessage());
    //     }
    // }



    // public function getYwhsjcService($real_name, $id_card)
    // {
    //     try{
    //         $hsjc_result = '查询失败';
    //         $hsjc_time = null;
    //         $hsjc_jcjg = '';
    //         // 省核酸检测接口
    //         $hsjc_res = app()->make(SgzxServices::class)->ywhsjcjk($real_name, $id_card);
    //         if($hsjc_res['status'] == 1) {
    //             if(isset($hsjc_res['data']) && count($hsjc_res['data']) > 0){
    //                 foreach($hsjc_res['data'] as $n) {
    //                     if(strstr($n['Exam_Name'], 'RNA') || strstr($n['Exam_Name'], '核酸')){
    //                         if($n['Exam_Time'] > $hsjc_time) {
    //                             $hsjc_result = $n['Exam_Result'];
    //                             $hsjc_time = Date('Y-m-d H:i:s',strtotime($n['Exam_Time']));
    //                             $hsjc_jcjg = $n['Exam_Org'];
    //                         }
    //                     }
    //                 }
    //             }else{
    //                 $hsjc_result = '查询成功-数据为空';
    //             }
    //         }
    //         return ['hsjc_result'=> $hsjc_result, 'hsjc_time'=> $hsjc_time, 'hsjc_jcjg'=> $hsjc_jcjg];
    //     } catch (\Exception $e){
    //         test_log('placeDeclareTask getYwhsjcService error:'. $e->getMessage());
    //     }
    // }

    // public function getXgymyfjzService($id_card)
    // {
    //     try{
    //         $vaccination = 0;
    //         $vaccination_date = null;
    //         $vaccination_times = 0;
    //         //省新冠疫苗预防接种信息查询
    //         //$xgym_res = app()->make(SgzxServices::class)->sxgymyfjzxxcx($id_card);
    //         $ssjptTool = new SsjptTool();
    //         $xgym_res =  $ssjptTool->skxgymyfjzxxcx($id_card);
    //         if($xgym_res['status'] == 1) {
    //             foreach($xgym_res['data'] as $n) {
    //                 if($n['vaccinationTime'] > $vaccination_times) {
    //                     $vaccination = 1;
    //                     $vaccination_date = $n['vaccinationDateTime'];
    //                     $vaccination_times = $n['vaccinationTime'];
    //                 }
    //             }
    //         }
    //         return ['vaccination'=> $vaccination, 'vaccination_date'=> $vaccination_date, 'vaccination_times'=> $vaccination_times];
    //     } catch (\Exception $e){
    //         test_log('placeDeclareTask getXgymyfjzService error:'. $e->getMessage());
    //     }
    // }

    public function getRyxxService($id_card)
    {
        //$ryxx_res = app()->make(ZzsbViewServices::class)->csm_ryxx($id_card);
        $ryxx_res = Db::name('csm_ryxx')->where('idcard', '=', $id_card)->find();
        try {
            if($ryxx_res == null) {
                $ryxx_type = ''; // #无需管控
            }else{
                $ryxx_type = $ryxx_res['type'];
            }
            return ['ryxx_result' => $ryxx_type];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }

    public function buildAppletCodeAndGetImageTask($param){
        $code = randomCode(12);
        $wechatAppletTool = new WechatAppletTool();
        $applet_qrcode = $wechatAppletTool->appletPlaceQrcode($code, $param['short_name']);
        if($applet_qrcode['status'] == 0) {
            test_log('buildAppletCodeAndGetImageTask error:'.$applet_qrcode['msg']);
            return ['status' => 0, 'msg' => '更新成功-'. $applet_qrcode['msg']];
        }
        $data = [
            'code' => $code,
            'applet_qrcode' => $applet_qrcode['data'],
        ];
        try {
            $placeDao = app()->make(PlaceDao::class);
            $placeDao->update($param['id'],$data);
            return ['status' => 1, 'msg' => '更新成功'];
        } catch (\Exception $e) {
            test_log('buildAppletCodeAndGetImageTask error:'.$e->getMessage());
            return ['status' => 0, 'msg' => '更新成功-'. $e->getMessage()];
        }
    }



    public function userQrcodeTaskService($param)
    {
        $url = 'http://localhost:30399/buildAppletCodeByUserId?uniqid='. $param['uniqid'] .'&qrcode_color='. $param['qrcode_color'];
        $curl = new Curl();
        $curl->get($url);
        if($curl->error) {
            return ['status'=> 0, 'msg'=> '微信获取用户手机号失败'];
        }else{
            $result = json_decode($curl->response, true);
        }
        //app()->make(UserDao::class)->update(['uniqid'=> $param['uniqid']], ['vaccination'=> 1]);

    }

    public function placeQrcodeWatermarkService($param)
    {
        $url = '';
        $at_server = Config::get('upload.at_server');
        if($at_server == 'dev') { //测试服务器
            $this->placeQrcodeWatermarkHandleService($param['file_path'], $param['version'], $param['short_name']);
            //$url = 'https://ldrk.jk-kj.com/wx_qrcode/watermark';
        }else{
            if(strstr($param['file_path'], 'server112')) {
                if($at_server == 'server112') { //本服务器
                    $this->placeQrcodeWatermarkHandleService($param['file_path'], $param['version'], $param['short_name']);
                }else{
                    $url = 'http://172.45.4.112:30399/wx_qrcode/watermark';
                }
            }else if(strstr($param['file_path'], 'server118')) {
                if($at_server == 'server118') { //本服务器
                    $this->placeQrcodeWatermarkHandleService($param['file_path'], $param['version'], $param['short_name']);
                }else{
                    $url = 'http://172.45.4.118:30399/wx_qrcode/watermark';
                }
            }else if(strstr($param['file_path'], 'server114')) {
                if($at_server == 'server114') { //本服务器
                    $this->placeQrcodeWatermarkHandleService($param['file_path'], $param['version'], $param['short_name']);
                }else{
                    $url = 'http://172.45.4.101:30399/wx_qrcode/watermark';
                }
            }else if(strstr($param['file_path'], 'server96')) {
                if($at_server == 'server96') { //本服务器
                    $this->placeQrcodeWatermarkHandleService($param['file_path'], $param['version'], $param['short_name']);
                }else{
                    $url = 'http://172.45.253.96:30399/wx_qrcode/watermark';
                }
            }else if(strstr($param['file_path'], 'server97')) {
                if($at_server == 'server97') { //本服务器
                    $this->placeQrcodeWatermarkHandleService($param['file_path'], $param['version'], $param['short_name']);
                }else{
                    $url = 'http://172.45.253.97:30399/wx_qrcode/watermark';
                }
            }else if(strstr($param['file_path'], 'server95')) {
                if($at_server == 'server95') { //本服务器
                    $this->placeQrcodeWatermarkHandleService($param['file_path'], $param['version'], $param['short_name']);
                }else{
                    $url = 'http://172.45.253.95:30399/wx_qrcode/watermark';
                }
            }
        }
        if($url == '') {
            return true;
        }
        $curl = new Curl();
        $data = [
            'file_path' => $param['file_path'],
            'version' => $param['version'],
            'name' => $param['short_name'],
        ];
        $curl->post($url, $data);
        if($curl->error) {
            test_log('跨服务器生成微信小程序码失败-'. $curl->error .': '. $curl->error_message);
        }
        //test_log('生成微信小程序码-'. json_encode($curl->response));
        return true;
    }
    
    public function placeQrcodeWatermarkHandleService($file_path, $version, $name)
    {
        try{
            $at_server = Config::get('upload.at_server');
            // test_log( 'placeQrcodeWatermarkHandleService at_server:'.$at_server.' file_path：'.$file_path.' version:'.$version .' name:'.$name);

            $file_path = app()->getRootPath() .'public'. $file_path;
            $watermark_file_path = str_replace('_qrcode.png', '_qrcode_'. $version .'.png', $file_path);
            $dst_path = app()->getRootPath() .'public/file/image/wxqrcode_bg'. $version .'.png'; // 背景图
            if($version == 'v1') {
                $this->wxQrcodeMerge($file_path, $watermark_file_path, $dst_path, 89.5, 85);
                $this->wxQrcodeText($watermark_file_path, $watermark_file_path, $name, 186, 735);
            }else if($version == 'v2') {
                $this->wxQrcodeMerge($file_path, $watermark_file_path, $dst_path, 160, 590);
                $this->wxQrcodeText($watermark_file_path, $watermark_file_path, $name, 235, 453);
            }
            
        } catch (\Exception $e) {
            test_log('微信小程序二维码加水印失败-'. $e->getMessage());
        }
    }
    
    //合并图像
    public function wxQrcodeMerge($fromfile, $tofile, $dst_path, $dst_x=0, $dst_y=0)
    {
        $src_path = $fromfile; // 二维码图
        //创建图片的实例
        $dst = imagecreatefromstring(file_get_contents($dst_path));
        $src = imagecreatefromstring(file_get_contents($src_path));
        // 把二维码图片的白色背景设为透明
        imagecolortransparent($src, imagecolorallocate($src, 255, 255, 255));
        //获取水印图片的宽高
        list($src_w, $src_h) = getimagesize($src_path);
        //var_dump(getimagesize($src_path));
        //将水印图片复制到目标图片上
        imagecopymerge($dst, $src, $dst_x, $dst_y, 0, 0, $src_w, $src_h, 100);
        //生成图片
        imagepng($dst, $tofile);
        //销毁
        imagedestroy($dst);
        imagedestroy($src);
    }

    //向不同格式的图片画一个字符串（也是文字水印）
    public function wxQrcodeText($fromfile, $tofile, $string, $x=0, $y=0)
    {
        //获取图片的属性，第一个宽度，第二个高度，类型1=>gif，2=>jpeg,3=>png
        list($width,$height,$type) = getimagesize($fromfile);
        //可以处理的图片类型
        $types = array(1=>"gif",2=>"jpeg",3=>"png",);
        //通过图片类型去组合，可以创建对应图片格式的，创建图片资源的GD库函数
        $createfrom = "imagecreatefrom".$types[$type];
        //通过“变量函数”去打对应的函数去创建图片的资源
        $image = $createfrom($fromfile);

        //设置居中字体的X轴坐标位置
        //$x = ($width-imagefontwidth(5)*strlen($string))/2;
        //设置居中字体的Y轴坐标位置
        //$y = ($height-imagefontheight(5))/1.18;
        $strlen = mb_strlen($string, 'utf8');
        if($strlen == 2 || $strlen == 3) {
            $x = $x + 80;
        }else if($strlen == 4 || $strlen == 5) {
            $x = $x + 40;
        }else if($strlen <= 8) {
            $x = $x;
        }else{
            $string = mb_substr($string, 0, 8);
        }

        //设置字体的颜色为黑色
        $textcolor = imagecolorallocate($image, 0, 0, 0);
        $font = app()->getRootPath() .'public/file/fonts/Songti.ttc'; //字体在服务器上的绝对路径

        imagefttext($image, 30, 0, $x, $y, $textcolor, $font, $string);
        //通过图片类型去组合保存对应格式的图片函数
        $output = "image".$types[$type];
        //通过变量函数去保存对应格式的图片
        $output($image,$tofile);
        imagedestroy($image);
    }

    public function taskPlaceDeclareDateNums($param=[]){
        if(isset($param['ymd'])){
            $ymd = $param['ymd'];
        }else{
            $ymd = Date('Y-m-d',strtotime(' -1 day'));
        }

        try{
            $create_time = time();
            $data = $this->getPreDayDateNums($ymd);
            // 
            foreach($data as $key2 => $value2){
                $value2['create_time'] = $create_time;
                Db::name('place_declare_date_nums')->save($value2);
            }
        } catch (\Exception $e) {
            test_log('taskPlaceDeclareDateNums-'. $e->getMessage());
        }

    }

    public function taskPlaceDeclareStreetHourNums($param=[]){
        if(isset($param['ymd'])){
            $ymd = $param['ymd'];
        }else{
            $ymd = Date('Y-m-d',strtotime(' -1 day'));
        }

        try{
            $create_time = time();
            $data = $this->getPreDayStreetHourNums($ymd);

            foreach($data as $key2 => $value2){
                $value2['create_time'] = $create_time;
                Db::name('place_declare_hour_nums')->save($value2);
            }
        } catch (\Exception $e) {
            test_log('taskPlaceDeclareStreetHourNums-'. $e->getMessage());
        }

    }

    private function getPreDayDateNums($ymd){
        $where = [];
        $where[] = ['create_date', '=', $ymd];
        // $where[] = ['yw_street', '=', $yw_street];

        return Db::connect('mysql_slave')->name('place_declare')
                ->field('
                    create_date as date,
                    yw_street,
                    place_code,
                    place_name,
                    count(id) as total_nums
                ')
                ->where($where)
                ->group('place_code')
                ->select()
                ->toArray();
    }

    public function taskFeedDateNums($param)
    {
        try{
            // $date = Date('Y-m-d',strtotime(' -1 day'));
            if(isset($param['ymd'])){
                $ymd = $param['ymd'];
            }else{
                $ymd = Date('Y-m-d',strtotime(' -1 day'));
            }

            $placeDeclareDateNumsDao = app()->make(PlaceDeclareDateNumsDao::class);
     
            $index = 1;
            $from_id = 0;
            while($index == 1) {
                $list = Db::connect('mysql_slave')->name('place_declare_date_nums')
                            ->field('id,place_code')
                            ->where('jkm_green_nums','=',0) // 一般这个都为0
                            ->where('id','>',$from_id)
                            ->where('date','=',$ymd)
                            ->limit(1000)->select()->toArray();

                if(count($list) == 0) {
                    $index = 0;
                }else{
                    $num = count($list);
                    foreach($list as $k => $v) {
                        $res[$k] = Db::connect('mysql_slave')->name('place_declare')->alias('pd')
                                    ->field('
                                        p.place_classify,
                                        p.place_type,
                                        SUM( if(jkm_mzt="绿码",1,0) ) as jkm_green_nums,
                                        SUM( if(jkm_mzt="黄码",1,0) ) as jkm_yellow_nums,
                                        SUM( if(jkm_mzt="红码",1,0) ) as jkm_red_nums,
                                        SUM( if(jkm_mzt<>"绿码" and jkm_mzt<>"黄码" and jkm_mzt<>"红码",1,0) ) as jkm_unknow_nums,
                                        SUM( if(xcm_result="1",1,0) ) as xcm_green_nums,
                                        SUM( if(xcm_result="2",1,0) ) as xcm_yellow_nums,
                                        SUM( if(xcm_result="9",1,0) ) as xcm_red_nums,
                                        SUM( if(xcm_result="0" or xcm_result="3",1,0) ) as xcm_unknow_nums,
                                        SUM( if(vaccination_times="0",1,0) ) as vaccination_0_nums,
                                        SUM( if(vaccination_times="1",1,0) ) as vaccination_1_nums,
                                        SUM( if(vaccination_times="2",1,0) ) as vaccination_2_nums,
                                        SUM( if(vaccination_times="3",1,0) ) as vaccination_3_nums,
                                        SUM( if(ryxx_result="" or ryxx_result="无需管控",1,0) ) as ryxx_empty_nums,
                                        SUM( if(ryxx_result<>"" and ryxx_result<>"无需管控",1,0) ) as ryxx_notempty_nums,
                                        SUM( if(TRUNCATE(( pd.create_time-UNIX_TIMESTAMP(hsjc_time))/86400,2) < 1 ,1,0) ) as hsjc_1_day_nums,
                                        SUM( if(TRUNCATE(( pd.create_time-UNIX_TIMESTAMP(hsjc_time))/86400,2) < 2 ,1,0) ) as hsjc_2_day_nums,
                                        SUM( if(TRUNCATE(( pd.create_time-UNIX_TIMESTAMP(hsjc_time))/86400,2) < 3 ,1,0) ) as hsjc_3_day_nums,
                                        SUM( if(TRUNCATE(( pd.create_time-UNIX_TIMESTAMP(hsjc_time))/86400,2) < 7 ,1,0) ) as hsjc_7_day_nums,
                                        SUM( if(TRUNCATE(( pd.create_time-UNIX_TIMESTAMP(hsjc_time))/86400,2) < 14 ,1,0) ) as hsjc_14_day_nums,
                                        SUM( if(TRUNCATE(( pd.create_time-UNIX_TIMESTAMP(hsjc_time))/86400,2) >= 14 ,1,0) ) as hsjc_outer_14_day_nums,
                                        SUM( if(hsjc_result="查询失败",1,0) ) as hsjc_unknow_nums
                                    ')
                                    ->leftJoin('place p', 'p.code=pd.place_code')
                                    ->where('pd.place_code','=',$v['place_code'])
                                    ->where('pd.create_date','=',$ymd)
                                    ->group('pd.place_code')
                                    ->select()->toArray();
                        if($res[$k]){
                            $save[$k] = $res[$k][0];
                            $placeDeclareDateNumsDao->update($v['id'],$save[$k]);
                        }

                        if($num == $k + 1){
                            // test_log('本次最后一个id为:'.$v['id']);
                            $from_id = $v['id']; // 下一批的开始id
                        }

                    }
                }
            }
        }catch(\Exception $e) {
            test_log('taskFeedDateNums error:'. $e->getMessage());
        }
    }


    public function taskGroupDateNums($param)
    {
        try{
            // $date = Date('Y-m-d',strtotime(' -1 day'));
            if(isset($param['ymd'])){
                $ymd = $param['ymd'];
            }else{
                $ymd = Date('Y-m-d',strtotime(' -1 day'));
            }
            test_log('taskGroupDateNums');

            // 按街道group
            $list = Db::connect('mysql_slave')->name('place_declare_date_nums')
                        ->field('
                            date as date,
                            yw_street as title,
                            SUM(total_nums) as total_nums,
                            SUM(jkm_green_nums) as jkm_green_nums,
                            SUM(jkm_yellow_nums) as jkm_yellow_nums,
                            SUM(jkm_red_nums) as jkm_red_nums,
                            SUM(jkm_unknow_nums) as jkm_unknow_nums,
                            SUM(xcm_green_nums) as xcm_green_nums,
                            SUM(xcm_yellow_nums) as xcm_yellow_nums,
                            SUM(xcm_red_nums) as xcm_red_nums,
                            SUM(xcm_unknow_nums) as xcm_unknow_nums,
                            SUM(vaccination_0_nums) as vaccination_0_nums,
                            SUM(vaccination_1_nums) as vaccination_1_nums,
                            SUM(vaccination_2_nums) as vaccination_2_nums,
                            SUM(vaccination_3_nums) as vaccination_3_nums,
                            SUM(ryxx_empty_nums) as ryxx_empty_nums,
                            SUM(ryxx_notempty_nums) as ryxx_notempty_nums,
                            SUM(hsjc_1_day_nums) as hsjc_1_day_nums,
                            SUM(hsjc_2_day_nums) as hsjc_2_day_nums,
                            SUM(hsjc_3_day_nums) as hsjc_3_day_nums,
                            SUM(hsjc_7_day_nums) as hsjc_7_day_nums,
                            SUM(hsjc_14_day_nums) as hsjc_14_day_nums,
                            SUM(hsjc_outer_14_day_nums) as hsjc_outer_14_day_nums,
                            SUM(hsjc_unknow_nums) as hsjc_unknow_nums
                        ')
                        ->where('date','=',$ymd)
                        ->group('yw_street')
                        ->select()->toArray();

            foreach($list as $key => $value){
                $value['group_by'] = 'yw_street';
                Db::name('place_declare_date_nums_group')->insert($value);
            }
            


            // 按分类
            $list = Db::connect('mysql_slave')->name('place_declare_date_nums')
                        ->field('
                            date as date,
                            place_classify as title,
                            SUM(total_nums) as total_nums,
                            SUM(jkm_green_nums) as jkm_green_nums,
                            SUM(jkm_yellow_nums) as jkm_yellow_nums,
                            SUM(jkm_red_nums) as jkm_red_nums,
                            SUM(jkm_unknow_nums) as jkm_unknow_nums,
                            SUM(xcm_green_nums) as xcm_green_nums,
                            SUM(xcm_yellow_nums) as xcm_yellow_nums,
                            SUM(xcm_red_nums) as xcm_red_nums,
                            SUM(xcm_unknow_nums) as xcm_unknow_nums,
                            SUM(vaccination_0_nums) as vaccination_0_nums,
                            SUM(vaccination_1_nums) as vaccination_1_nums,
                            SUM(vaccination_2_nums) as vaccination_2_nums,
                            SUM(vaccination_3_nums) as vaccination_3_nums,
                            SUM(ryxx_empty_nums) as ryxx_empty_nums,
                            SUM(ryxx_notempty_nums) as ryxx_notempty_nums,
                            SUM(hsjc_1_day_nums) as hsjc_1_day_nums,
                            SUM(hsjc_2_day_nums) as hsjc_2_day_nums,
                            SUM(hsjc_3_day_nums) as hsjc_3_day_nums,
                            SUM(hsjc_7_day_nums) as hsjc_7_day_nums,
                            SUM(hsjc_14_day_nums) as hsjc_14_day_nums,
                            SUM(hsjc_outer_14_day_nums) as hsjc_outer_14_day_nums,
                            SUM(hsjc_unknow_nums) as hsjc_unknow_nums
                        ')
                        ->where('date','=',$ymd)
                        ->group('place_classify')
                        ->select()->toArray();

            // 分类列表
            $place_classify_list = app()->make(Place::class)->getPlaceClassifyList();

            foreach($list as $key => $value){
                $value['group_by'] = 'place_classify';
                if((string)$value['title'] == ''){
                    $value['title'] = '未知';
                }else{
                    $value['title'] = $place_classify_list[$value['title']];
                }
                Db::name('place_declare_date_nums_group')->insert($value);
            }

            // 按行业
            $list = Db::connect('mysql_slave')->name('place_declare_date_nums')
                        ->field('
                            date as date,
                            place_type as title,
                            SUM(total_nums) as total_nums,
                            SUM(jkm_green_nums) as jkm_green_nums,
                            SUM(jkm_yellow_nums) as jkm_yellow_nums,
                            SUM(jkm_red_nums) as jkm_red_nums,
                            SUM(jkm_unknow_nums) as jkm_unknow_nums,
                            SUM(xcm_green_nums) as xcm_green_nums,
                            SUM(xcm_yellow_nums) as xcm_yellow_nums,
                            SUM(xcm_red_nums) as xcm_red_nums,
                            SUM(xcm_unknow_nums) as xcm_unknow_nums,
                            SUM(vaccination_0_nums) as vaccination_0_nums,
                            SUM(vaccination_1_nums) as vaccination_1_nums,
                            SUM(vaccination_2_nums) as vaccination_2_nums,
                            SUM(vaccination_3_nums) as vaccination_3_nums,
                            SUM(ryxx_empty_nums) as ryxx_empty_nums,
                            SUM(ryxx_notempty_nums) as ryxx_notempty_nums,
                            SUM(hsjc_1_day_nums) as hsjc_1_day_nums,
                            SUM(hsjc_2_day_nums) as hsjc_2_day_nums,
                            SUM(hsjc_3_day_nums) as hsjc_3_day_nums,
                            SUM(hsjc_7_day_nums) as hsjc_7_day_nums,
                            SUM(hsjc_14_day_nums) as hsjc_14_day_nums,
                            SUM(hsjc_outer_14_day_nums) as hsjc_outer_14_day_nums,
                            SUM(hsjc_unknow_nums) as hsjc_unknow_nums
                        ')
                        ->where('date','=',$ymd)
                        ->group('place_type')
                        ->select()->toArray();

            foreach($list as $key => $value){
                $value['group_by'] = 'place_type';
                if((string)$value['title'] == ''){
                    $value['title'] = '未知';
                }
                Db::name('place_declare_date_nums_group')->insert($value);
            }

     
        }catch(\Exception $e) {
            test_log('taskGroupDateNums error:'. $e->getMessage());
        }
    }

    public function taskFeedHourNums($param)
    {
        try{
            // $date = Date('Y-m-d',strtotime(' -1 day'));
            if(isset($param['ymd'])){
                $ymd = $param['ymd'];
            }else{
                $ymd = Date('Y-m-d',strtotime(' -1 day'));
            }
    
            $placeDeclareHourNumsDao = app()->make(PlaceDeclareHourNumsDao::class);
     
            $yw_street_data = Db::name('district')->where('pid','=',2832)->select()->toArray();
            $yw_street_arr = array_column($yw_street_data,'name');



            foreach($yw_street_arr as $key => $yw_street){

                for($i=0;$i<24;$i++){

                    $hour = sprintf("%02d",$i);
    
                    $start_time = strtotime($ymd.' '.$hour.':00:00' );
                    $end_time = strtotime($ymd.' '.$hour.':59:59' );
    
                    $list[$key][$i] = Db::connect('mysql_slave')->name('place_declare')
                                ->field('
                                    DATE_FORMAT( FROM_UNIXTIME(create_time),"%H" ) AS  hour,
                                    yw_street,
                                    SUM( if(jkm_mzt="绿码",1,0) ) as jkm_green_nums,
                                    SUM( if(jkm_mzt="黄码",1,0) ) as jkm_yellow_nums,
                                    SUM( if(jkm_mzt="红码",1,0) ) as jkm_red_nums,
                                    SUM( if(jkm_mzt<>"绿码" and jkm_mzt<>"黄码" and jkm_mzt<>"红码",1,0) ) as jkm_unknow_nums,
                                    SUM( if(xcm_result="1",1,0) ) as xcm_green_nums,
                                    SUM( if(xcm_result="2",1,0) ) as xcm_yellow_nums,
                                    SUM( if(xcm_result="9",1,0) ) as xcm_red_nums,
                                    SUM( if(xcm_result="0" or xcm_result="3",1,0) ) as xcm_unknow_nums,
                                    SUM( if(vaccination_times="0",1,0) ) as vaccination_0_nums,
                                    SUM( if(vaccination_times="1",1,0) ) as vaccination_1_nums,
                                    SUM( if(vaccination_times="2",1,0) ) as vaccination_2_nums,
                                    SUM( if(vaccination_times="3",1,0) ) as vaccination_3_nums,
                                    SUM( if(ryxx_result="" or ryxx_result="无需管控",1,0) ) as ryxx_empty_nums,
                                    SUM( if(ryxx_result<>"" and ryxx_result<>"无需管控",1,0) ) as ryxx_notempty_nums,
                                    SUM( if(TRUNCATE(( create_time-UNIX_TIMESTAMP(hsjc_time))/86400,2) < 1 ,1,0) ) as hsjc_1_day_nums,
                                    SUM( if(TRUNCATE(( create_time-UNIX_TIMESTAMP(hsjc_time))/86400,2) < 2 ,1,0) ) as hsjc_2_day_nums,
                                    SUM( if(TRUNCATE(( create_time-UNIX_TIMESTAMP(hsjc_time))/86400,2) < 3 ,1,0) ) as hsjc_3_day_nums,
                                    SUM( if(TRUNCATE(( create_time-UNIX_TIMESTAMP(hsjc_time))/86400,2) < 7 ,1,0) ) as hsjc_7_day_nums,
                                    SUM( if(TRUNCATE(( create_time-UNIX_TIMESTAMP(hsjc_time))/86400,2) < 14 ,1,0) ) as hsjc_14_day_nums,
                                    SUM( if(TRUNCATE(( create_time-UNIX_TIMESTAMP(hsjc_time))/86400,2) >= 14 ,1,0) ) as hsjc_outer_14_day_nums,
                                    SUM( if(hsjc_result="查询失败",1,0) ) as hsjc_unknow_nums
                                ')
                                ->where('create_time','>=',$start_time)
                                ->where('create_time','<',$end_time)
                                ->where('create_date','=',$ymd)
                                ->where('yw_street','=',$yw_street)
                                ->group('hour')
                                ->select()->toArray();
    
                    foreach($list[$key][$i] as $key2 => $value2){
                        $placeDeclareHourNumsDao->update(['date'=>$ymd,'hour'=>$hour,'yw_street'=>$yw_street],$value2);
                    }
    
                }


            }

            
        }catch(\Exception $e) {
            test_log('taskFeedHourNums error:'. $e->getMessage());
        }
    }


    private function getPreDayStreetHourNums($ymd){
        $where = [];
        $where[] = ['create_date', '=', $ymd];
        // $where[] = ['yw_street', '=', $yw_street];

        return Db::connect('mysql_slave')->name('place_declare')
                ->field('
                    create_date as date,
                    DATE_FORMAT( FROM_UNIXTIME(create_time),"%H" ) AS  hour,
                    yw_street,
                    count(id) as total_nums
                ')
                ->where($where)
                ->group('hour,yw_street')
                ->select()
                ->toArray();
    }

    // SUM( if(jkm_mzt="绿码",1,0) ) as jkm_green_nums,
    // SUM( if(jkm_mzt="黄码",1,0) ) as jkm_yellow_nums,
    // SUM( if(jkm_mzt="红码",1,0) ) as jkm_red_nums,
    // SUM( if(jkm_mzt<>"绿码" and jkm_mzt<>"黄码" and jkm_mzt<>"红码",1,0) ) as jkm_unknow_nums,
    // SUM( if(xcm_result="1",1,0) ) as xcm_green_nums,
    // SUM( if(xcm_result="2",1,0) ) as xcm_yellow_nums,
    // SUM( if(xcm_result="9",1,0) ) as xcm_red_nums,
    // SUM( if(xcm_result="0" or xcm_result="3",1,0) ) as xcm_unknow_nums,
    // SUM( if(vaccination_times="0",1,0) ) as vaccination_0_nums,
    // SUM( if(vaccination_times="1",1,0) ) as vaccination_1_nums,
    // SUM( if(vaccination_times="2",1,0) ) as vaccination_2_nums,
    // SUM( if(vaccination_times="3",1,0) ) as vaccination_3_nums,
    // SUM( if(ryxx_result="" or ryxx_result="无需管控",1,0) ) as ryxx_empty_nums,
    // SUM( if(ryxx_result<>"" and ryxx_result<>"无需管控",1,0) ) as ryxx_notempty_nums,
    // SUM( if(TRUNCATE(( create_time-UNIX_TIMESTAMP(hsjc_time))/86400,2) < 1 ,1,0) ) as hsjc_1_day_nums,
    // SUM( if(TRUNCATE(( create_time-UNIX_TIMESTAMP(hsjc_time))/86400,2) < 2 ,1,0) ) as hsjc_2_day_nums,
    // SUM( if(TRUNCATE(( create_time-UNIX_TIMESTAMP(hsjc_time))/86400,2) < 3 ,1,0) ) as hsjc_3_day_nums,
    // SUM( if(TRUNCATE(( create_time-UNIX_TIMESTAMP(hsjc_time))/86400,2) < 7 ,1,0) ) as hsjc_7_day_nums,
    // SUM( if(TRUNCATE(( create_time-UNIX_TIMESTAMP(hsjc_time))/86400,2) < 14 ,1,0) ) as hsjc_14_day_nums,
    // SUM( if(TRUNCATE(( create_time-UNIX_TIMESTAMP(hsjc_time))/86400,2) >= 14 ,1,0) ) as hsjc_outer_14_day_nums,
    // SUM( if(hsjc_result="查询失败",1,0) ) as hsjc_unknow_nums
}
