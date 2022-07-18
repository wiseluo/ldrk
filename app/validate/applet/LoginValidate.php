<?php

namespace app\validate\applet;

use think\Validate;
use \behavior\IdentityCardTool;

class LoginValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'    =>    ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'code' => 'require',
        'openid' => 'require',
        'phone' => 'require|checkPhone',
        'real_name' => 'require',
        'id_card' => 'require',
        'encryptedData' => 'require',
        'iv' => 'require',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'code.require' => '授权码必填',
        'openid.require' => '微信标识必填',
        'phone.require' => '手机号必填',
        'real_name.require' => '姓名必填',
        'id_card.require' => '身份证必填',
        'encryptedData.require' => '加密数据必填',
        'iv.require' => '初始向量必填',
    ];

    protected $scene = [
        'login' => ['code'],
        'authphone' => ['code', 'openid'],
        'encryptedPhone' => ['openid', 'code', 'encryptedData', 'iv'],
        'register' => ['phone', 'openid', 'real_name', 'id_card'],
        'registerIdcardRecogn' => ['real_name', 'id_card'],
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
