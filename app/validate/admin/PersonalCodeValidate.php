<?php

namespace app\validate\admin;

use think\Validate;
use \behavior\IdentityCardTool;

class PersonalCodeValidate extends Validate
{
    protected $rule = [
        'real_name' => 'require',
        'agent_name' => 'require',
        'agent_idcard' => 'require|checkCardId',
        'agent_phone' => 'require|checkPhone',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'real_name.require' => '姓名必填',
        'agent_name.require' => '代领人姓名必填',
        'agent_idcard.require' => '代领人身份证必填',
        'agent_phone.require' => '代领人手机号',
    ];

    protected $scene = [
        'update' => ['real_name', 'agent_name', 'agent_idcard', 'agent_phone'],
    ];

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

    public function checkCardId($value, $rule, $data)
    {
        if (IdentityCardTool::isValid($value)) {
            return true;
        } else {
            return '证件号码格式不准确';
        }
    }
}
