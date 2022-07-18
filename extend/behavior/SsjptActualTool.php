<?php

namespace behavior;

use Curl\Curl;
use think\facade\Config;
use app\dao\WxtokenDao;
use think\facade\Cache;

// 省公共数据平台工具类（实时版接口）
// 主要接口为 健康码的实时获取接口
class SsjptActualTool
{
    // 义乌市疫情系统（实时版接口）
    private $app_key = 'A330782384534202108016644';
    private $app_secret = 'a389ba3cb63e46a8bab90423cac09fe8';


    /**
     * 获取刷新密钥和请求密钥12分钟一次，用定时任务更新
     */
    public function getIrsSecret()
    {
        $wxtoken = app()->make(WxtokenDao::class);
        $token = $wxtoken->get(['type'=> 'sksecret_actual']);
        if($token && $token['refresh_expires'] >= time()*1000) { //刷新秘钥存在且未过期
            $new_token_res = $this->skIrsSecretBySec($token['refresh_token']);
            test_tmp_log('实时版 skIrsSecretBySec-'. json_encode($new_token_res),__METHOD__,'1');
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
            'type' => 'sksecret_actual',
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
            test_tmp_log('实时版 skIrsSecretBySec-'. $curl->error_code .': '. $curl->error_message,__METHOD__,'1');
            return ['status'=> 0, 'msg'=> $curl->error_code .': '. $curl->error_message];
        } else {
            $res = $curl->response;
            //test_log('实时版 skIrsSecretBySec-'. $res);
            $result = json_decode($res, true);
        }
        $curl->close();
        if(isset($result['code']) && $result['code'] == "00") {
            return ['status'=> 1, 'msg'=> '成功', 'data'=> $result['datas']];
        }else{
            test_tmp_log('实时版 skIrsSecretBySec-'. $res,__METHOD__,'2');
            // if($result['code'] == "03") { //签名错误，并发重复请求了
            //     return ['status'=> 2, 'msg'=> $result['msg']];
            // }
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
            test_tmp_log('appKey:'.$this->app_key.',实时版 skIrsSecretByKey-'. $curl->error_code .': '. $curl->error_message,__METHOD__,'1');
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
            test_tmp_log('appKey:'.$this->app_key.'实时版 skIrsSecretByKey-'. $res,__METHOD__,'2');
            return ['status'=> 0, 'msg'=> $result['msg']];
        }
    }

    //获取省库请求秘钥
    public function getSkRequestToken()
    {
        if(Config::get('app.app_host') == 'dev') { //测试服务器
            $url = "http://localhost:8083/sk_sjpt/token_actual";
            $curl = new Curl();
            $curl->get($url);
            
            if ($curl->error) {
                test_tmp_log('实时版 sksjpt_token-'. $curl->error_code .': '. $curl->error_message,__METHOD__,'1');
                return ['status'=> 0, 'msg'=> $curl->error_code .': '. $curl->error_message];
            } else {
                $res = $curl->response;
                //test_tmp_log('实时版 sksjpt_token-'. $res,__METHOD__,'2');
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
        // $token = app()->make(WxtokenDao::class)->get(['type'=> 'sksecret_actual']);
        // if($token) {
        //     return ['status'=> 1, 'msg'=> '成功', 'data'=> $token['access_token']];
        // }else{
        //     test_log('实时版 sksjpt_token-秘钥不存在，定时任务未设置');
        //     return ['status'=> 0, 'msg'=> '秘钥不存在'];
        // }
        $access_token = $this->_sk_request_token_cache();
        if($access_token) {
            return ['status'=> 1, 'msg'=> '成功', 'data'=> $access_token];
        }else{
            test_tmp_log('实时版 sksjpt_token-秘钥不存在，定时任务未设置',__METHOD__,'1');
            return ['status'=> 0, 'msg'=> '秘钥不存在'];
        }
    }
    private function _sk_request_token_cache(){
        $cache = Cache::get('access_token_sksecret_actual');
        if($cache){
            return $cache;
        }else{
            $token_info = app()->make(WxtokenDao::class)->get(['type'=> 'sksecret_actual']);
            if($token_info){
                Cache::set('access_token_sksecret_actual',$token_info['access_token'],720);
                return $token_info['access_token'];
            }
        }
    }
    private function _sk_request_token_clear(){
        Cache::delete('access_token_sksecret_actual');
    }
    // "宁波市", "330200" "温州市", "330300" "嘉兴市", "330400" "湖州市", "330500" "绍兴市", "330600" "金华市", "330700" "衢州市", "330800" 舟山市", "330900" "台州市", "331000" "丽水市", "331100"
    //全省健康码基本信息查询(身份证号)
    public function skIdcardAndPhoneToJkm($id_card,$phone, $times=0,$is_all=0)
    {
        $id_card = strtoupper($id_card); // 身份证x转大写
        $request_token_res = $this->getSkRequestToken();
        if($request_token_res['status'] == 0) {
            return ['status'=> 0, 'msg'=> $request_token_res['msg']];
        }
        $curl = new Curl();
        // 目前反向代理,为了测试和正式环境都能调用
        if(Config::get('app.app_host') == 'dev') { //测试服务器
            return $this->forTestApi('post','skIdcardAndPhoneToJkm',['id_card'=>$id_card,'phone'=>$phone,'is_all'=>$is_all]);
        }else{
            $url = "https://interface.zjzwfw.gov.cn/gateway/api/001003001/dataSharing/M3dfkN6d88mWekf4.htm";
        }
        $mtimestamp = time() * 1000; // 毫秒
        $sign = md5($this->app_key . $request_token_res['data'] . $mtimestamp);
        $curl->setHeader('appKey', $this->app_key);
        $curl->setHeader('sign', $sign);
        $curl->setHeader('requestTime', $mtimestamp);
        $curl->setHeader('Content-Type', 'application/json; charset=UTF-8');
        $data = [
            // 'additional' => [],
            'cityNo' => '330700', // 金华市
            'idcardNo' => $id_card,
            'idcardType' => 'IDENTITY_CARD',
            'phone' => $phone,
        ];
        $data_json = json_encode($data);
        $curl->post($url, $data_json);
        if ($curl->error) {
            test_tmp_log('skIdcardAndPhoneToJkm-'. $curl->error_code .': '. $curl->error_message,__METHOD__,'1');
            return ['status'=> 0, 'msg'=> $curl->error_code .': '. $curl->error_message];
        } else {
            $res = $curl->response;
            // test_log('skIdcardAndPhoneToJkm-'. $res);
            $result = json_decode($res, true);
        }
        $curl->close();
        if($result['code'] == "00") {
            $datas = json_decode($result['datas'], true);
            if($datas['success'] == true) {
                return ['status'=> 1, 'msg'=> '成功', 'data'=> $datas['data'] ];
            }else{
                return ['status'=> 0, 'msg'=> $datas['errMsg']];
            }
        }else{
            test_tmp_log('skIdcardAndPhoneToJkm-'. $res,__METHOD__,'2');
            if($result['code'] == '14') { //签名错误，重新查询数据
                $this->_sk_request_token_clear();
                if($times >= 3) {
                    // 触发刷新
                    $remain_second = 60 - Date('s');
                    system_error_log(__METHOD__,'省库实时秘钥查询3次都失败,一分钟内将触发重新获取,预计影响秒数：'.$remain_second.'s','一分钟内将触发重新获取');
                    return ['status'=> 0, 'msg'=> '签名错误'];
                }
                $times += 1;
                test_tmp_log('skIdcardAndPhoneToJkm-签名错误，重新查询'. $times,__METHOD__,'3');
                return $this->skIdcardAndPhoneToJkm($id_card,$phone, $times);
            }
            if($result['code'] == '11') { // 请求秘钥不在有效期内
                $this->_sk_request_token_clear();
                $remain_second = 60 - Date('s');
                system_error_log(__METHOD__,'省库(实时)秘钥请求秘钥不在有效期内,一分钟内将触发重新获取,预计影响秒数：'.$remain_second.'s','一分钟内将触发重新获取');
                return ['status'=> 0, 'msg'=> '签名错误'];
            }
            return ['status'=> 0, 'msg'=> $result['msg']];
        }
    }
    private function forTestApi($getpost='get',$function_name='',$data=[]){
        $map = [
            'skIdcardAndPhoneToJkm' => 'skIdcardAndPhoneToJkm',
            'skGetHsjcCollectTime' => 'skGetHsjcCollectTime',
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

    //通过身份证号码，查询核酸采样数据查询，全省全量，2022年5月10日之后
    public function skGetHsjcCollectTime($id_card,$times=0)
    {
        $request_token_res = $this->getSkRequestToken();
        if($request_token_res['status'] == 0) {
            return ['status'=> 0, 'msg'=> $request_token_res['msg']];
        }
        $curl = new Curl();
        // 目前反向代理,为了测试和正式环境都能调用
        if(Config::get('app.app_host') == 'dev') { //测试服务器
            return $this->forTestApi('post','skGetHsjcCollectTime',['id_card'=>$id_card,$times]);
        }else{
            $url = "https://interface.zjzwfw.gov.cn/gateway/api/proxy/001003001/outbreak/11a4deA89984ooq6.htm";
        }
        $mtimestamp = time() * 1000; // 毫秒
        $sign = md5($this->app_key . $request_token_res['data'] . $mtimestamp);
        $curl->setHeader('appKey', $this->app_key);
        $curl->setHeader('sign', $sign);
        $curl->setHeader('requestTime', $mtimestamp);
        $curl->setHeader('Content-Type', 'application/json; charset=UTF-8');
        $data = [
            // 'additional' => [],
            'idCardNo' => $id_card,
        ];
        $data_json = json_encode($data);
        $curl->post($url, $data_json);
        if ($curl->error) {
            test_tmp_log('skGetHsjcCollectTime-'. $curl->error_code .': '. $curl->error_message,__METHOD__,'1');
            return ['status'=> 0, 'msg'=> $curl->error_code .': '. $curl->error_message];
        } else {
            $res = $curl->response;
            //test_tmp_log('skGetHsjcCollectTime-'. $res,__METHOD__,'2');
            $result = json_decode($res, true);
        }
        $curl->close();
        if($result['code'] == "00") {
            $datas = json_decode($result['datas'], true);
            if($datas['success'] == true) {
                return ['status'=> 1, 'msg'=> '成功', 'data'=> $datas['data'] ];
            }else{
                return ['status'=> 0, 'msg'=> $datas['errMsg']];
            }
        }else{
            test_tmp_log('skGetHsjcCollectTime-'. $res,__METHOD__,'2');
            if($result['code'] == '14') { //签名错误，重新查询数据
                $this->_sk_request_token_clear();
                if($times >= 3) {
                    // 触发刷新
                    $remain_second = 60 - Date('s');
                    system_error_log(__METHOD__,'省库实时秘钥查询3次都失败,一分钟内将触发重新获取,预计影响秒数：'.$remain_second.'s','一分钟内将触发重新获取');
                    return ['status'=> 0, 'msg'=> '签名错误'];
                }
                $times += 1;
                test_tmp_log('skGetHsjcCollectTime-签名错误，重新查询'. $times,__METHOD__,'3');
                return $this->skGetHsjcCollectTime($id_card, $times);
            }
            if($result['code'] == '11') { // 请求秘钥不在有效期内
                $this->_sk_request_token_clear();
                $remain_second = 60 - Date('s');
                system_error_log(__METHOD__,'省库(实时)秘钥请求秘钥不在有效期内,一分钟内将触发重新获取,预计影响秒数：'.$remain_second.'s','一分钟内将触发重新获取');
                return ['status'=> 0, 'msg'=> '签名错误'];
            }
            return ['status'=> 0, 'msg'=> $result['msg']];
        }
    }
}
