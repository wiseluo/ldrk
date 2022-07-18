<?php

namespace app\validate\gate;

use think\Validate;

class GateDeclareValidate extends Validate
{
    protected $rule = [
        'gate_code' => 'require',
        'real_name' => 'require',
        'id_card' => 'require',
        'phone' => 'require|checkPhone',
        'create_datetime' => 'require',
        'sign' => 'require',
    ];
    
    protected $message = [
        'gate_code.require' => '闸机码必填',
        'phone.require' => '手机号必填',
        'real_name.require' => '姓名必填',
        'id_card.require' => '身份证必填',
        'create_datetime.require' => '过闸时间必填',
        'sign.require' => '扫码签名必填',
    ];
    
    protected $scene = [
        'addHistory' => ['real_name','id_card', 'phone', 'gate_code','create_datetime'],
        'resultWhole' => ['sign'],
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
