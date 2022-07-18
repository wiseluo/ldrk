<?php

namespace app\validate\admin;

use think\Validate;

class AdminCompanyValidate extends Validate
{

    protected $rule = [
        'company_id' => 'require|integer',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'company_id.require' => '企业id必填',
    ];

    protected $scene = [
        'follow' => ['company_id'],
        'unfollow' => ['company_id'],
    ];

}
