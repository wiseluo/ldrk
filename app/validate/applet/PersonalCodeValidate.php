<?php

namespace app\validate\applet;

use think\Validate;
use \behavior\IdentityCardTool;

class PersonalCodeValidate extends Validate
{
    protected $rule = [
        'real_name' => 'require',
        'id_card' => 'require|checkCardId',
        'phone' => 'checkIfPhone',
        'urgent_phone' => 'checkIfUrgentPhone',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'real_name.require' => '姓名必填',
        'id_card.require' => '身份证必填',
    ];

    protected $scene = [
        'save' => ['real_name', 'id_card', 'phone', 'urgent_phone'],
        'update' => ['real_name', 'phone', 'urgent_phone'],
    ];

    public function checkPhone($value, $rule, $data)
    {
        if(preg_match('/^1[0-9]{10}$/', $value)) {
            return true;
        }else{
            return '手机号不正确';
        }
    }
    
    public function checkIfPhone($value, $rule, $data)
    {
        if($value) {
            if(preg_match('/^1[0-9]{10}$/', $value)) {
                return true;
            }else{
                return '个人手机号不正确';
            }
        }
        return true;
    }
    
    public function checkIfUrgentPhone($value, $rule, $data)
    {
        if($value) {
            if(preg_match('/^1[0-9]{10}$/', $value)) {
                return true;
            }else{
                return '应急联系电话不正确';
            }
        }
        return true;
    }
    
    public function checkCardId($value, $rule, $data)
    {
        if (IdentityCardTool::isValid($value)) {
            return true;
        } else {
            return '证件号码格式不准确，请注意大小写';
        }
    }
}
