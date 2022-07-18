<?php

namespace app\validate\applet;

use think\Validate;

class SchoolClassValidate extends Validate
{
    protected $rule = [
        'type' => 'require',
        'page' => 'require',
        'class_name' => 'require',
        'size' => 'require|max:40',
        'class_id' => 'require|integer',
        'school_code' => 'require',

    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'school_code.require' => '学校必填',
        'type.require' => '类型必填',
        'page.require' => '页面必传',
        'size.require' => '数量必传',
        'class_name.require' => '班级名称必传',
        'class_id.require' => '班级必传'
    ];

    protected $scene = [
        'index' => ['page', 'size', 'school_code'],
        'save' => ['class_name', 'weight'],
        'update' => ['class_id', 'class_name', 'weight'],
        'delete' => ['class_id'],
    ];
}
