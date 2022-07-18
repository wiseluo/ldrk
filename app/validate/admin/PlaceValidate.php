<?php

namespace app\validate\admin;

use think\Validate;

class PlaceValidate extends Validate
{
    protected $rule = [
        'name' => 'require|min:3',
        'short_name' => 'require|length:2,8',
        'yw_street_id' => 'require|integer',
        'place_classify' => 'require|in:company,gov,unit,social,other',
        'link_man' => 'require',
        'link_phone' => 'require|checkPhone',
        'addr' => 'require',
        'superior_gov' => 'require',
        'credit_code' => 'requireIf:place_classify,company',
        'place_type_id' => 'require|integer',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'name.require' => '场所名称必填',
        'name.min' => '场所名称不能少于3个字',
        'short_name.require' => '简称必填',
        'short_name.length' => '简称长度为2到8个字',
        'yw_street_id.require' => '镇街必填',
        'place_classify.require' => '场所分类必填',
        'link_man.require' => '联络员必填',
        'link_phone.require' => '联络员手机号必填',
        'addr.require' => '地址必填',
        'superior_gov.require' => '上级主管部门',
        'credit_code.requireIf' => '企业信用代码必填',
        'place_type_id.require' => '所属行业必填',
    ];

    protected $scene = [
        'save' => ['name', 'short_name', 'place_classify', 'yw_street_id', 'place_type_id', 'place_type', 'link_man', 'link_phone', 'addr'],
        'update' => ['name', 'short_name', 'place_classify', 'yw_street_id', 'place_type_id', 'place_type', 'link_man', 'link_phone', 'addr'],
    ];

    public function checkPhone($value, $rule, $data)
    {
        $match = '/^1[0-9]{10}$/';
        $result = preg_match($match, $value);
        if($result) {
            return true;
        }else{
            return '请填写正确的手机号码';
        }
    }

}
