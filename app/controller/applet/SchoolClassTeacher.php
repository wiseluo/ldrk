<?php

namespace app\controller\applet;

use think\facade\App;
use crmeb\basic\BaseController;
use app\services\applet\SchoolClassTeacherServices;
use app\validate\applet\SchoolClassTeacherValidate;

class SchoolClassTeacher extends BaseController
{

    public function __construct(App $app, SchoolClassTeacherServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    // 校园联系人获取审核老师列表
    public function list()
    {
        $param = $this->request->param();
        $param['size'] = $this->request->param('size', 10);
        $validate = new SchoolClassTeacherValidate();
        if (!$validate->scene('index')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->services->getList($param, $this->request->tokenUser()['id']);
        if ($res['status'] == 1) {
            return show(200, $res['msg'], $res['data']);
        } else {
            return show(400, $res['msg']);
        }
    }

    // 老师申请绑定班级
    public function create()
    {
        $param = $this->request->param();
        $validate = new SchoolClassTeacherValidate();
        if (!$validate->scene('save')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->services->save($param, $this->request->tokenUser());
        if ($res['status'] == 1) {
            return show(200, $res['msg']);
        } else {
            return show(400, $res['msg']);
        }
    }

    // 处理审核
    public function checkAudit()
    {
        $param = $this->request->param();
        $validate = new SchoolClassTeacherValidate();
        if (!$validate->scene('checkAudit')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->services->checkAudit($param, $this->request->tokenUser());
        if ($res['status'] == 1) {
            return show(200, $res['msg']);
        } else {
            return show(400, $res['msg']);
        }
    }


    // 删除审核老师
    public function delete()
    {
        $param = $this->request->param();
        $validate = new SchoolClassTeacherValidate();
        if (!$validate->scene('delete')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->services->delete($param['id'], $this->request->tokenUser()['id']);
        if ($res['status']) {
            return show(200, $res['msg']);
        } else {
            return show(400, $res['msg']);
        }
    }

    // 联系人查看班级老师列表
    public function teacherList()
    {
        $param = $this->request->param();
        $validate = new SchoolClassTeacherValidate();
        if (!$validate->scene('teacherList')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->services->teacherList($param, $this->request->tokenUser()['id']);
        if ($res['status']) {
            return show(200, $res['msg'], $res['data']);
        } else {
            return show(400, $res['msg']);
        }
    }
}
