<?php

namespace app\validate\applet;

use think\Validate;

class SchoolClassTeacherValidate extends Validate
{
    protected $rule = [
        'school_code' => 'require',
        'class_id' => 'require|integer',
        'page' => 'require|integer',
        'size' => 'number|between:1,20',
        'audit_status' => 'require'
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'class_name.require' => '班级名称必填',
        'page.require' => '页面必填',
        'size.require' => '数量必填',
        'audit_status' => '审核状态必填',
        'class_id.require' => '班级必填',
        'size.between' => '不得超过20',
    ];

    protected $scene = [
        'save' => ['school_code', 'class_id'],
        'index' => ['page', 'size', 'audit_status'],
        'checkAudit' => ['id', 'status'],
        'delete' => ['id'],
        'teacherList' => ['class_id']
    ];
}
