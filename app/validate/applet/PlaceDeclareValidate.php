<?php

namespace app\validate\applet;

use think\Validate;

class PlaceDeclareValidate extends Validate
{
    protected $rule = [
        'place_code' => 'require',
        'sms_code' => 'require|length:6',
        'type' => 'require|in:ymjz,jkm,hsjc,xcm,ryxx',
        'sign' => 'require',
        'phone' => 'require|checkPhone',
        'id_card' => 'require',
    ];
    
    protected $message = [
        'place_code.require' => '场所码code必填',
        'sms_code.require' => '短信验证码必填',
        'sms_code.length' => '短信验证码必须6位',
        'type.require' => '类型必填',
        'sign.require' => '签名必填',
        'phone.require' => '手机号必填',
        'id_card.require' => '身份证必传',
    ];
    
    protected $scene = [
        'result' => ['type', 'sign'],
        'resultWhole' => ['sign'],
        'scanCode' => ['place_code'],
        'replaceScan' => ['uniqid', 'place_code'],
        'xcmResult' => ['sign', 'phone', 'sms_code'],
        'resultForQrcode' => ['phone', 'sms_code','id_card'],
        'ryxxResult' => ['sign'],
        'hsjcResultLog' => ['id_card'],
        'reverseScan' => ['place_code'],
    ];

    public function checkPhone($value, $rule, $data)
    {
        if(preg_match('/^1[0-9]{10}$/', $value)) {
            return true;
        }else{
            return '手机号不正确';
        }
    }
}
