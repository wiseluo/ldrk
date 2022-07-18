<?php

namespace app\services\applet;

use app\dao\SchoolClassTeacherDao;
use app\services\applet\BaseServices;
use app\dao\SchoolDao;
use app\dao\SchoolStudentDao;
use app\dao\SchoolStudentFamilyDao;
use app\model\SchoolClassTeacher;
use \behavior\WechatAppletTool;


class SchoolServices extends BaseServices
{
    public function __construct(SchoolDao $dao)
    {
        $this->dao = $dao;
    }



    public function getUserSchoolInfo($user_id)
    {
        return app()->make(SchoolDao::class)->getUserSchoolInfo($user_id);
    }



    public function saveService($param, $userInfo)
    {
        $school = $this->dao->get(['link_id' => $userInfo['id']]);
        if ($school) {
            return ['status' => 0, 'msg' => '您已申请过学校信息码，不能重复申请'];
        }
        $name = trim($param['name']);

        $row = $this->dao->get(['name' => $name]);
        if ($row) {
            return ['status' => 0, 'msg' => '该学校已申请了信息码，不能重复申请'];
        }

        $code = randomCode(12);
        $wechatAppletTool = new WechatAppletTool();
        $applet_qrcode = $wechatAppletTool->appletSchoolQrcode($code, $name);
        if ($applet_qrcode['status'] == 0) {
            return ['status' => 0, 'msg' => '操作失败-' . $applet_qrcode['msg']];
        }
        $data = [
            'code' => $code,
            'school_qrcode' => $applet_qrcode['data'],
            'name' => $name,
            'credit_code' => $param['credit_code'],
            'yw_street_id' => $param['yw_street_id'],
            'yw_street' => $param['yw_street'],
            'community_id' => $param['community_id'],
            'community' => $param['community'],
            'addr' => $param['addr'],
            'link_id' => $userInfo['id'],
            'link_name' => $userInfo['real_name'],
            'link_phone' => $userInfo['phone'],
            'link_idcard' => $userInfo['id_card'],
            'user_count' => 1,
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
        $school = $this->dao->get(['link_id' => $userInfo['id']]);
        if ($school == null) {
            return ['status' => 0, 'msg' => '您未申请过校园码'];
        }

        if ($school['link_update_time'] != null && (time() - strtotime($school['link_update_time']) < 2592000)) {
            return ['status' => 0, 'msg' => '一个月内只能修改一次'];
        }

        $data = [
            'yw_street_id' => $param['yw_street_id'],
            'yw_street' => $param['yw_street'],
            'community_id' => $param['community_id'],
            'community' => $param['community'],
            'addr' => $param['addr'],
            'link_update_time' => date('Y-m-d H:i:s'),
        ];
        try {
            $this->dao->update($school['id'], $data);
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-' . $e->getMessage()];
        }
    }

    public function whiteList($param)
    {
        $credit_code = $param['credit_code'];
        switch ($credit_code) {
            case '1':
                return ['status' => 1, 'msg' => '成功', 'data' => ['name' => '学校名称1', 'credit_code' => 'XXXXXXXXXX1']];
                break;
            case '2':
                return ['status' => 1, 'msg' => '成功', 'data' => ['name' => '学校名称2', 'credit_code' => 'XXXXXXXXXX2']];
                break;
            case '3':
                return ['status' => 1, 'msg' => '成功', 'data' => ['name' => '学校名称3', 'credit_code' => 'XXXXXXXXXX3']];
                break;
            case '4':
                return ['status' => 1, 'msg' => '成功', 'data' => ['name' => '学校名称4', 'credit_code' => 'XXXXXXXXXX4']];
                break;
        }
    }

    public function identityInspector($userInfo)
    {
        $isTeacher = false;
        $isFamily = false;
        // 老师
        $teacher = app()->make(SchoolClassTeacherDao::class)->get(['teacher_idcard' => $userInfo['id_card'], 'audit_status' => 'audit_success']);
        if (!empty($teacher)) {
            $isTeacher = true;
        }

        // 家属
        $family = app()->make(SchoolStudentFamilyDao::class)->get(['id_card' => $userInfo['id_card']]);
        if (!empty($family)) {
            $isFamily = true;
        }
        return ['status' => 1, 'msg' => '获取成功', 'data' => ['is_teacher' => $isTeacher, 'is_family' => $isFamily]];
    }
}
