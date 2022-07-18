<?php

namespace app\validate\admin;

use think\Validate;

class CompanyStaffClassifyValidate extends Validate
{
    protected $rule = [
        'classify_name' => 'require',
        'gov_name' => 'require',
        'jinhua_full_name' => 'require',
        'jinhua_short_name' => 'require',
        'yiwu_name' => 'require',
        'check_frequency' => 'require',
        'check_frequency_text' => 'require',
        'sort' => 'require|integer',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'classify_id.require' => '企业类型id必填',
        'classify_name.require' => '企业类型名称必填',
        'gov_name.require' => '主管部门必填',
        'jinhua_full_name.require' => '金华全称必填',
        'jinhua_short_name.require' => '金华简称必填',
        'yiwu_name.require' => '义乌名称必填',
        'check_frequency.require' => '检测频率必填',
        'check_frequency_text.require' => '检测频率文本必填',
        'sort.require' => '显示顺序必填',
        'sort.integer' => '显示顺序必须是整数',
    ];

    protected $scene = [
        'save' => ['classify_id', 'classify_name', 'gov_name', 'jinhua_full_name', 'jinhua_short_name', 'yiwu_name', 'check_frequency', 'check_frequency_text', 'sort'],
        'update' => ['classify_id', 'classify_name', 'gov_name', 'jinhua_full_name', 'jinhua_short_name', 'yiwu_name', 'check_frequency', 'check_frequency_text', 'sort'],
    ];


}
