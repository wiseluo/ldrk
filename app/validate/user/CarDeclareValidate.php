<?php

namespace app\validate\user;

use think\Validate;
use \behavior\IdentityCardTool;

class CarDeclareValidate extends Validate
{
    protected $rule = [
        'real_name' => 'require|min:2|checkRealName',
        'id_card' => 'require|max:20|checkIdCard',
        'phone' => 'require|checkPhone',
        'sms_code' => 'require|length:6',
        'plate_number' => 'require',
    ];
    
    protected $message = [
        'real_name.require' => '姓名必填',
        'real_name.min' => '姓名不允许一个字',
        'id_card.require' => '证件号码必填',
        'phone.require' => '手机号必填',
        'sms_code.require' => '短信验证码必填',
        'sms_code.length' => '短信验证码必须6位',
        'plate_number.require' => '车牌号必填',
    ];
    
    protected $scene = [
        'post' => ['real_name', 'id_card', 'phone', 'sms_code','plate_number']
        
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
        // if($data['card_type'] == 'id') { //身份证类型
            if (IdentityCardTool::isValid($value)) {
                return true;
            } else {
                return '身份证号码格式不准确';
            }
        // }else{
        //     $match = "/^[\x{4e00}-\x{9fa5}A-Za-z0-9_]+$/u"; //匹配中文数字字母
        //     $result = preg_match($match, $value);
        //     if($result == 0) {
        //         return '证件号不能包含特殊字符';
        //     }else{
        //         return true;
        //     }
        // }
        return true;
    }

    public function checkAddress($value, $rule, $data)
    {
        $match = "/^[\x{4e00}-\x{9fa5}A-Za-z0-9_]+$/u"; //匹配中文数字字母
        $result = preg_match($match, $value);
        if($result == 0) {
            return '地址不能包含特殊字符';
        }else{
            return true;
        }
    }

    public function checkArrivalSign($value, $rule, $data)
    {
        $match = "/^[\x{4e00}-\x{9fa5}A-Za-z0-9_]+$/u"; //匹配中文数字字母
        $result = preg_match($match, $value);
        if($result == 0) {
            return '车次/航班/车牌不能包含特殊字符';
        }else{
            return true;
        }
    }
    
    public function checkReturnTime($value, $rule, $data)
    {
        if(strtotime($value) < strtotime($data['leave_time'])) {
            return '返义时间要大于等于离义时间';
        }
        return true;
    }
}
