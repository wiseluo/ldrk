<?php

namespace app\validate\applet;

use think\Validate;

class SchoolStudentValidate extends Validate
{
    protected $rule = [
        'school_code' => 'require',
        'class_id' => 'require|integer',
        'page' => 'require|integer',
        'student_number' => 'require|integer',
        'student_name' => 'require',
        'size' => 'require|integer',
        'student_id' => 'require|integer'
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'student_id.require' => '学生必填',
        'school_code.require' => '学校code必填',
        'class_id.require' => '班级必填',
        'student_number.require' => '学号必填',
        'student_name.require' => '学生名称',
        'page.require' => '页面必填',
        'size.require' => '页面数量必填',
    ];

    protected $scene = [
        'delete' => ['student_id'],
        'studentList' => ['page', 'size', 'class_id'],
        'update' => ['student_id', 'student_name'],
        'addStudent' => ['school_code', 'class_id', 'student_number', 'student_name'],
        'findChild' => ['school_code', 'class_id', 'student_number', 'student_name'],
    ];
}
