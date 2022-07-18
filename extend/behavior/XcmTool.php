<?php

namespace behavior;

use Curl\Curl;
use think\facade\Config;

//行程码接口工具类
class XcmTool
{
    public function __construct()
    {
        $this->key = '9@nr!#G8*Kuw';
    }

    /*
     * 行程码短信接口
    */
    public function xcmdxjk($phone = '')
    {
        if(empty($this->key)) {
            return ['status'=> 0, 'msg'=> '签名密匙未配置'];
        }
        if(empty($phone)) {
            return ['status'=> 0, 'msg'=> '手机号不能为空'];
        }
        
        $curl = new Curl();
        // 目前反向代理,为了测试和正式环境都能调用
        if(Config::get('app.app_host') == 'dev') { //测试服务器
            $url = "http://localhost:8083/ESBWeb/servlets/xcmdxjk@1.0";
        }else{
            $url = 'http://172.45.7.23/ESBWeb/servlets/xcmdxjk@1.0';
        }
        $sendTime = date('Y-m-d H:i:s');
        $secret = md5($this->key . $sendTime);
        $data = [
            'phone' => $phone,
            'queryId' => md5(uniqid(microtime(true),true)),
            'sendTime' => $sendTime,
            'secert' => $secret,
        ];
        $curl->get($url, $data);
        if($curl->error) {
            test_log('xcmdxjk-error'. $curl->response);
            return ['status'=> 0, 'msg'=> $curl->error_code . $curl->error_message];
        }else{
            $res = $curl->response;
            //test_log($res);
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
                    return ['status'=> 0, 'msg'=> $datas['data']['errorDesc']];
                }
            }else{
                return ['status'=> 0, 'msg'=> $datas['errMsg']];
            }
        }else{
            test_log('xcmdxjk-'. $res);
            return ['status'=> 0, 'msg'=> $result['msg']];
        }
    }
    
    /*
     * 行程码接口
    */
    public function xcmjk($phone = '', $verification = '', $city_code = '0')
    {
        if(empty($this->key)) {
            return ['status'=> 0, 'msg'=> '签名密匙未配置'];
        }
        if(empty($phone)) {
            return ['status'=> 0, 'msg'=> '手机号不能为空'];
        }
        
        $curl = new Curl();
        // 目前反向代理,为了测试和正式环境都能调用
        if(Config::get('app.app_host') == 'dev') { //测试服务器
            $url = "http://localhost:8083/ESBWeb/servlets/xcmjk@1.0";
        }else{
            $url = 'http://172.45.7.23/ESBWeb/servlets/xcmjk@1.0';
        }
        $sendTime = date('Y-m-d H:i:s');
        $secret = md5($this->key . $sendTime);
        $data = [
            'phone' => $phone,
            'queryId' => md5(uniqid(microtime(true),true)),
            'sendTime' => $sendTime,
            'secert' => $secret,
            'verification' => $verification,
            'type' => '233',
            'city' => $city_code,
        ];
        $curl->get($url, $data);
        if($curl->error) {
            test_log('xcmjk-'. $curl->error_code . $curl->error_message);
            return ['status'=> 0, 'msg'=> $curl->error_code . $curl->error_message];
        }else{
            $res = $curl->response;
            //test_log($res);
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
                    return ['status'=> 0, 'msg'=> $datas['data']['errorDesc']];
                }
            }else{
                return ['status'=> 0, 'msg'=> $datas['errMsg']];
            }
            return ['status'=> 1, 'msg'=> $result['msg'], 'data'=> $result['datas']];
        }else{
            test_log('xcmjk-'. $res);
            return ['status'=> 0, 'msg'=> $result['msg']];
        }
    }

}
