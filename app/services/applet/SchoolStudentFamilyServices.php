<?php

namespace app\services\applet;

use app\services\applet\BaseServices;
use app\dao\SchoolStudentFamilyDao;
use app\model\SchoolClassTeacher;
use app\model\SchoolStudent;
use app\model\SchoolStudentFamily;

class SchoolStudentFamilyServices extends BaseServices
{
    public function __construct(SchoolStudentFamilyDao $dao)
    {
        $this->dao = $dao;
    }

    public function index($param)
    {
        $row = app()->make(SchoolStudent::class)->where(['school_code' => $param['school_code'], 'class_id' => $param['class_id'], 'student_number' => $param['student_number'], 'student_name' => $param['student_name']])->find();
        if (empty($row)) {
            return ['status' => 0, 'msg' => '数据不存在'];
        }

        $familyList =  $this->dao->getList(['student_id' => $row['id'], 'size' => $param['size']]);
        return ['status' => 1, 'msg' => '成功', 'data' => $familyList];
    }


    public function save($param)
    {
        $row = app()->make(SchoolStudent::class)->where(['school_code' => $param['school_code'], 'class_id' => $param['class_id'], 'student_number' => $param['student_number'], 'student_name' => $param['student_name']])->find();
        if (empty($row)) {
            return ['status' => 0, 'msg' => '数据不存在'];
        }

        $data = [
            'school_code' => $row['school_code'],
            'school_name' => $row['school_name'],
            'class_id' => $row['class_id'],
            'class_name' => $row['class_name'],
            'student_number' => $row['student_number'],
            'student_id' => $row['id'],
            'student_name' => $row['student_name'],
            'relationship_name' => $param['relationship_name'],
            'real_name' => $param['real_name'],
            'id_card' => $param['id_card'],
            'phone' => $param['phone'],
        ];

        try {
            $this->dao->save($data);
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-' . $e->getMessage()];
        }
    }


    public function checkFamily($param, $userInfo)
    {

        // 检查用户是否是该班级的老师
        $row =  app()->make(SchoolClassTeacher::class)->where(['teacher_idcard' => $userInfo['id_card'], 'class_id' => $param['class_id'], 'audit_status' => 'audit_success'])->find();
        if (empty($row)) {
            return ['status' => 0, 'msg' => '权限不足'];
        }
        $param['school_code'] = $row['school_code'];
        return ['status' => 1, 'msg' => '成功', 'data' => $this->dao->checkFamilly($param,)];
    }

    public function update($param, $userInfo)
    {
        $family = $this->dao->get($param['family_id']);

        if (empty($family)) {
            return ['status' => 0, 'msg' => '数据不存在'];
        }

        // 判断用户是否是家属成员
        $user = $this->dao->get(['student_id' => $family['student_id'], 'id_card' => $userInfo['id_card']]);
        if (empty($user)) {
            return ['status' => 0, 'msg' => '权限不足'];
        }

        $data = [
            'relationship_name' => $param['relationship_name'],
            'real_name' => $param['real_name'],
            'id_card' => $param['id_card'],
            'phone' => $param['phone'],
        ];

        try {
            $this->dao->update($param['family_id'], $data);
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-' . $e->getMessage()];
        }
    }

    public function delete($param, $userInfo)
    {
        $family = $this->dao->get($param['family_id']);

        if (empty($family)) {
            return ['status' => 0, 'msg' => '数据不存在'];
        }

        // 判断用户是否是家属成员
        $user = $this->dao->get(['student_id' => $family['student_id'], 'id_card' => $userInfo['id_card']]);
        if (empty($user)) {
            return ['status' => 0, 'msg' => '权限不足'];
        }

        try {
            $this->dao->softDelete($param['family_id']);
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-' . $e->getMessage()];
        }
    }


    public function childList($userInfo)
    {
        // 获取家属关联的学生
        $childList = app()->make(SchoolStudentFamily::class)->field('student_id,school_name,school_code,class_id,student_id,class_name,student_number,relationship_name,student_name')->where(['id_card' => $userInfo['id_card']])->group('student_id')->select()->toArray();
        if (!empty($childList)) {
            $studentList = app()->make(SchoolStudent::class)->where(['id' => array_column($childList, 'student_id')])->select()->toArray();

            foreach ($studentList as $key => $value) {
                $studentKV[$value['id']] = [$value['student_idcard'], $value['student_phone']];
            }

            foreach ($childList as $key => &$value) {
                if (isset($studentKV[$value['student_id']])) {
                    $info = $studentKV[$value['student_id']];
                    $value['student_idcard'] = $info[0];
                    $value['student_phone'] = $info[1];
                } else {
                    $value['student_idcard'] = '';
                    $value['student_phone'] = '';
                }
            }
        }
        return ['status' => 1, 'msg' => '成功', 'data' => $childList];
    }
}
