<?php

namespace app\validate\user;

use think\Validate;
use \behavior\IdentityCardTool;

class PlaceDeclareValidate extends Validate
{
    protected $rule = [
        'place_code' => 'require',
        'real_name' => 'require|min:2|checkRealName',
        'id_card' => 'require|max:20|checkIdCard',
        'phone' => 'require|checkPhone',
        'sms_code' => 'require|length:6',
        'type' => 'require|in:ymjz,jkm,hsjc,xcm',
        'sign' => 'require',
    ];
    
    protected $message = [
        'place_code.require' => '场所码必填',
        'real_name.require' => '姓名必填',
        'real_name.min' => '姓名不允许一个字',
        'id_card.require' => '证件号码必填',
        'phone.require' => '手机号必填',
        'sms_code.require' => '短信验证码必填',
        'sms_code.length' => '短信验证码必须6位',
        'type' => '类型必填',
        'sign' => '签名必填',
    ];
    
    protected $scene = [
        'placeDeclare' => ['place_code', 'real_name', 'id_card', 'phone', 'sms_code'],
        'result' => ['type', 'sign'],
        'detail' => ['sign'],
        'scanCode' => ['place_code', 'sign'],
    ];

    public function checkRealName($value, $rule, $data)
    {
        $match = "/^[\x{4e00}-\x{9fa5}A-Za-z0-9_·]+$/u"; //匹配中文数字字母
        $result = preg_match($match, $value);
        if($result == 0) {
            return '姓名不能包含特殊字符';
        }else{
            $match = '/\d+|\w+/';  //中国籍包含字母、数字
            $result = preg_match($match, $value);
            if($result) {
                return '姓名不能包含字母、数字';
            }
            return true;
        }
    }
    
    public function checkPhone($value, $rule, $data)
    {
        $match = '/^1[0-9]{10}$/';
        $result = preg_match($match, $value);
        if($result) {
            return true;
        }else{
            return '请填写正确的手机号码';
        }
    }

    public function checkIdCard($value, $rule, $data)
    {
        if (IdentityCardTool::isValid($value)) {
            return true;
        } else {
            return '身份证号码格式不准确';
        }
    }

}
