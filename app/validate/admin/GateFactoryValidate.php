<?php

namespace app\validate\admin;

use think\Validate;

class GateFactoryValidate extends Validate
{
    protected $rule = [
        'name' => 'require|min:3',
        'link_man' => 'require',
        'link_phone' => 'require|checkPhone',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'name.require' => '名称必填',
        'link_man.require' => '联系人必填',
        'link_phone.require' => '联系电话必填',
    ];

    protected $scene = [
        'save' => ['name','link_man','link_phone'],
        'update' => ['name','link_man','link_phone'],
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
