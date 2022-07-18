<?php

namespace app\validate\admin;

use think\Validate;

class RiskDistrictProValidate extends Validate
{
    protected $rule = [
        'province_id' => 'require|integer',
        'city_id' => 'require|integer',
        'county_id' => 'require|integer',
        'street_id' => 'require|integer',
        'address' => 'require',
        'risk_level' => 'require|in:low,middling,high',
        'start_date' => 'require|dateFormat:Y-m-d',
        'end_date' => 'require|dateFormat:Y-m-d',
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
        'address.require' => '详细地址必填',
        'risk_level.require' => '风险等级必填',
        'start_date.require' => '开始时间必填',
        'end_date.require' => '截止时间必填',
    ];

    protected $scene = [
        'save' => ['province_id', 'city_id', 'risk_level', 'start_date'],
        'update' => ['risk_level', 'start_date'],
    ];


}
