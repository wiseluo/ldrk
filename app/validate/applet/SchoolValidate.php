<?php

namespace app\validate\applet;

use think\Validate;

class SchoolValidate extends Validate
{
    protected $rule = [
        'school_code' => 'require',
        'name' => 'require',
        'addr' => 'require|min:3',
        'yw_street_id' => 'require|integer',
        'yw_street' => 'require',
        'community_id' => 'require|integer',
        'community' => 'require',
        'credit_code' => 'require'
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'school_code.require' => '学校code必填',
        'name.require' => '学校名称必填',
        'credit_code.require' => '信用码必填',
        'yw_street_id.require' => '镇街必填',
        'yw_street.require' => '镇街必填',
        'community_id.require' => '村社必填',
        'community.require' => '村社必填',
        'addr.require' => '地址必填',
        'addr.min' => '地址不能少于3个字',
    ];

    protected $scene = [
        'save' => ['name', 'credit_code', 'yw_street_id', 'yw_street', 'community_id', 'community', 'addr'],
        'update' => ['yw_street_id', 'yw_street', 'community_id', 'community', 'addr'],
        'whiteList' => ['credit_code']
    ];
}
