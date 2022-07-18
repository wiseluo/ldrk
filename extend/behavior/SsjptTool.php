<?php

namespace behavior;

use Curl\Curl;
use think\facade\Config;
use app\dao\WxtokenDao;
use think\facade\Cache;

//省公共数据平台工具类
class SsjptTool
{
    //义乌市疫情系统
    private $app_key = '951903d2811c461d85903498654de977';
    private $app_secret = '7fe3c6323da943bab5ed12da04690118';
    private $xcmkey = '9@nr!#G8*Kuw';


    /**
     * 获取刷新密钥和请求密钥12分钟一次，用定时任务更新
     */
    public function getIrsSecret()
    {
        $wxtoken = app()->make(WxtokenDao::class);
        $token = $wxtoken->get(['type'=> 'sksecret']);
        if($token && $token['refresh_expires'] >= time()*1000) { //刷新秘钥存在且未过期
            $new_token_res = $this->skIrsSecretBySec($token['refresh_token']);
            test_tmp_log('skIrsSecretBySec-'. json_encode($new_token_res),__METHOD__,'1');
            if($new_token_res['status'] == 0) {
                return ['status'=> 0, 'msg'=> $new_token_res['msg']];
            }
        }else{//秘钥不存在或刷新秘钥过期
            $new_token_res = $this->skIrsSecretByKey();
            if($new_token_res['status'] == 0) {
                return ['status'=> 0, 'msg'=> $new_token_res['msg']];
            }
        }
        //提前3分钟刷新token
        //$expires = $new_token_res['data']['requestSecretEndTime'] - 180000;
        $data = [
            'type' => 'sksecret',
            'access_token' => $new_token_res['data']['requestSecret'],
            'expires' => $new_token_res['data']['requestSecretEndTime'],
            'refresh_token' => $new_token_res['data']['refreshSecret'],
            'refresh_expires' => $new_token_res['data']['refreshSecretEndTime'],
        ];
        try {
            if($token) {
                $wxtoken->update($token['id'], $data);
            }else{
                $wxtoken->save($data);
            }
            // 缓存在12分10秒之后过期
            // Cache::store('redis')->set('skRequestTokenRedis', $token->toArray(), 730);
            return ['status'=> 1, 'msg'=> '成功', 'data'=> $data['access_token']];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '保存失败-'. $e->getMessage()];
        }
    }

    //请求秘钥失效后根据刷新秘钥获取省库请求秘钥和刷新秘钥
    public function skIrsSecretBySec($refresh_secret)
    {
        $mtimestamp = time() * 1000; // 毫秒
        $sign = md5($this->app_key . $refresh_secret . $mtimestamp);
        $curl = new Curl();
        if(Config::get('app.app_host') == 'dev') { //测试服务器
            $url = "http://localhost:8083/gateway/app/refreshTokenBySec.htm";
        }else{
            $url = "http://59.202.38.178/gateway/app/refreshTokenBySec.htm";
        }
        $curl->get($url, [
            'appKey' => $this->app_key,
            'sign' => $sign,
            'requestTime' => $mtimestamp,
        ]);
        
        if ($curl->error) {
            test_tmp_log('skIrsSecretBySec-'. $curl->error_code .': '. $curl->error_message,__METHOD__,'1');
            return ['status'=> 0, 'msg'=> $curl->error_code .': '. $curl->error_message];
        } else {
            $res = $curl->response;
            //test_log('skIrsSecretBySec-'. $res);
            $result = json_decode($res, true);
        }
        $curl->close();
        if(isset($result['code']) && $result['code'] == "00") {
            return ['status'=> 1, 'msg'=> '成功', 'data'=> $result['datas']];
        }else{
            test_tmp_log('skIrsSecretBySec-'. $res,__METHOD__,'2');
            return ['status'=> 0, 'msg'=> $result['msg']];
        }
    }

    //刷新秘钥失效后根据app秘钥获取省库请求秘钥和刷新秘钥
    public function skIrsSecretByKey()
    {
        $mtimestamp = time() * 1000; // 毫秒
        $sign = md5($this->app_key . $this->app_secret . $mtimestamp);
        $curl = new Curl();
        if(Config::get('app.app_host') == 'dev') { //测试服务器
            $url = "http://localhost:8083/gateway/app/refreshTokenByKey.htm";
        }else{
            $url = "http://59.202.38.178/gateway/app/refreshTokenByKey.htm";
        }
        $curl->get($url, [
            'appKey' => $this->app_key,
            'sign' => $sign,
            'requestTime' => $mtimestamp,
        ]);
        
        if ($curl->error) {
            test_tmp_log('skIrsSecretByKey-'. $curl->error_code .': '. $curl->error_message,__METHOD__,'1');
            return ['status'=> 0, 'msg'=> $curl->error_code .': '. $curl->error_message];
        } else {
            $res = $curl->response;
            //test_log('skIrsSecretByKey-'. $res);
            $result = json_decode($res, true);
        }
        $curl->close();
        if(isset($result['code']) && $result['code'] == "00") {
            return ['status'=> 1, 'msg'=> '成功', 'data'=> $result['datas']];
        }else{
            test_tmp_log('skIrsSecretByKey-'. $res,__METHOD__,'2');
            return ['status'=> 0, 'msg'=> $result['msg']];
        }
    }

    //获取省库请求秘钥
    public function getSkRequestToken()
    {
        if(Config::get('app.app_host') == 'dev') { //测试服务器
            $url = "http://localhost:8083/sk_sjpt/token";
            $curl = new Curl();
            $curl->get($url);
            
            if ($curl->error) {
                test_tmp_log('sksjpt_token-'. $curl->error_code .': '. $curl->error_message,__METHOD__,'1');
                return ['status'=> 0, 'msg'=> $curl->error_code .': '. $curl->error_message];
            } else {
                $res = $curl->response;
                test_tmp_log('sksjpt_token-'. $res,__METHOD__,'2');
                $result = json_decode($res, true);
            }
            $curl->close();
            if(isset($result['code']) && $result['code'] == "200") {
                return ['status'=> 1, 'msg'=> '成功', 'data'=> $result['data']];
            }else{
                test_tmp_log('sksjpt_token-'. $res,__METHOD__,'3');
                return ['status'=> 0, 'msg'=> $result['msg']];
            }
        }
        // $token = app()->make(WxtokenDao::class)->get(['type'=> 'sksecret']);
        //$token = Cache::store('redis')->get('skRequestToken');
        // if($token) {
        //     return ['status'=> 1, 'msg'=> '成功', 'data'=> $token['access_token']];
        // }else{
        //     test_log('sksjpt_token-秘钥不存在，定时任务未设置');
        //     return ['status'=> 0, 'msg'=> '秘钥不存在'];
        // }
        $access_token = $this->_sk_request_token_cache();
        if($access_token) {
            return ['status'=> 1, 'msg'=> '成功', 'data'=> $access_token];
        }else{
            test_log('sksjpt_token-秘钥不存在，定时任务未设置');
            return ['status'=> 0, 'msg'=> '秘钥不存在'];
        }
    }
    private function _sk_request_token_cache(){
        $cache = Cache::get('access_token_sksecret');
        if($cache){
            return $cache;
        }else{
            $token_info = app()->make(WxtokenDao::class)->get(['type'=> 'sksecret']);
            if($token_info){
                Cache::set('access_token_sksecret',$token_info['access_token'],720);
                return $token_info['access_token'];
            }
        }
    }
    private function _sk_request_token_clear(){
        Cache::delete('access_token_sksecret');
    }

    //全省健康码基本信息查询(手机号)
    public function skPhoneToJkm($phone, $times=0)
    {
        $request_token_res = $this->getSkRequestToken();
        if($request_token_res['status'] == 0) {
            return ['status'=> 0, 'msg'=> $request_token_res['msg']];
        }
        // 目前反向代理,为了测试和正式环境都能调用
        if(Config::get('app.app_host') == 'dev') { //测试服务器
            return $this->forTestApi('post','skPhoneToJkm',['phone'=>$phone,'times'=>$times]);
        }else{
            $url = "https://interface.zjzwfw.gov.cn/gateway/api/001003001/dataSharing/Y7B604xUs73c7b57.htm";
        }
        $curl = new Curl();
        $mtimestamp = time() * 1000; // 毫秒
        $sign = md5($this->app_key . $request_token_res['data'] . $mtimestamp);
        $curl->setHeader('appKey', $this->app_key);
        $curl->setHeader('sign', $sign);
        $curl->setHeader('requestTime', $mtimestamp);
        $curl->setHeader('Content-Type', 'application/json; charset=UTF-8');
        $data = [
            'sjh' => $phone,
        ];
        $data_json = json_encode($data);
        $curl->post($url, $data_json);
        if ($curl->error) {
            test_tmp_log('skPhoneToJkm-'. $curl->error_code .': '. $curl->error_message,__METHOD__,'1');
            return ['status'=> 0, 'msg'=> $curl->error_code .': '. $curl->error_message];
        } else {
            $res = $curl->response;
            //test_log('skPhoneToJkm-'. $res);
            $result = json_decode($res, true);
        }
        $curl->close();
        if( isset($result['code']) && $result['code'] == "00") {
            $datas = json_decode($result['datas'], true);
            if($datas['success'] == true) {
                if($datas['data'] == null || count($datas['data']) == 0) {
                    return ['status'=> 0, 'msg'=> '不存在'];
                }
                $last_item = $this->_getCardIdArr($datas['data'],$phone);
                return ['status'=> 1, 'msg'=> '成功', 'data'=> $last_item];
            }else{
                return ['status'=> 0, 'msg'=> $datas['errorMsg']];
            }
        }else{
            if(isset($result['code']) && $result['code'] == "18"){
                system_error_log(__METHOD__,'手机号查询身份证接口超量',$result['msg']);
            }
            test_tmp_log('skPhoneToJkm-'. $res,__METHOD__,'2');
            if($result['code'] == '14') { //签名错误，重新查询数据
                $this->_sk_request_token_clear();
                if($times >= 3) {
                    return ['status'=> 0, 'msg'=> '签名错误'];
                }
                $times += 1;
                test_tmp_log('skPhoneToJkm-签名错误，重新查询'. $times,__METHOD__,'3');
                return $this->skPhoneToJkm($phone, $times);
            }
            return ['status'=> 0, 'msg'=> $result['msg']];
        }
    }

    private function _getCardIdArr($data,$phone){
        $id_card_map = [];
        foreach($data as $key => $item){
            $id_card_map[$item['sfzh']]['sfzh'] = $item['sfzh'];
            $id_card_map[$item['sfzh']]['xm'] = $item['xm'];
            $id_card_map[$item['sfzh']]['sjh'] = $item['sjh'];
        }
        return array_values($id_card_map);
    }

    //全省健康码基本信息查询(身份证号)
    public function skIdcardToJkm($id_card, $times=0,$is_all=0)
    {
        $id_card = strtoupper($id_card); // 身份证x转大写
        $request_token_res = $this->getSkRequestToken();
        if($request_token_res['status'] == 0) {
            return ['status'=> 0, 'msg'=> $request_token_res['msg']];
        }
        // 目前反向代理,为了测试和正式环境都能调用
        if(Config::get('app.app_host') == 'dev') { //测试服务器
            return $this->forTestApi('post','skIdcardToJkm',['id_card'=>$id_card,'times'=>$times,'is_all'=>$is_all]);
        }else{
            $url = "https://interface.zjzwfw.gov.cn/gateway/api/001003001/dataSharing/uU4lb0350783d2fa.htm";
        }
        $curl = new Curl();
        $mtimestamp = time() * 1000; // 毫秒
        $sign = md5($this->app_key . $request_token_res['data'] . $mtimestamp);
        $curl->setHeader('appKey', $this->app_key);
        $curl->setHeader('sign', $sign);
        $curl->setHeader('requestTime', $mtimestamp);
        $curl->setHeader('Content-Type', 'application/json; charset=UTF-8');
        $data = [
            'sfzh' => $id_card,
        ];
        $data_json = json_encode($data);
        $curl->post($url, $data_json);
        if ($curl->error) {
            test_tmp_log('skIdcardToJkm-'. $curl->error_code .': '. $curl->error_message,__METHOD__,'1');
            return ['status'=> 0, 'msg'=> $curl->error_code .': '. $curl->error_message];
        } else {
            $res = $curl->response;
            // test_log('skIdcardToJkm-'. $res);
            $result = json_decode($res, true);
        }
        $curl->close();
        if($result['code'] == "00") {
            $datas = json_decode($result['datas'], true);
            if($datas['success'] == true) {
                if($datas['data'] == null || count($datas['data']) == 0) {
                    return ['status'=> 0, 'msg'=> '不存在'];
                }
                if($is_all == 1){
                    $last_item = $datas['data']; // 测试环境需要查看全部
                }else{
                    $last_item = $this->_getLastJkmItem($datas['data']);
                }
                return ['status'=> 1, 'msg'=> '成功', 'data'=> $last_item ];
            }else{
                return ['status'=> 0, 'msg'=> $datas['errorMsg']];
            }
        }else{
            test_tmp_log('skIdcardToJkm-'. $res,__METHOD__,'2');
            if($result['code'] == '14') { //签名错误，重新查询数据
                $this->_sk_request_token_clear();
                if($times >= 3) {
                    // 触发刷新
                    $remain_second = 60 - Date('s');
                    system_error_log(__METHOD__,'省库秘钥查询3次都失败,一分钟内将触发重新获取,预计影响秒数：'.$remain_second.'s','一分钟内将触发重新获取');
                    return ['status'=> 0, 'msg'=> '签名错误'];
                }
                $times += 1;
                return $this->skIdcardToJkm($id_card, $times);
            }
            if($result['code'] == '11') { // 请求秘钥不在有效期内
                $this->_sk_request_token_clear();
                $remain_second = 60 - Date('s');
                system_error_log(__METHOD__,'省库秘钥请求秘钥不在有效期内,一分钟内将触发重新获取,预计影响秒数：'.$remain_second.'s','一分钟内将触发重新获取');
                return ['status'=> 0, 'msg'=> '签名错误'];
            }
            return ['status'=> 0, 'msg'=> $result['msg']];
        }
    }
    
    private function _getLastJkmItem($data){
        $last_item = [];
        // 金华市的为主
        $last_jinhua_item = [];
        foreach($data as $key => $item){
            if(count($last_item) == 0){
                $last_item = $item;
            }
            // 时间
            if( strtotime($item['zjgxsj']) > strtotime($last_item['zjgxsj']) ){
                $last_item = $item;
            }
            if($item['mffd'] == '金华市'){
                if(count($last_jinhua_item) == 0){
                    $last_jinhua_item = $item;
                }
                // 时间
                if( strtotime($item['zjgxsj']) > strtotime($last_jinhua_item['zjgxsj']) ){
                    $last_jinhua_item = $item;
                }
            }
        }
        if($last_jinhua_item){
            return $last_jinhua_item;
        }
        return $last_item;
    }

    
    private function _getLastJkmItem_old($data){
        $last_item = [];
        foreach($data as $key => $item){
            if(count($last_item) == 0){
                $last_item = $item;
            }
            // 时间
            if( strtotime($item['zjgxsj']) > strtotime($last_item['zjgxsj']) ){
                $last_item = $item;
            }
        }
        return $last_item;
    }

    /*
     * 省库行程码短信接口
    */
    public function skxcmdxjk($phone, $times=0)
    {
        if(empty($this->xcmkey)) {
            return ['status'=> 0, 'msg'=> '签名密匙未配置'];
        }
        if(empty($phone)) {
            return ['status'=> 0, 'msg'=> '手机号不能为空'];
        }
        $request_token_res = $this->getSkRequestToken();
        if($request_token_res['status'] == 0) {
            return ['status'=> 0, 'msg'=> $request_token_res['msg']];
        }
        // 目前反向代理,为了测试和正式环境都能调用
        if(Config::get('app.app_host') == 'dev') { //测试服务器
            return $this->forTestApi('post','skxcmdxjk',['phone'=>$phone,'times'=>$times]);
        }else{
            $url = 'https://interface.zjzwfw.gov.cn/gateway/api/001003001/national/deYxdfMgX8cd8h76.htm';
        }
        $curl = new Curl();
        $mtimestamp = time() * 1000; // 毫秒
        $sign = md5($this->app_key . $request_token_res['data'] . $mtimestamp);
        $curl->setHeader('appKey', $this->app_key);
        $curl->setHeader('sign', $sign);
        $curl->setHeader('requestTime', $mtimestamp);
        $curl->setHeader('Content-Type', 'application/json; charset=UTF-8');
        
        $sendTime = date('Y-m-d H:i:s');
        $secret = md5($this->xcmkey . $sendTime);
        $data = [
            'phone' => $phone,
            'queryId' => md5(uniqid(microtime(true),true)),
            'sendTime' => $sendTime,
            'secert' => $secret,
        ];
        $data_json = json_encode($data);
        $curl->post($url, $data_json);
        if($curl->error) {
            test_tmp_log('skxcmdxjk-error-'. $curl->error_code .': '. $curl->error_message,__METHOD__,'1');
            return ['status'=> 0, 'msg'=> $curl->error_code .': '. $curl->error_message];
        }else{
            $res = $curl->response;
            //test_log('skxcmdxjk-'. $res);
            $result = json_decode($res, true);
        }
        // {"code":"00","msg":"成功","data":"","datas":"{\"code\":200,\"data\":
        // {\"status\":\"1\",\"code\":\"00\",\"errorDesc\":\"请求成功\",\"result\":\"短信发送中,请注意查收\",\"queryId\":\"d57ffb7fdfd3aaea9d14780de3b10ebc\"},\"message\":\"success\"}",
        // "requestId": "f8c9796f766142d0ba1515fd78681110",
        // "dataCount": 1,
        // "totalDataCount": 0,
        // "totalPage": 1
        // }
        $curl->close();
        if($result['code'] == '00') {
            if($result['datas'] == '') {
                if($times >= 2) {
                    test_tmp_log('skxcmjk-2次，datas为空:'.$curl->response,__METHOD__,'2');
                    return ['status'=> 0, 'msg'=> '数据为空'];
                }
                $times += 1;
                return $this->skxcmdxjk($phone, $times);
            }
            $datas = json_decode($result['datas'], true);
            if(isset($datas['code']) && $datas['code'] == 200) {
                if($datas['data'] == null || count($datas['data']) == 0) {
                    return ['status'=> 0, 'msg'=> '接口数据为空'];
                }
                if($datas['data']['status'] == 1) {
                    return ['status'=> 1, 'msg'=> '成功', 'data'=> $datas['data']['result']];
                }else if($datas['data']['status'] == 2) { //验证码仍在有效期内
                    return ['status'=> 2, 'msg'=> $datas['data']['errorDesc']];
                }else{
                    test_tmp_log('skxcmdxjk-'. $res,__METHOD__,'3');
                    return ['status'=> 0, 'msg'=> $datas['data']['errorDesc']];
                }
            }else{
                test_tmp_log('skxcmdxjk-'. $res,__METHOD__,'4');
                return ['status'=> 0, 'msg'=> $datas['errMsg']];
            }
        }else{
            test_tmp_log('skxcmdxjk-'. $res,__METHOD__,'5');
            if($result['code'] == '14') { //签名错误，重新查询数据
                $this->_sk_request_token_clear();
                if($times >= 3) {
                    return ['status'=> 0, 'msg'=> '签名错误'];
                }
                $times += 1;
                return $this->skxcmdxjk($phone, $times);
            }
            return ['status'=> 0, 'msg'=> $result['msg']];
        }
    }
    
    /*
     * 省库行程码接口
    */
    public function skxcmjk($phone = '', $verification = '', $city_code = '0', $times=0)
    {
        if(empty($this->xcmkey)) {
            return ['status'=> 0, 'msg'=> '签名密匙未配置'];
        }
        if(empty($phone)) {
            return ['status'=> 0, 'msg'=> '手机号不能为空'];
        }
        $request_token_res = $this->getSkRequestToken();
        if($request_token_res['status'] == 0) {
            return ['status'=> 0, 'msg'=> $request_token_res['msg']];
        }
        // 目前反向代理,为了测试和正式环境都能调用
        if(Config::get('app.app_host') == 'dev') { //测试服务器
            return $this->forTestApi('post','skxcmjk',['phone'=>$phone,'verification'=>$verification,'city_code'=>$city_code,'times'=>$times]);
        }else{
            $url = 'https://interface.zjzwfw.gov.cn/gateway/api/001003001/dataSharing/OwQ79dcpSaDW050b.htm';
        }
        $curl = new Curl();
        $mtimestamp = time() * 1000; // 毫秒
        $sign = md5($this->app_key . $request_token_res['data'] . $mtimestamp);
        $curl->setHeader('appKey', $this->app_key);
        $curl->setHeader('sign', $sign);
        $curl->setHeader('requestTime', $mtimestamp);
        $curl->setHeader('Content-Type', 'application/json; charset=UTF-8');

        $sendTime = date('Y-m-d H:i:s');
        $secret = md5($this->xcmkey . $sendTime);
        $data = [
            'phone' => $phone,
            'queryId' => md5(uniqid(microtime(true),true)),
            'sendTime' => $sendTime,
            'secert' => $secret,
            'verification' => $verification,
            'type' => '233',
            'city' => $city_code,
        ];
        $data_json = json_encode($data);
        $curl->post($url, $data_json);
        if($curl->error) {
            test_tmp_log('skxcmjk-'. $curl->error_code .': '. $curl->error_message,__METHOD__,'1');
            return ['status'=> 0, 'msg'=> $curl->error_code .': '. $curl->error_message];
        }else{
            $res = $curl->response;
            //test_log('skxcmjk-'. $res);
            $result = json_decode($res, true);
        }
        // {"code":"00","msg":"成功","data":"","datas":"{\"code\":200,\"data\":{\"status\":\"1\",\"code\":\"00\",\"errorDesc\":\"请求成功\",\"result\":{\"value\":\"1\"},
        // \"queryId\":\"7f06087de850e7509c24051ea15cb1ef\"},\"message\":\"success\"}",
        // "requestId": "8cb31f42ad7c4b4d81240ae800eaced0",
        // "dataCount": 1,
        // "totalDataCount": 0,
        // "totalPage": 1
        // }
        $curl->close();
        if($result['code'] == '00') {
            if($result['datas'] == '') {
                if($times >= 2) {
                    test_log('skxcmjk-2次，datas为空:'.$curl->response);
                    return ['status'=> 0, 'msg'=> '数据为空'];
                }
                $times += 1;
                return $this->skxcmjk($phone, $verification, $city_code, $times);
            }
            $datas = json_decode($result['datas'], true);
            if(isset($datas['code']) && $datas['code'] == 200) {
                if($datas['data'] == null || count($datas['data']) == 0) {
                    return ['status'=> 0, 'msg'=> '接口数据为空'];
                }
                if($datas['data']['status'] == 1) {
                    return ['status'=> 1, 'msg'=> '成功', 'data'=> $datas['data']['result']];
                }else if($datas['data']['status'] == 2) {
                    return ['status'=> 2, 'msg'=> $datas['data']['errorDesc']];
                }else{
                    test_tmp_log('skxcmjk-'. $res,__METHOD__,'2');
                    return ['status'=> 0, 'msg'=> $datas['data']['errorDesc']];
                }
            }else{
                test_tmp_log('skxcmjk-'.Config::get('upload.at_server'). $res,__METHOD__,'3');
                return ['status'=> 0, 'msg'=> $datas['errMsg']];
            }
            return ['status'=> 1, 'msg'=> $result['msg'], 'data'=> $result['datas']];
        }else{
            test_tmp_log('skxcmjk-'. $res,__METHOD__,'4');
            if($result['code'] == '14') { //签名错误，重新查询数据
                $this->_sk_request_token_clear();
                if($times >= 3) {
                    return ['status'=> 0, 'msg'=> '签名错误'];
                }
                $times += 1;
                test_tmp_log('skxcmjk-签名错误，重新查询'. $times,__METHOD__,'5');
                return $this->skxcmjk($phone, $verification, $city_code, $times);
            }
            return ['status'=> 0, 'msg'=> $result['msg']];
        }
    }

    //省核酸检测接口
    public function skhsjcjk($real_name, $id_card, $times=0)
    {
        $id_card = strtoupper($id_card); // 身份证x转大写
        $request_token_res = $this->getSkRequestToken();
        if($request_token_res['status'] == 0) {
            return ['status'=> 0, 'msg'=> $request_token_res['msg']];
        }
        // 目前反向代理,为了测试和正式环境都能调用
        if(Config::get('app.app_host') == 'dev') { //测试服务器
            return $this->forTestApi('post','skhsjcjk',['real_name'=>$real_name,'id_card'=>$id_card,'times'=>$times]);
        }else{
            $url = "https://interface.zjzwfw.gov.cn/gateway/api/001003001/dataSharing/8fGO7c2lb5S13Q0a.htm";
            //$url = "https://interface.zjzwfw.gov.cn/gateway/api/001003001029/dataSharing/ptpatientinfoSc.htm";
        }
        $curl = new Curl();
        $mtimestamp = time() * 1000; // 毫秒
        $sign = md5($this->app_key . $request_token_res['data'] . $mtimestamp);
        $curl->setHeader('appKey', $this->app_key);
        $curl->setHeader('sign', $sign);
        $curl->setHeader('requestTime', $mtimestamp);
        $curl->setHeader('Content-Type', 'application/json; charset=UTF-8');
        $data = [
            'patientname' => $real_name,
            'idcardNo' => $id_card,
        ];
        $data_json = json_encode($data);
        $curl->post($url, $data_json);
        if ($curl->error) {
            test_tmp_log('skhsjcjk-'. $curl->error_code .': '. $curl->error_message,__METHOD__,'1');
            return ['status'=> 0, 'msg'=> $curl->error_code .': '. $curl->error_message];
        } else {
            $res = $curl->response;
            // test_tmp_log('skhsjcjk-'. $res,__METHOD__);
            $result = json_decode($res, true);
        }
        $curl->close();
        if($result['code'] == "00") {
            return ['status'=> 1, 'msg'=> $result['msg'], 'data'=> $result['datas']];
        }else{
            test_tmp_log('skhsjcjk-'. $res,__METHOD__,'2');
            if($result['code'] == '14') { //签名错误，重新查询数据
                $this->_sk_request_token_clear();
                if($times >= 3) {
                    return ['status'=> 0, 'msg'=> '签名错误'];
                }
                $times += 1;
                test_tmp_log('skhsjcjk-签名错误，重新查询-'. $times,__METHOD__,'3');
                return $this->skhsjcjk($real_name, $id_card, $times);
            }
            return ['status'=> 0, 'msg'=> $result['msg']];
        }
    }
    
    //省新冠疫苗预防接种信息查询
    public function skxgymyfjzxxcx($id_card, $times=0)
    {
        $id_card = strtoupper($id_card); // 身份证x转大写
        $request_token_res = $this->getSkRequestToken();
        if($request_token_res['status'] == 0) {
            return ['status'=> 0, 'msg'=> $request_token_res['msg']];
        }
        // 目前反向代理,为了测试和正式环境都能调用
        if(Config::get('app.app_host') == 'dev') { //测试服务器
            return $this->forTestApi('post','skxgymyfjzxxcx',['id_card'=>$id_card,'times'=>$times]);
        }else{
            $url = "https://interface.zjzwfw.gov.cn/gateway/api/001003001/dataSharing/ObbOIJ7c3PkWs465.htm";
        }
        $curl = new Curl();
        $mtimestamp = time() * 1000; // 毫秒
        $sign = md5($this->app_key . $request_token_res['data'] . $mtimestamp);
        $curl->setHeader('appKey', $this->app_key);
        $curl->setHeader('sign', $sign);
        $curl->setHeader('requestTime', $mtimestamp);
        $curl->setHeader('Content-Type', 'application/json; charset=UTF-8');
        $data = [
            'idcardNo' => $id_card,
        ];
        $data_json = json_encode($data);
        $curl->post($url, $data_json);
        if ($curl->error) {
            test_tmp_log('skxgymyfjzxxcx-'. $curl->error_code .': '. $curl->error_message,__METHOD__,'1');
            return ['status'=> 0, 'msg'=> $curl->error_code .': '. $curl->error_message];
        } else {
            $res = $curl->response;
            //test_tmp_log('skxgymyfjzxxcx-'. $res);
            $result = json_decode($res, true);
        }
        $curl->close();
        if($result['code'] == "00") {
            if(isset($result['datas']['data'])) {
                return ['status'=> 1, 'msg'=> $result['msg'], 'data'=> $result['datas']['data']];
            }
            return ['status'=> 0, 'msg'=> $result['msg']];
        }else{
            test_tmp_log('skxgymyfjzxxcx-'. $res,__METHOD__,'2');
            if($result['code'] == '14') { //签名错误，重新查询数据
                $this->_sk_request_token_clear();
                if($times >= 3) {
                    return ['status'=> 0, 'msg'=> '签名错误'];
                }
                $times += 1;
                test_tmp_log('skxgymyfjzxxcx-签名错误，重新查询-'. $times,__METHOD__,'3');
                return $this->skxgymyfjzxxcx($id_card, $times);
            }
            return ['status'=> 0, 'msg'=> $result['msg']];
        }
    }
    
    //省司法厅律师事务所执业许可证
    public function sklssws($name, $credit_code, $times=0)
    {
        $request_token_res = $this->getSkRequestToken();
        if($request_token_res['status'] == 0) {
            return ['status'=> 0, 'msg'=> $request_token_res['msg']];
        }
        // 目前反向代理,为了测试和正式环境都能调用
        if(Config::get('app.app_host') == 'dev') { //测试服务器
            return $this->forTestApi('post','sklssws',['name'=>$name,'credit_code'=>$credit_code,'times'=>$times]);
        }else{
            $url = "https://interface.zjzwfw.gov.cn/gateway/api/001003001029/dataSharing/6s6uud3316rsnoa5.htm";
        }
        $curl = new Curl();
        $mtimestamp = time() * 1000; // 毫秒
        $sign = md5($this->app_key . $request_token_res['data'] . $mtimestamp);
        $curl->setHeader('appKey', $this->app_key);
        $curl->setHeader('sign', $sign);
        $curl->setHeader('requestTime', $mtimestamp);
        $curl->setHeader('Content-Type', 'application/json; charset=UTF-8');
        $data = [
            'officename'=> $name,
            'creditcode'=> $credit_code,
        ];
        $data_json = json_encode($data);
        $curl->post($url, $data_json);
        if ($curl->error) {
            test_tmp_log('sklssws-'. $curl->error_code .': '. $curl->error_message,__METHOD__,'1');
            return ['status'=> 0, 'msg'=> $curl->error_code .': '. $curl->error_message];
        } else {
            $res = $curl->response;
            $result = json_decode($res, true);
        }
        $curl->close();
        if($result['code'] == "00") {
            if(isset($result['datas'])) {
                if($result['datas'] == "[]") {
                    return ['status'=> 0, 'msg'=> '未查询到该律师事务所'];
                }
                $datas = json_decode($result['datas'], true);
                return ['status'=> 1, 'msg'=> '成功', 'data'=> $datas[0]];
            }
            return ['status'=> 0, 'msg'=> $result['msg']];
        }else{
            test_tmp_log('sklssws-'. $res,__METHOD__,'2');
            if($result['code'] == '14') { //签名错误，重新查询数据
                $this->_sk_request_token_clear();
                if($times >= 3) {
                    return ['status'=> 0, 'msg'=> '签名错误'];
                }
                $times += 1;
                return $this->sklssws($name, $credit_code, $times);
            }
            return ['status'=> 0, 'msg'=> $result['msg']];
        }
    }

    private function forTestApi($getpost='get',$function_name='',$data=[]){
        $map = [
            'skPhoneToJkm' => 'skPhoneToJkm',
            'skIdcardToJkm' => 'skIdcardToJkm',
            'skxcmdxjk' => 'skxcmdxjk',
            'skxcmjk' => 'skxcmjk',
            'skhsjcjk' => 'skhsjcjk',
            'skxgymyfjzxxcx' => 'skxgymyfjzxxcx',
            'sklssws' => 'sklssws'

        ];
        $test_function = $map[$function_name];
        if($test_function == ''){
            test_log('wu test_function');
            return ['status'=> 0, 'msg'=> 'wu test_function'];
        }
        // 
        $curl = new Curl();
        $url = "https://yqfk.yw.gov.cn/proxy/".$test_function;
        $curl->get($url, $data); // 这个都是get
        $res = $curl->response;
        $result = json_decode($res, true);
        $curl->close();
        if($result['code'] == 200){
            return ['status'=> 1, 'msg'=> '成功', 'data'=> $result['data'] ];
        }else{
            test_log($function_name.'forTestApi 错误');
            test_log($result);
            return ['status'=> 0, 'msg'=> $result['msg'] ];
        }
    }

}
