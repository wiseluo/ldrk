<?php

namespace app\validate\admin;

use think\Validate;

class SmsValidate extends Validate
{
    protected $rule = [
        'prefix' => 'require|in:smslogin',
        'phone' => 'require|checkPhone',
    ];
    
    protected $message = [
        'prefix.require' => '前缀必填',
        'prefix.in' => '前缀错误',
        'phone.require' => '手机号必填',
    ];
    
    protected $scene = [
        'smsCode' => ['prefix', 'phone'],
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
