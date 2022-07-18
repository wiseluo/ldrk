<?php

namespace app\validate\admin;

use think\Validate;

class CompanyClassifyValidate extends Validate
{
    protected $rule = [
        'classify_name' => 'require',
        'sort' => 'require|integer',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'classify_name.require' => '企业类型名称必填',
        'sort.require' => '显示顺序必填',
        'sort.integer' => '显示顺序必须是整数',
    ];

    protected $scene = [
        'save' => ['classify_name', 'sort'],
        'update' => ['classify_name', 'sort'],
    ];


}
