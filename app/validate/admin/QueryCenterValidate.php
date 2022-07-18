<?php

namespace app\validate\admin;

use think\Validate;
use \behavior\IdentityCardTool;

class QueryCenterValidate extends Validate
{
    protected $rule = [
        'id_card' => 'require|checkCardId',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'id_card.require' => '身份证号必填',
    ];

    protected $scene = [
        'healthInfo' => ['id_card'],
        'update' => ['name'],
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
