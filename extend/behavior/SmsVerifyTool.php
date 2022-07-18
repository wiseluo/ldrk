<?php

namespace behavior;

use think\facade\Cache;
use \behavior\SmsTool;

class SmsVerifyTool
{
    public function sendSmsCode($prefix, $phone)
    {
        $smscode_cache = Cache::store('file')->get($prefix .'_sms_'. $phone);
        if($smscode_cache) {
            $smscode_data = json_decode($smscode_cache, true);
            if(time() - $smscode_data['time'] < 60) {
                return ['status'=> 0, 'msg'=> '每1分钟可获取一次验证码，请稍候再来。'];
            }
        }

        // 随机6位数
        $smscode = rand(100000, 999999);
        $content = "您的验证码是：". $smscode ."，有效期为5分钟。如非本人操作，请忽略此短信。";
        $smsTool = new SmsTool();
        $res = $smsTool->sendSms($phone, $content);
        if($res['status'] == 1) {
            $sms_data = [
                'time' => time(),
                'smscode' => (string)$smscode,
                'count' => 0,
            ];
            Cache::store('file')->set($prefix .'_sms_'. $phone, json_encode($sms_data), 3000);
            return ['status'=> 1, 'msg'=> '短信发送成功'];
        }else{
            return ['status'=> 0, 'msg'=> '短信发送失败! 状态：' . $res['msg']];
        }
    }
    
    public function verifySmsCode($prefix, $phone, $smscode)
    {
        $smscode_cache = Cache::store('file')->get($prefix .'_sms_'. $phone);
        if($smscode_cache == null) {
            return ['status'=> 0, 'msg'=> '验证码不存在或已过期，请重新获取'];
        }
        $smscode_data = json_decode($smscode_cache, true);
        if($smscode_data['count'] > 3) {
            return ['status'=> 0, 'msg'=> '验证码错误超过3次，请用重新获取'];
        }
        if(!hash_equals($smscode_data['smscode'], $smscode)) { //可防止时序攻击的字符串比较
            $smscode_data['count'] += 1; //输入错误加一
            Cache::store('file')->set($prefix .'_sms_'. $phone, json_encode($smscode_data), 300);
            return ['status'=> 0, 'msg'=> '验证码错误'];
        }
        Cache::store('file')->delete($prefix .'_sms_'. $phone);
        return ['status'=> 1, 'msg'=> 'sms验证成功'];
    }
}
