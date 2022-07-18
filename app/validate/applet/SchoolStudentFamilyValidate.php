<?php

namespace app\validate\applet;

use think\Validate;
use \behavior\IdentityCardTool;

class SchoolStudentFamilyValidate extends Validate
{
    protected $rule = [
        'school_code' => 'require',
        'class_id' => 'require|integer',
        'page' => 'require|integer',
        'size' => 'require|integer|max:40',
        'student_number' => 'require|integer',
        'student_name' => 'require',
        'real_name' => 'require|checkRealName',
        'id_card' => 'require|checkIdCard',
        'phone' => 'require|checkPhone',
        'type' => 'require',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'school_code.require' => '学校code必填',
        'class_id.require' => '班级必填',
        'student_number.require' => '学号必填',
        'student_name.require' => '学生名称必填',
        'page.require' => '页面必填',
        'real_name.require' => '家属名称必填',
        'id_card.require' => '家属身份证必填',
        'phone.require' => '家属手机号必填',
        'type.require' => '类型必填',
    ];

    protected $scene = [
        'save' => ['school_code', 'class_id', 'student_number', 'student_name', 'relationship_name', 'real_name', 'id_card', 'phone'],
        'index' => ['page', 'size', 'school_code', 'class_id', 'student_number', 'student_name'],
        'checkFamily' => ['page', 'size', 'type', 'class_id'],
        'update' => ['family_id', 'relationship_name', 'real_name', 'id_card', 'phone'],
        'delete' => ['family_id'],
    ];


    public function checkRealName($value, $rule, $data)
    {
        $match = "/^[\x{4e00}-\x{9fa5}A-Za-z0-9_·]+$/u"; //匹配中文数字字母
        $result = preg_match($match, $value);
        if ($result == 0) {
            return '姓名不能包含特殊字符';
        } else {

            $match = '/\d+|\w+/';  //中国籍包含字母、数字
            $result = preg_match($match, $value);
            if ($result) {
                return '姓名不能包含字母、数字';
            }
            return true;
        }
    }

    public function checkPhone($value, $rule, $data)
    {
        $match = '/^1[0-9]{10}$/';
        $result = preg_match($match, $value);
        if ($result) {
            return true;
        } else {
            return '请填写正确的手机号码';
        }
    }

    public function checkIdCard($value, $rule, $data)
    {
        if (IdentityCardTool::isValid($value)) {
            return true;
        } else {
            return '身份证号码格式不准确';
        }
    }
}
