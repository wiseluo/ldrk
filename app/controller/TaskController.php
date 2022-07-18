<?php
namespace app\controller;

use app\dao\CompanyStaffDao;
use app\dao\UserDao;
use app\dao\OwnDeclareDao;
use app\dao\OwnDeclareOcrDao;
use app\dao\MessageRecordDao;
use app\dao\PlaceDeclareNodeDao;
use app\services\admin\OwnDeclareServices;
use app\services\MessageServices;
use app\services\user\OwnDeclareTempServices;
use app\services\ZzsbViewServices;
use think\facade\View;
use think\facade\Config;
use crmeb\services\SwooleTaskService;
use think\facade\Log;
use Curl\Curl;
use \behavior\SmsTool;
use \behavior\SsjptTool;
use \behavior\FaceTool;
use \behavior\WechatAppletTool;
use app\dao\CompanyDao;
use behavior\SsjptActualTool;
use think\facade\Db;

// 异步任务，最好保证是swoole模式下请求
class TaskController extends Controller
{

    public function index(){
        return View::fetch('task_trigger');
    }

    // 每天上午8：00 发送 3个月内超过预计返义时间
    public function timeToSendOverdueReturnTime()
    {
        $messageServices = app()->make(MessageServices::class);
        $ownDeclareDao = app()->make(OwnDeclareDao::class);
        $userDao = app()->make(UserDao::class);
        $list = $ownDeclareDao->getOverdueReturnTimeList();
        if($list){
            foreach($list as $v) {
                $user = $userDao->get(['id_card'=> $v['id_card']]);
                $message_data = [
                    'receive_id'=> $user['id'],
                    'receive'=> $v['real_name'],
                    'phone'=> $v['phone'],
                    'source_id'=> $v['id'],
                ];
                $param_data = [];
                $messageServices->asyncMessage('template001', $message_data, $param_data);
                $ownDeclareDao->update($v['id'], ['send_sms'=> 1]);
            }
            return show(200, '已触发'.count($list).'条');
        }else{
            return show(200, '无数据触发');
        }
    }

    //重新发送失败消息
    public function timeToSendFailedMessage()
    {
        $messageServices = app()->make(MessageServices::class);
        $messageRecordDao = app()->make(MessageRecordDao::class);
        //发送失败消息
        $failed_message = $messageRecordDao->getFailedMessageList();
        foreach($failed_message as $v) {
            $messageServices->sendMessage($v);
        }
        //漏发消息，今天零时到5分钟之前，漏发的消息
        $miss_message = $messageRecordDao->getMissMessageList();
        foreach($miss_message as $v) {
            $messageServices->sendMessage($v);
        }
        return show(200, '已触发'.(count($failed_message) + count($miss_message)).'条');
    }

    //发送未发送的消息
    public function timeToSendUnSendMessage()
    {
        $messageRecordDao = app()->make(MessageRecordDao::class);
        $smsTool = new SmsTool();
        $index = 1;
        $pageSize = 100;
        //分批次发送短信
        while($index == 1) {
            $mess = $messageRecordDao->getUnSendMessageList($pageSize);
            if(count($mess) == 0) {
                $index = 0;
            }else{
                foreach($mess as $n) {
                    $res = $smsTool->sendSms($n['phone'], $n['content']);
                    if($res['status'] == 1) {
                        $messageRecordDao->update($n['id'], ['status'=> 1, 'send_time'=> date('Y-m-d H:i:s'), 'send_count'=> $n['send_count']+1]);
                    }else{
                        $messageRecordDao->update($n['id'], ['status'=> 2, 'send_time'=> date('Y-m-d H:i:s'), 'send_count'=> $n['send_count']+1]);
                    }
                }
                sleep(2);
            }
        }
        return show(200, '已触发');
    }

    //认证用户身份
    public function timeToVerifyIdCard()
    {
        Log::error('timeToVerifyIdCard');
        $list = app()->make(OwnDeclareDao::class)->getUnVerifyIdCardList();
        foreach($list as $k => $v) {
            $v['key'] = $k;
            SwooleTaskService::declare()->taskType('declare')->data(['action'=>'verifyIdCardService','param'=> $v])->push();
        }
        return show(200, '已触发'. count($list));
    }

    //省核酸检测
    public function timeToHsjc()
    {
        $list = app()->make(OwnDeclareDao::class)->getUnShsjcList();
        foreach($list as $k => $v) {
            $v['key'] = $k;
            SwooleTaskService::declare()->taskType('declare')->data(['action'=>'handleShsjc','param'=> $v])->push();
        }
        Log::error('timeToHsjc count : '.count($list));
        return show(200, '已触发'. count($list));
    }

    //义乌核酸检测
    // public function timeToYwhsjc()
    // {
    //     $list = app()->make(OwnDeclareDao::class)->getUnYwhsjcList();
    //     foreach($list as $k => $v) {
    //         $v['key'] = $k;
    //         SwooleTaskService::declare()->taskType('declare')->data(['action'=>'handleYwhsjc','param'=> $v])->push();
    //     }
    //     Log::error('timeToHsjc count : '.count($list));
    //     return show(200, '已触发'. count($list));
    // }

    //健康码
    public function timeToJkm()
    {
        $list = app()->make(OwnDeclareDao::class)->getUnJkmList();
        foreach($list as $k => $v) {
            $v['key'] = $k;
            SwooleTaskService::declare()->taskType('declare')->data(['action'=>'handleJkm','param'=> $v])->push();
        }
        Log::error('timeToJkm count : '.count($list));
        return show(200, '已触发'. count($list));
    }

    //新冠疫苗预防接种
    public function timeToXgymyfjz()
    {
        $list = app()->make(OwnDeclareDao::class)->getUnXgymjzList();
        foreach($list as $k => $v) {
            $v['key'] = $k;
            SwooleTaskService::declare()->taskType('declare')->data(['action'=>'handleXgymyfjzService','param'=> $v])->push();
        }
        return show(200, '已触发'. count($list));
    }

    //申报ocr识别
    public function timeDeclareOcr()
    {
        $list = app()->make(OwnDeclareDao::class)->getDeclareUnOcr();
        foreach($list as $k => $v) {
            $v['key'] = $k;
            SwooleTaskService::declare()->taskType('declare')->data(['action'=>'ocrDeclareService','param'=> $v])->push();
        }
        return show(200, '已触发'. count($list));
    }

    //获取管控状态
    public function timeToSetControlState()
    {
        $lastid = app()->make(ZzsbViewServices::class)->setControlStateService();
        return show(200, '已触发至id：'. $lastid);
    }

    //处理已识别图片ocr但未匹配到，重新匹配
    public function handleUnmatchOcr()
    {
        $list = app()->make(OwnDeclareOcrDao::class)->getUnmatchOcr();
        foreach($list as $k => $v) {
            SwooleTaskService::declare()->taskType('declare')->data(['action'=>'handleUnmatchOcrService', 'param'=> $v])->push();
        }
        return show(200, '已触发'. count($list));
    }

    // 归档前3天的申报数据到日报表内
    public function archivePre3Day(){
        $res = app()->make(OwnDeclareServices::class)->archivePre3Day();
        return show(200, '已触发'. $res['data']['total_new']);
    }

    public function timeToCheckServerIsOk(){
        $param = request()->param();
        // if(Config::get('app.app_host') == 'dev') { //测试环境不验证
        //     $domain = 'https://ldrk.jk-kj.com/';
        // }else{
            $domain = 'https://yqfk.yw.gov.cn/';
        // }
        if(isset($param['action'])){
            $action = $param['action'];
        }else{
            $other_server_arr = ['serverIsOk95','serverIsOk96','serverIsOk97'];
            $index = mt_rand(0,2);
            $action = $other_server_arr[$index];
        }
        $curl2 = new Curl();
        $curl2->get($domain.$action);
        $result2 = $curl2->response;
        $res2 = json_decode($result2,true);
        if( isset($res2['code']) &&  $res2['code'] == 200){
            return show(200, $action.':'.$res2['data']['app_host'].'成功');
        }else{
            $this->_sendSmsToAdminPhone('服务器健康检测出现异常：'.$action.':'.$res2['data']['app_host'].'失败');
            test_log('timeToCheckServerIsOk error:'.$curl2->response);
            return show(400, $action.':'.$res2['data']['app_host'].'失败');
        }
    }

    private function _sendSmsToAdminPhone($str){
        // 发送短信
        $smsTool = new SmsTool();
        $res = $smsTool->sendSms('13625896500', $str);
    }

    //省库更新请求秘钥定时任务
    public function timeToRefreshSkRequestToken()
    {
        if(Config::get('app.app_host') == 'dev') { 
            test_log('测试环境不刷新 timeToRefreshSkRequestToken');
            return; //测试环境不刷新
        }

        $ssjptTool = new SsjptTool();
        $res =  $ssjptTool->getIrsSecret();
        if($res['status'] == 1) {
            test_log('timeToRefreshSkRequestToken成功-'.$res['data']);
            return show(200, '已更新省库token');
        }else{
            $res2 =  $ssjptTool->getIrsSecret();
            if($res2['status'] == 1) {
                test_log('timeToRefreshSkRequestToken成功2-'.$res2['data']);
                return show(200, '已更新省库token2');
            }else{
                $res3 =  $ssjptTool->getIrsSecret();
                if($res3['status'] == 1) {
                    test_log('timeToRefreshSkRequestToken成功3-'.$res3['data']);
                    return show(200, '已更新省库token3');
                }
                test_log('timeToRefreshSkRequestToken失败3-'.$res3['msg']);
                $this->_sendSmsToAdminPhone('更新省库token3次失败，请尽快手动更新');
                return show(400, '更新省库token失败3-'. $res3['msg']);
            }
            test_log('timeToRefreshSkRequestToken失败2-'.$res2['msg']);
            return show(400, '更新省库token失败2-'. $res2['msg']);
        }
        test_log('timeToRefreshSkRequestToken失败1-'.$res['msg']);
        return show(400, '更新省库token失败1-'. $res['msg']);
    }
    //省库（实时秘钥）更新请求秘钥定时任务
    public function timeToRefreshSkActualRequestToken()
    {
        if(Config::get('app.app_host') == 'dev') { 
            test_log('测试环境不刷新 timeToRefreshSkActualRequestToken');
            return; //测试环境不刷新
        }
        $ssjptTool = new SsjptActualTool();
        $res =  $ssjptTool->getIrsSecret();
        if($res['status'] == 1) {
            test_log('timeToRefreshSkActualRequestToken成功-'.$res['data']);
            return show(200, '已更新省库实时token');
        }else{
            $res2 =  $ssjptTool->getIrsSecret();
            if($res2['status'] == 1) {
                test_log('timeToRefreshSkActualRequestToken成功2-'.$res2['data']);
                return show(200, '已更新省库实时token2');
            }else{
                $res3 =  $ssjptTool->getIrsSecret();
                if($res3['status'] == 1) {
                    test_log('timeToRefreshSkActualRequestToken成功3-'.$res3['data']);
                    return show(200, '已更新省库实时token3');
                }
                test_log('timeToRefreshSkActualRequestToken失败3-'.$res3['msg']);
                $this->_sendSmsToAdminPhone('更新省库实时token3次失败，请尽快手动更新');
                return show(400, '更新省库实时token失败3-'. $res3['msg']);
            }
            test_log('timeToRefreshSkActualRequestToken失败2-'.$res2['msg']);
            return show(400, '更新省库实时token失败2-'. $res2['msg']);
        }
        test_log('timeToRefreshSkActualRequestToken失败1-'.$res['msg']);
        return show(400, '更新省库实时token失败1-'. $res['msg']);
    }
   // 更新人脸识别token定时任务
   public function timeToRefreshFaceRequestToken()
   {
        if(Config::get('app.app_host') == 'dev') { 
            test_log('测试环境不刷新 timeToRefreshFaceRequestToken');
            return; //测试环境不刷新
        }
       $faceTool = new FaceTool();
       $res =  $faceTool->timeToGetToken();
       if($res['status'] == 1) {
           test_log('timeToRefreshFaceRequestToken成功-'.$res['data']);
           return show(200, '已更新更新人脸识别token');
       }else{
           $res2 =  $faceTool->timeToGetToken();
           if($res2['status'] == 1) {
               test_log('timeToRefreshFaceRequestToken成功2-'.$res2['data']);
               return show(200, '已更新更新人脸识别token2');
           }else{
               $res3 =  $faceTool->timeToGetToken();
               if($res3['status'] == 1) {
                   test_log('timeToRefreshFaceRequestToken成功3-'.$res3['data']);
                   return show(200, '已更更新人脸识别token3');
               }
               test_log('timeToRefreshFaceRequestToken失败3-'.$res3['msg']);
               $this->_sendSmsToAdminPhone('更新人脸识别token3次失败，请尽快手动更新');
               return show(400, '更新人脸识别token失败3-'. $res3['msg']);
           }
           test_log('timeToRefreshFaceRequestToken失败2-'.$res2['msg']);
           return show(400, '更新人脸识别token失败2-'. $res2['msg']);
       }
       test_log('timeToRefreshFaceRequestToken失败1-'.$res['msg']);
       return show(400, '更新人脸识别token失败1-'. $res['msg']);
   }
    //更新微信access token定时任务
    public function timeToRefreshWxAccessToken()
    {
        $wechatAppletTool = new WechatAppletTool();
        $res = $wechatAppletTool->setAccessToken();
        if($res['status'] == 1) {
            test_log('timeToRefreshWxAccessToken成功-'.$res['data']);
            return show(200, '已更新微信access token');
        }else{
            $res2 = $wechatAppletTool->setAccessToken();
            if($res2['status'] == 1) {
                test_log('timeToRefreshWxAccessToken成功2-'.$res2['data']);
                return show(200, '已更新微信access token2');
            }else{
                $res3 = $wechatAppletTool->setAccessToken();
                if($res3['status'] == 1) {
                    test_log('timeToRefreshWxAccessToken成功3-'.$res3['data']);
                    return show(200, '已更新微信access token3');
                }
                test_log('timeToRefreshWxAccessToken失败3-'.$res3['msg']);
                $this->_sendSmsToAdminPhone('更新微信access token3次失败，请尽快手动更新');
                return show(400, '更新微信access token失败3-'. $res3['msg']);
            }
            test_log('timeToRefreshSkRequestToken失败2-'.$res2['msg']);
            return show(400, '更新微信access token失败2-'. $res2['msg']);
        }
        test_log('timeToRefreshSkRequestToken失败1-'.$res['msg']);
        return show(400, '更新微信access token失败1-'. $res['msg']);
    }

    // 更新企业员工的核酸情况
    public function timeToUpdateCompanyStaffResult(){
        SwooleTaskService::company()->taskType('company')->data(['action'=>'taskUpdateCompanyStaffHsjc', 'param'=> []])->push();
    }

    // 更新企业员工的采样情况
    public function timeToUpdateCompanyStaffReceiveTime(){
        SwooleTaskService::company()->taskType('company')->data(['action'=>'taskUpdateCompanyStaffReceiveTime', 'param'=> []])->push();
    }


    // // 更新企业员工没有在义乌库里面的核酸情况
    // public function timeToUpdateCompanyStaffNoYwResult(){
    //     SwooleTaskService::company()->taskType('company')->data(['action'=>'taskUpdateCompanyStaffNoYwHsjc', 'param'=> []])->push();
    // }


    //一小时一次，计算所有企业的员工核酸检测数
    public function timeToCompanyHsjcCount()
    {
        SwooleTaskService::company()->taskType('company')->data(['action'=>'taskCompanyHsjcCount', 'param'=> []])->push();
        return show(200, '已触发');
    }

    //联络员负责的镇街，48小时内核酸检测人数不足5人，每天早上8：30给联络员发送一条提醒短信
    public function timeToSendSmsToLinkTwoDaysHsjc()
    {
        SwooleTaskService::company()->taskType('company')->data(['action'=>'taskSendSmsToLinkTwoDaysHsjc', 'param'=> []])->push();
        return show(200, '已触发');
    }

    public function timeToPlaceDeclareDateNums(){
        $param = request()->param();
        SwooleTaskService::company()->taskType('place')->data(['action'=>'taskPlaceDeclareDateNums', 'param'=> $param])->push();
        return show(200, '已触发');
    }

    public function timeToFeedDateNums(){
        $param = request()->param();
        SwooleTaskService::company()->taskType('place')->data(['action'=>'taskFeedDateNums', 'param'=> $param])->push();
        return show(200, '已触发');
    }

    public function timeToGroupDateNums(){
        $param = request()->param();
        SwooleTaskService::company()->taskType('place')->data(['action'=>'taskGroupDateNums', 'param'=> $param])->push();
        return show(200, '已触发');
    }


    public function timeToPlaceDeclareStreetHourNums(){
        $param = request()->param();
        SwooleTaskService::company()->taskType('place')->data(['action'=>'taskPlaceDeclareStreetHourNums', 'param'=> $param])->push();
        return show(200, '已触发');
    }

    public function timeToFeedHourNums(){
        $param = request()->param();
        SwooleTaskService::company()->taskType('place')->data(['action'=>'taskFeedHourNums', 'param'=> $param])->push();
        return show(200, '已触发');
    }
    // 从本地的省回流库去更新
    // public function timeToUpdateHsjcFromLocalSKHL(){
    //     $last_id = (int)Db::name('system_config')->where('menu_name','=','skhl_last_id')->value('value');
    //     if($last_id > 0){
    //         $data = Db::table('dsc_jh_dm_037_pt_patientinfo_sc_delta_new')->where('reportid','>',$last_id)->limit(10)->select();
    //         test_log('timeToUpdateUserHsjcFromLocalSHL last_id='.$last_id);
    //         foreach($data as $key => $value){
    //             SwooleTaskService::company()->taskType('company')->data(['action'=>'syncStaffHsjcFromLocalSKHL','param'=>['id_card'=> $value['sfzh'],'real_name'=>$value['patientname'], 'add_rider'=> 0,'item'=>$value]])->push();
    //             $last_id = $value['reportid'];
    //         }
    //         Db::name('system_config')->where('menu_name','=','skhl_last_id')->save(['value'=>$last_id]);
    //         return show(200, '已触发'.count($data));
    //     }else{
    //         test_log('error timeToUpdateUserHsjcFromLocalSHL last_id=0');
    //         return show(200, 'last_id=0');
    //     }
    // }

    // 检测从库的状态
    public function timeToCheckSlaveDb(){
        $res = Db::connect('mysql_slave')->query(" show SLAVE status", []);
        
        if($res){
            $one_info = $res[0];
            if($one_info['Slave_IO_Running'] == 'Yes'  && $one_info['Slave_SQL_Running'] == 'Yes'){
                // 正常
                // test_log('timeToCheckSlaveDb 正常');
                // test_log($res);
            }else{
                system_error_log(__METHOD__,'从库状态不正常'.Date('YmdHi'),'Slave_IO_Running:'.$one_info['Slave_IO_Running'].',Slave_SQL_Running:'.$one_info['Slave_SQL_Running']);
            }
        }
    }
    // 检测从库的状态
    public function timeToMoveTmpTestLogToDb(){
        $table = app("swoole.table.tmp_log");
        test_log('delete before table count:'.$table->count());
        foreach($table as $key => $value){
            \think\facade\Db::name('test')->save(['info'=>$value['info'],'create_datetime'=>$value['create_datetime']]);
            $table->del($value['key']);
        }
        test_log('table count:'.$table->count());
    }
    public function timeToSendSmsToAdmin(){
        // 查找
        $data = Db::name('system_day_error')->where('is_send_msg','=',0)->where('function_name','<>','app\services\FysjServices::getJkmService')->select();
        $message_arr = [];
        if($data){
            $dataarr =  $data->toArray();
            $has_shenku_shishi_miya_error = false; // 省库(实时)秘钥有错误
            $has_shenku_miya_error = false; // 省库秘钥有错误
            foreach($dataarr as $key => $value){
                if($value['function_name'] == 'behavior\SsjptActualTool::skIdcardAndPhoneToJkm'){
                    $has_shenku_shishi_miya_error = true; // 有省库(实时)秘钥更新的错误
                }
                if($value['function_name'] == 'behavior\SsjptTool::skIdcardToJkm'){
                    $has_shenku_miya_error = true; // 有省库秘钥更新的错误
                }
            }
            if($has_shenku_shishi_miya_error){
                $curl = new Curl();
                $curl->get('http://localhost:30399/task/timeToRefreshSkActualRequestToken', []);
                if ($curl->error) {
                    test_log('脚本检测到错误，自动触发更新省库(实时)秘钥更新的错误-'. $curl->error_code .': '. $curl->error_message);
                } else {
                    test_log('脚本检测到错误，自动触发更新省库(实时)秘钥更新');
                }
                $curl->close();
            }
            if($has_shenku_miya_error){
                $curl = new Curl();
                $curl->get('http://localhost:30399/task/timeToRefreshSkRequestToken', []);
                if ($curl->error) {
                    test_log('脚本检测到错误，自动触发更新省库秘钥更新的错误-'. $curl->error_code .': '. $curl->error_message);
                } else {
                    test_log('脚本检测到错误，自动触发更新省库秘钥更新');
                }
                $curl->close();
            }
            $message_arr = array_column($dataarr,'name');
            $message_arr = array_unique($message_arr);

            $day_error_admin_phone = Db::name('system_config')->where('menu_name','=','day_error_admin_phone')->value('value');
            if($day_error_admin_phone){
                // 发送短信
                $smsTool = new SmsTool();
                $res = $smsTool->sendSms($day_error_admin_phone, implode(',',$message_arr) );
                if(isset($res['status']) && $res['status'] == 1){
                    Db::name('system_day_error')->where(['is_send_msg'=>0])->update(['is_send_msg'=>1]);
                }
                var_dump($res);
            }else{
                test_log('不存在day_error_admin_phone的配置');
            }

        }
    }

    public function timeToSplitPlaceDeclareTable()
    {
        $day = date('Ymd', strtotime("-1 day"));
        $suffix = '_'. $day;
        $newtable_name = 'eb_place_declare'. $suffix;
        Db::execute('alter table eb_place_declare rename to '. $newtable_name .';');
        //Db::execute('create table eb_place_declare like '. $newtable_name .';');
        Db::execute("CREATE TABLE `eb_place_declare` (
            `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户id',
            `sign` varchar(50) DEFAULT '' COMMENT '唯一签名',
            `place_code` varchar(50) DEFAULT '0' COMMENT '场所code',
            `place_name` varchar(150) DEFAULT '' COMMENT '场所名称',
            `place_short_name` varchar(150) DEFAULT '' COMMENT '场所简称',
            `place_unit` varchar(150) DEFAULT '' COMMENT '场所单位',
            `real_name` varchar(150) DEFAULT NULL COMMENT '真实姓名',
            `id_card` varchar(20) DEFAULT '' COMMENT '身份证号码',
            `phone` varchar(50) DEFAULT NULL COMMENT '手机号码',
            `yw_street_id` int(11) DEFAULT '0' COMMENT '镇街id',
            `yw_street` varchar(150) DEFAULT '' COMMENT '镇街',
            `id_verify_result` tinyint(4) DEFAULT '0' COMMENT '身份验证结果：0=未验证，1=验证成功，2=验证失败',
            `age` tinyint(4) DEFAULT '0' COMMENT '年龄',
            `vaccination` tinyint(4) DEFAULT '0' COMMENT '疫苗接种：0=否，1=是',
            `vaccination_date` date DEFAULT NULL COMMENT '接种日期',
            `vaccination_times` tinyint(4) DEFAULT '0' COMMENT '接种剂次',
            `xgymjz_get` tinyint(4) DEFAULT '0' COMMENT '是否获取过新冠疫苗接种',
            `jkm_time` datetime DEFAULT NULL COMMENT '健康码获取时间',
            `jkm_mzt` varchar(50) DEFAULT '' COMMENT '健康码-码状态',
            `jkm_get` tinyint(4) unsigned DEFAULT '0' COMMENT '是否获取过-健康码',
            `hsjc_time` datetime DEFAULT NULL COMMENT '核酸检测时间',
            `hsjc_result` varchar(50) DEFAULT '' COMMENT '核酸检测结果',
            `hsjc_jcjg` varchar(150) DEFAULT '' COMMENT '核酸检测-检测机构',
            `hsjc_get` tinyint(4) unsigned DEFAULT '0' COMMENT '是否获取过核酸检测信息：0=未获取，1=省内，2=义乌库',
            `xcm_result` varchar(50) DEFAULT '0' COMMENT '1 代表没有去过高风险地区 2 代表去过高风险地区 3 代表用户没有行程记录',
            `ryxx_result` varchar(150) DEFAULT '' COMMENT '人员信息结果',
            `create_time` int(11) unsigned DEFAULT '0' COMMENT '添加时间',
            `update_time` int(11) unsigned DEFAULT '0' COMMENT '修改时间',
            `delete_time` int(11) unsigned DEFAULT NULL,
            `place_addr` varchar(150) DEFAULT NULL COMMENT '场所地址',
            `link_man` varchar(50) DEFAULT NULL COMMENT '联络人',
            `link_phone` varchar(50) DEFAULT NULL COMMENT '联络人电话',
            `create_date` date DEFAULT NULL COMMENT '申报所在日期',
            `create_datetime` datetime DEFAULT NULL COMMENT '创建时间',
            PRIMARY KEY (`id`) USING BTREE,
            UNIQUE KEY `unq_sign` (`sign`) USING BTREE,
            KEY `place_code` (`place_code`) USING BTREE,
            KEY `place_name` (`place_name`) USING BTREE,
            KEY `real_name` (`real_name`) USING BTREE,
            KEY `id_card` (`id_card`) USING BTREE,
            KEY `phone` (`phone`) USING BTREE,
            KEY `yw_street` (`yw_street`) USING BTREE,
            KEY `yw_street_id` (`yw_street_id`) USING BTREE,
            KEY `create_date` (`create_date`) USING BTREE,
            KEY `jkm_mzt` (`jkm_mzt`) USING BTREE,
            KEY `hsjc_result` (`hsjc_result`) USING BTREE,
            KEY `place_short_name` (`place_short_name`) USING BTREE,
            KEY `ryxx_result` (`ryxx_result`) USING BTREE,
            KEY `link_man` (`link_man`),
            KEY `link_phone` (`link_phone`)
          ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='场所码申报表';");
        
        $node = Db::name('place_declare_node')->order('id', 'desc')->find();
        $last_date = '';
        if($node) {
            $last_date = $node['separate_date'];
        }
        $node_data = [
            'separate_date'=> $day,
            'table_name'=> $newtable_name,
            'suffix'=> $suffix,
            'describe'=> $last_date == '' ? $day : $last_date .'至'. $day,
        ];
        app()->make(PlaceDeclareNodeDao::class)->save($node_data);
        return show(200, '完成');
    }

}