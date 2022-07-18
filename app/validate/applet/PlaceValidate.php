<?php

namespace app\validate\applet;

use think\Validate;

class PlaceValidate extends Validate
{
    protected $rule = [
        'name' => 'require|min:3',
        'short_name' => 'require|length:2,8',
        'place_classify' => 'require|in:company,gov,unit,social,other',
        'link_man' => 'require',
        'link_phone' => 'require|checkPhone',
        'addr' => 'require|min:3',
        'credit_code' => 'requireIf:place_classify,company',
        'yw_street_id' => 'require|integer',
        'place_type_id' => 'require|integer',
        'lng' => 'require',
        'lat' => 'require',
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
        'place_classify.require' => '场所分类必填',
        'link_man.require' => '联络员必填',
        'link_phone.require' => '联络员手机号必填',
        'addr.require' => '地址必填',
        'addr.min' => '地址不能少于3个字',
        'credit_code.requireIf' => '企业信用代码必填',
        'yw_street_id.require' => '镇街必填',
        'place_type_id.require' => '所属行业必填',
        'lng.require' => '经纬度必填',
        'lat.require' => '经纬度必填',
    ];

    protected $scene = [
        'save' => ['name', 'place_classify', 'link_man', 'link_phone', 'addr'],
        'applyV2' => ['name', 'short_name', 'place_classify', 'yw_street_id', 'place_type_id', 'place_type', 'credit_code', 'link_man', 'link_phone', 'addr', 'lng', 'lat'],
        'update' => ['name', 'short_name', 'place_classify', 'yw_street_id', 'place_type_id', 'place_type', 'credit_code', 'link_man', 'link_phone', 'addr', 'lng', 'lat'],
    ];

    public function checkPhone($value, $rule, $data)
    {
        if(preg_match('/^1[0-9]{10}$/', $value)) {
            return true;
        }else{
            return '手机号不正确';
        }
    }

}
