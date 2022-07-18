<?php

namespace app\validate\applet;

use think\Validate;

class CompanyValidate extends Validate
{
    protected $rule = [
        'classify_id' => 'require|integer',
        //'company_classify' => 'require|in:company,lawoffice,logistics,business,build,education,other,importcompany,gov,stateown,units,nursing,nearroads',
        'name' => 'require',
        'addr' => 'require|min:3',
        'yw_street_id' => 'require|integer',
        'yw_street' => 'require',
        'community_id' => 'require|integer',
        'community' => 'require',
        'credit_code' => 'require|length:18',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'classify_id' => '企业码类型必填',
        'name.require' => '企业码名称必填',
        'credit_code.require' => '统一企业信用代码必填',
        'credit_code.length' => '统一企业信用代码必须18位',
        'yw_street_id.require' => '镇街必填',
        'yw_street.require' => '镇街必填',
        'community_id.require' => '村社必填',
        'community.require' => '村社必填',
        'addr.require' => '地址必填',
        'addr.min' => '地址不能少于3个字',
    ];

    protected $scene = [
        'save' => ['classify_id', 'credit_code', 'name', 'yw_street_id', 'yw_street', 'community_id', 'community', 'addr'],
        'update' => ['classify_id', 'yw_street_id', 'yw_street', 'community_id', 'community', 'addr'],
        'classifyVerify' => ['classify_id', 'credit_code'],
    ];

}
