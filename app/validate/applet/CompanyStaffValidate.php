<?php

namespace app\validate\applet;

use think\Validate;

class CompanyStaffValidate extends Validate
{
    protected $rule = [
        'company_id' => 'require|integer',
        'company_code' => 'require|length:12',
        'ids' => 'require',
        'id' => 'require|integer',
        'remind_type' => 'require|in:seven_day,seventy_day',
        'staff_classify_id' => 'require|integer',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'company_id.require' => '企业id必填',
        'company_code.require' => '企业码必填',
        'ids.require' => '记录id必填',
        'id.require' => '记录id必填',
        'remind_type.require' => '提醒类型必填',
        'staff_classify_id.require' => '员工类型必填',
    ];

    protected $scene = [
        'scanGetName' => ['company_code'],
        'scanCode' => ['company_code', 'staff_classify_id'],
        'batchDelete' => ['ids'],
        'linkTransfer' => ['id'],
        'oneclickRemind' => ['remind_type'],
        'checkFrequency' => ['ids', 'staff_classify_id'],
        'userCheckFrequency' => ['company_id', 'staff_classify_id'],
    ];

}
