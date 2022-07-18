<?php

namespace app\services\applet;

use app\dao\SchoolClassDao;
use app\dao\SchoolClassTeacherDao;
use app\dao\SchoolDao;
use app\model\SchoolClassTeacher;
use app\services\applet\BaseServices;


class SchoolClassServices extends BaseServices
{
    public function __construct(SchoolClassDao $dao)
    {
        $this->dao = $dao;
    }

    public function getList($param)
    {
        $school = app()->make(SchoolDao::class)->get(['code' => $param['school_code']]);
        if ($school == null) {
            return ['status' => 0, 'msg' => '学校不存在'];
        }
        $list = $this->dao->getList($param);
        return ['status' => 1, 'msg' => '成功', 'data' => $list];
    }

    public function save($param, $userInfo)
    {
        $school = app()->make(SchoolDao::class)->get(['link_id' => $userInfo['id']]);
        if ($school == null) {
            return ['status' => 0, 'msg' => '学校不存在'];
        }
        $class_name = trim($param['class_name']);
        $row = app()->make(SchoolClassDao::class)->get(['school_code' => $school['code'], 'class_name' => $class_name]);
        if ($row != null) {
            return ['status' => 0, 'msg' => '班级名称不得重复'];
        }

        $data = [
            'school_code' => $school['code'],
            'school_name' => $school['name'],
            'class_name' => $class_name,
            'weight' => $param['weight']
        ];
        try {
            app()->make(SchoolClassDao::class)->save($data);
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-' . $e->getMessage()];
        }
    }


    public function update($param, $user_id)
    {
        $school = app()->make(SchoolDao::class)->get(['link_id' => $user_id]);
        if ($school == null) {
            return ['status' => 0, 'msg' => '学校不存在'];
        }

        $class = $this->dao->get(['id' => $param['class_id'], 'school_code' => $school['code']]);
        if ($class == null) {
            return ['status' => 0, 'msg' => '班级不存在'];
        }

        $data = [
            'class_name' =>  trim($param['class_name']),
            'weight' => $param['weight']
        ];
        try {
            $this->dao->update($param['class_id'], $data);
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-' . $e->getMessage()];
        }
    }


    public function delete($param, $user_id)
    {
        $school = app()->make(SchoolDao::class)->get(['link_id' => $user_id]);
        if ($school == null) {
            return ['status' => 0, 'msg' => '学校不存在'];
        }

        $class = $this->dao->get(['id' => $param['class_id'], 'school_code' => $school['code']]);
        if ($class == null) {
            return ['status' => 0, 'msg' => '班级不存在'];
        }

        try {
            $this->dao->softDelete($param['class_id']);
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-' . $e->getMessage()];
        }
    }


    public function teachingToClass($id_card)
    {
        $teacher = app()->make(SchoolClassTeacher::class)->where(['teacher_idcard' => $id_card, 'audit_status' => 'audit_success'])->field('school_code,class_id,class_name')->select()->toArray();
        return ['status' => 1, 'msg' => '获取成功', 'data' => $teacher];
    }
}
