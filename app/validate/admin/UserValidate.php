<?php

namespace app\validate\admin;

use think\Validate;
use \behavior\IdentityCardTool;

class UserValidate extends Validate
{

    protected $rule = [
        'id' => 'require|integer',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'id.require' => '用户id必填',
    ];

    protected $scene = [
        'companyList' => ['id'],
    ];

}
