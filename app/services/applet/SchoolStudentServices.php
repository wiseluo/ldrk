<?php

namespace app\services\applet;

use app\dao\SchoolClassTeacherDao;
use app\services\applet\BaseServices;
use app\dao\SchoolStudentDao;
use app\dao\SchoolStudentFamilyDao;
use app\model\School;
use app\model\SchoolClass;
use app\model\SchoolStudentFamily;
use think\facade\Db;

class SchoolStudentServices extends BaseServices
{
    public function __construct(SchoolStudentDao $dao)
    {
        $this->dao = $dao;
    }

    public function findChild($param)
    {
        $school = app()->make(School::class)->where(['code' => $param['school_code']])->find();
        if (empty($school)) {
            return ['status' => 0, 'msg' => '学校不存在'];
        }

        $class = app()->make(SchoolClass::class)->where(['school_code' => $param['school_code'], 'id' => $param['class_id']])->find();
        if (empty($class)) {
            return ['status' => 0, 'msg' => '班级不存在'];
        }

        $student =  $this->dao->get(['school_code' => $param['school_code'], 'class_id' => $param['class_id'], 'student_number' => $param['student_number'], 'student_name' => $param['student_name']], ['id as student_id', 'class_name', 'student_name', 'student_number', 'student_idcard', 'student_phone']);
        return ['status' => 1, 'data' => $student];
    }

    public function addStudent($param)
    {
        $school = app()->make(School::class)->where(['code' => $param['school_code']])->find();
        if (empty($school)) {
            return ['status' => 0, 'msg' => '学校不存在'];
        }

        $class = app()->make(SchoolClass::class)->where(['school_code' => $param['school_code'], 'id' => $param['class_id']])->find();
        if (empty($class)) {
            return ['status' => 0, 'msg' => '班级不存在'];
        }

        $student = $this->dao->get(['school_code' => $param['school_code'], 'class_id' => $param['class_id'], 'student_name' => $param['student_name']]);
        if (!empty($student)) {
            return ['status' => 0, 'msg' => '学生已存在，请勿重复添加'];
        }

        $data = [
            'school_code' => $param['school_code'],
            'school_name' => $school['name'],
            'student_number' => $param['student_number'],
            'class_id' => $class['id'],
            'class_name' => $class['class_name'],
            'student_name' => $param['student_name'],
            'student_idcard' => isset($param['student_idcard']) ? $param['student_idcard'] : '',
            'student_phone' => isset($param['student_phone']) ? $param['student_phone'] : '',
        ];
        try {
            $this->dao->save($data);
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-' . $e->getMessage()];
        }
    }

    public function update($param, $userInfo)
    {
        // 查看当前用户绑定的学生
        $family = app()->make(SchoolStudentFamily::class)->where(['id_card' => $userInfo['id_card'], 'student_id' => $param['student_id']])->find();
        if (empty($family)) {
            return ['status' => 0, 'msg' => '权限不足'];
        }
        $data = [
            'student_name' => $param['student_name'],
            'student_idcard' => isset($param['student_idcard']) ? $param['student_idcard'] : '',
            'student_phone' => isset($param['student_phone']) ? $param['student_phone'] : '',
            'student_number' => $param['student_number'],
        ];
        Db::startTrans();
        try {
            app()->make(SchoolStudentFamily::class)->where('student_id', $family['student_id'])
                ->update(['student_name' => $param['student_name'], 'student_number' => $param['student_number']]);


            $this->dao->update($family['student_id'], $data);
            Db::commit();
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            Db::rollBack();
            return ['status' => 0, 'msg' => '操作失败-' . $e->getMessage()];
        }
    }


    public function studentList($param, $userInfo)
    {
        $teacher = app()->make(SchoolClassTeacherDao::class)->get(['teacher_idcard' => $userInfo['id_card'], 'class_id' => $param['class_id'], 'audit_status' => 'audit_success']);
        if (empty($teacher)) {
            return ['status' => 0, 'msg' => '权限不足'];
        }

        $students = $this->dao->getList($param, 'id as student_id,class_name,student_number, student_name,student_idcard,student_phone');
        return ['status' => 1, 'msg' => '操作成功', 'data' => $students];
    }


    public function delete($param, $userInfo)
    {
        $student = $this->dao->get($param['student_id']);
        if (empty($student)) {
            return ['status' => 0, 'msg' => '学生不存在'];
        }

        // 判断是否是老师
        $teacher = app()->make(SchoolClassTeacherDao::class)->get(['class_id' => $student['class_id'], 'audit_status' => 'audit_success']);
        // 判断是否是家属
        $family = app()->make(SchoolStudentFamilyDao::class)->get(['student_id' => $param['student_id'], 'id_card' => $userInfo['id_card']]);
        if ($teacher['teacher_idcard'] != $userInfo['id_card'] and empty($family)) {
            return ['status' => 0, 'msg' => '权限不足'];
        }

        try {
            $this->dao->softDelete($param['student_id']);
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-' . $e->getMessage()];
        }
    }
}
