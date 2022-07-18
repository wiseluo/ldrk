<?php
use think\facade\Env;
return [
    //小程序 appid
    'applet_app_id' => Env::get('wechat.applet_app_id', ''),
    //小程序密钥
    'applet_app_secret' => Env::get('wechat.applet_app_secret', ''),
    //商户号
    'mch_id' => '',
    //证书序列号
    'cert_serialno' => '',
];
