<?php

namespace app\validate\admin;

use think\Validate;

class BarrierDistrictValidate extends Validate
{
    protected $rule = [
        'province_id' => 'require|integer',
        'city_id' => 'require|integer',
        'county_id' => 'require|integer',
        'street_id' => 'require|integer',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'province_id.require' => '省必填',
        'city_id.require' => '市必填',
        'county_id.require' => '县必填',
        'street_id.require' => '镇街必填',
    ];

    protected $scene = [
        'save' => ['province_id'],
        'update' => ['province_id'],
    ];


}
