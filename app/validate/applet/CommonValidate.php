<?php

namespace app\validate\applet;

use think\Validate;

class CommonValidate extends Validate
{
    protected $rule = [
        'credit_code' => 'require',
        'yw_street_id' => 'require|integer',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'credit_code.require' => '企业信用代码必填',
        'yw_street_id.require' => '镇街必填',
    ];

    protected $scene = [
        'enterpriseInfo' => ['credit_code'],
        'communityList' => ['yw_street_id'],
    ];
    

}
