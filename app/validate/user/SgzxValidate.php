<?php

namespace app\validate\user;

use think\Validate;
use \behavior\IdentityCardTool;

class SgzxValidate extends Validate
{
    protected $rule = [
        'real_name' => 'require|min:2|checkRealName',
        'id_card' => 'require|checkIdCard',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'real_name.require' => '姓名必填',
        'real_name.min' => '姓名不允许一个字',
        'id_card.require' => '身份证必填',
    ];

    protected $scene = [
        'qgrkk' => ['real_name', 'id_card'],
    ];

    public function checkIdCard($value, $rule, $data)
    {
        if (IdentityCardTool::isValid($value)) {
            return true;
        } else {
            return '证件号码格式不准确';
        }
    }

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

}
