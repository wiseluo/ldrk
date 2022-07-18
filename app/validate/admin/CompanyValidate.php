<?php

namespace app\validate\admin;

use think\Validate;

class CompanyValidate extends Validate
{
    protected $rule = [
        'company_id' => 'require|integer',
        'id' => 'require|integer',
        'ids' => 'require',
        'check_frequency' => 'require|in:2,7,14,28,70',
        'classify_id' => 'require|integer',
        'staff_classify_id' => 'require|integer',
        'yw_street_id' => 'require|integer',
        'yw_street' => 'require',
        'addr' => 'require|min:3',
        'yw_street_id' => 'require|integer',
        'yw_street' => 'require',
        'community_id' => 'require|integer',
        'community' => 'require',
        'staff_id' => 'require|integer',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'company_id.require' => '企业id必填',
        'id.require' => '用户id必填',
        'ids.require' => '记录id必填',
        'check_frequency.require' => '检测频率必填',
        'classify_id' => '企业码类型必填',
        'yw_street_id.require' => '镇街必填',
        'yw_street.require' => '镇街必填',
        'community_id.require' => '村社必填',
        'community.require' => '村社必填',
        'addr.require' => '地址必填',
        'addr.min' => '地址不能少于3个字',
        'staff_id.require' => '员工id必填',
    ];

    protected $scene = [
        'update' => ['classify_id', 'yw_street_id', 'yw_street', 'community_id', 'community', 'addr'],
        'staff' => ['company_id'],
        'staffCompanyList' => ['id'],
        'checkFrequency' => ['ids', 'check_frequency'],
        'transferLink' => ['company_id', 'staff_id'],
        'batchUpdateCompanyClassify' => ['classify_id'],
        'batchUpdateCompanyStaffClassify' => ['staff_classify_id'],
    ];


}
