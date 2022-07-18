<?php

namespace app\controller\applet;

use think\facade\App;
use crmeb\basic\BaseController;
use app\services\applet\SchoolStudentFamilyServices;
use app\validate\applet\SchoolStudentFamilyValidate;

class SchoolStudentFamily extends BaseController
{

    public function __construct(App $app, SchoolStudentFamilyServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    // 学生家属列表
    public function list()
    {
        $param = $this->request->param();
        $validate = new SchoolStudentFamilyValidate();
        if (!$validate->scene('index')->check($param)) {
            return show(400, $validate->getError());
        }

        $res = $this->services->index($param);
        if ($res['status'] == 1) {
            return show(200, $res['msg'], $res['data']);
        } else {
            return show(400, $res['msg']);
        }
    }

    // 添加学生家属
    public function create()
    {
        $param = $this->request->param();
        $validate = new SchoolStudentFamilyValidate();
        if (!$validate->scene('save')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->services->save($param);
        if ($res['status'] == 1) {
            return show(200, $res['msg']);
        } else {
            return show(400, $res['msg']);
        }
    }

    // 老师查看学生家属列表
    public function checkFamily()
    {
        $param = $this->request->param();
        $validate = new SchoolStudentFamilyValidate();
        if (!$validate->scene('checkFamily')->check($param)) {
            return show(400, $validate->getError());
        }

        $res = $this->services->checkFamily($param, $this->request->tokenUser());

        if ($res['status'] == 1) {
            return show(200, $res['msg'], $res['data']);
        } else {
            return show(400, $res['msg']);
        }
    }

    // 修改学生家属
    public function update()
    {
        $param = $this->request->param();
        $validate = new SchoolStudentFamilyValidate();
        if (!$validate->scene('update')->check($param)) {
            return show(400, $validate->getError());
        }

        $res = $this->services->update($param, $this->request->tokenUser());
        if ($res['status'] == 1) {
            return show(200, $res['msg']);
        } else {
            return show(400, $res['msg']);
        }
    }

    // 删除学生家属
    public function  delete()
    {
        $param = $this->request->param();
        $validate = new SchoolStudentFamilyValidate();
        if (!$validate->scene('delete')->check($param)) {
            return show(400, $validate->getError());
        }

        $res = $this->services->delete($param, $this->request->tokenUser());
        if ($res['status'] == 1) {
            return show(200, $res['msg']);
        } else {
            return show(400, $res['msg']);
        }
    }

    // 孩子列表
    public function childList()
    {

        $res = $this->services->childList($this->request->tokenUser());
        if ($res['status'] == 1) {
            return show(200, $res['msg'], $res['data']);
        } else {
            return show(400, $res['msg']);
        }
    }
}
