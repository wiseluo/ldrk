<?php

namespace app\services\applet;

use app\services\applet\BaseServices;
use app\dao\SchoolClassTeacherDao;
use app\dao\SchoolDao;
use app\dao\SchoolClassDao;
use app\model\School;
use app\model\SchoolClassTeacher;
use app\model\SchoolTeacher;
use think\facade\Db;
use behavior\SmsTool;

class SchoolClassTeacherServices extends BaseServices
{
    public function __construct(SchoolClassTeacherDao $dao)
    {
        $this->dao = $dao;
    }

    // 获取审核老师列表
    public function getList($param, $user_id)
    {
        $school = app()->make(SchoolDao::class)->get(['link_id' => $user_id]);

        test_log(['$user_id' => $user_id]);

        if ($school == null) {
            return ['status' => 0, 'msg' => '学校不存在'];
        }

        if (!in_array($param['audit_status'], ['await_review', 'audit_fail', 'audit_success'])) {
            return ['status' => 0, 'msg' => '审核状态错误'];
        }

        $param['school_code'] = $school['code'];
        $list = $this->dao->getList($param);
        return ['status' => 1, 'msg' => '操作成功', 'data' => $list];
    }

    public function save($param, $userInfo)
    {
        $school = app()->make(SchoolDao::class)->get(['code' => $param['school_code']]);
        if ($school == null) {
            return ['status' => 0, 'msg' => '学校不存在'];
        }

        $class =  app()->make(SchoolClassDao::class)->get(['school_code' => $param['school_code'], 'id' => $param['class_id']]);

        if ($class == null) {
            return ['status' => 0, 'msg' => '班级不存在'];
        }

        $teacher = $this->dao->get(['school_code' => $param['school_code'], 'class_id' => $param['class_id'], 'teacher_idcard' => $userInfo['id_card']]);
        if (!empty($teacher)) {
            return ['status' => 0, 'msg' => '请勿重复提交，联系管理员审核'];
        }

        $data = [
            'school_name' => $school['name'],
            'school_code' => $param['school_code'],
            'class_id' => $param['class_id'],
            'class_name' => $class['class_name'],
            'teacher_name' => $userInfo['real_name'],
            'teacher_idcard' => $userInfo['id_card'],
            'teacher_phone' => $userInfo['phone'],
            'audit_status' => 'await_review',
        ];
        try {
            $this->dao->save($data);
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-' . $e->getMessage()];
        }
    }

    public function checkAudit($param, $userInfo)
    {
        if (!in_array($param['status'], ['pass', 'pass_fail'])) {
            return ['status' => 0, 'msg' => '状态错误'];
        }

        $row = $this->dao->get(['id' => $param['id'],]);
        if (empty($row)) {
            return ['status' => 0, 'msg' => '数据不存在'];
        }

        $school = app()->make(School::class)->where(['code' => $row['school_code']])->find();
        if ($school['link_id'] !== $userInfo['id']) {
            return ['status' => 0, 'msg' => '权限不足'];
        }

        if (($row['audit_status'] == 'audit_fail' && $param['status'] == 'pass_fail') || ($row['audit_status'] == 'audit_success' && $param['status'] == 'pass')) {
            return ['status' => 0, 'msg' => '请勿重复操作'];
        }

        $smsTool = new SmsTool();

        // 通过
        if ($param['status'] == 'pass') {
            $data = [
                'audit_status' => 'audit_success',
            ];
            $teacher = app()->make(SchoolTeacher::class)->where(['teacher_idcard' => $row['teacher_idcard']])->find();



            Db::startTrans();
            try {
                if (empty($teacher)) {
                    $teacherData = [
                        'school_name' => $school['name'],
                        'school_code' => $school['code'],
                        'teacher_name' => $row['teacher_name'],
                        'teacher_idcard' => $row['teacher_idcard'],
                        'teacher_phone' => $row['teacher_phone'],
                    ];
                    app()->make(SchoolTeacher::class)->save($teacherData);
                }
                $this->dao->update($param['id'], $data);

                Db::commit();
                //发送短信
                $smsTool->sendSms($row['teacher_phone'], '【校园码】您申请绑定班级的请求已通过！');
                return ['status' => 1, 'msg' => '操作成功'];
            } catch (\Exception $e) {
                Db::rollback();
                return ['status' => 0, 'msg' => '操作失败-' . $e->getMessage()];
            }
        } else {
            $data = [
                'audit_status' => 'audit_fail',
            ];
            try {
                $this->dao->update($param['id'], $data);
                $smsTool->sendSms($row['teacher_phone'], '【校园码】您申请绑定班级的请求未通过！');
                return ['status' => 1, 'msg' => '操作成功'];
            } catch (\Exception $e) {
                return ['status' => 0, 'msg' => '操作失败-' . $e->getMessage()];
            }
        }
    }

    public function delete(int $id, int $user_id)
    {
        // 判断是否是学校联系人
        $school = app()->make(School::class)->where(['link_id' => $user_id])->find();
        if (empty($school)) {
            return ['status' => 0, 'msg' => '权限不足'];
        }
        // 关系数据
        $row = $this->dao->get($id);
        if ($row == null) {
            return ['status' => 0, 'msg' => '数据不存在'];
        }
        // 判断是否是这个学校的联系人
        if ($row['school_code'] != $school['code']) {
            return ['status' => 0, 'msg' => '权限不足'];
        }
        try {
            $this->dao->softDelete($id);
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-' . $e->getMessage()];
        }
    }

    public function teacherList($param, int $user_id)
    {
        $school = app()->make(School::class)->where(['link_id' => $user_id])->find();
        if (empty($school)) {
            return ['status' => 0, 'msg' => '数据不存在'];
        }
        $row = app()->make(SchoolClassTeacher::class)->where(['school_code' => $school['code'], 'class_id' => $param['class_id'], 'audit_status' => 'audit_success'])->select();
        return ['status' => 1, 'msg' => '获取成功', 'data' => $row];
    }
}
