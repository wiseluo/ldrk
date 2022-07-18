<?php

namespace behavior;

use Curl\Curl;
use think\facade\Config;

class SmsTool
{
    private $apId = ''; //短信账号
    private $secretKey = ''; //短信密码
    private $ecName = '';
    private $smsUrl = '';
    private $sign = '';
    private $addSerial = '';

    public function __construct()
    {
        $this->apId = Config::get('sms.apId');
        $this->secretKey = Config::get('sms.secretKey');
        $this->ecName = Config::get('sms.ecName');
        $this->smsUrl = Config::get('sms.smsUrl');
        $this->sign = Config::get('sms.sign');
        $this->addSerial = Config::get('sms.addSerial');
    }

    /*
     * mobiles 收信手机号码。英文逗号分隔，每批次限5000个号码，例：“13800138000,13800138001,13800138002”
     * content 短信内容。如content中存在双引号，请务必使用转义符\在报文中进行转义（使用JSON转换工具转换会自动增加转义符），否则会导致服务端解析报文异常
    */
    public function sendSms($mobiles = '', $content = '')
    {
        if(empty($this->apId) || empty($this->secretKey)) {
            return ['status'=> 0, 'msg'=> '短信账号密码未配置'];
        }
        if(empty($mobiles) || empty($content)) {
            return ['status'=> 0, 'msg'=> '手机号内容为空'];
        }
        
        $url = $this->smsUrl .'/sms/norsubmit';
        $mac = $this->ecName. $this->apId. $this->secretKey. $mobiles. $content. $this->sign. $this->addSerial;
        $md5_mac = strtolower(md5($mac));

        $curl = new Curl();
        $message_data = [
            'ecName' => $this->ecName,
            'apId' =>  $this->apId,
            'secretKey' =>  $this->secretKey,
            'mobiles' => $mobiles,
            'content' => $content,
            'sign' => $this->sign,
            'addSerial' =>  $this->addSerial,
            'mac' => $md5_mac,
        ];
        $message_json = json_encode($message_data);
        $base64_json = base64_encode($message_json);
        $curl->post($url, $base64_json);
        if($curl->error) {
            return ['status'=> 0, 'msg'=> $curl->error_code . $curl->error_message];
        }else{
            $res = $curl->response;
            //var_dump($res);
            $result = json_decode($res, true);
        }
        $curl->close();
        if($result['success'] == true) {
            return ['status'=> 1, 'msg'=> $result['rspcod'], 'data'=> $result['msgGroup']];
        }else{
            return ['status'=> 0, 'msg'=> $result['rspcod']];
        }
    }
    
}
