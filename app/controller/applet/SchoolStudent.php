<?php

namespace app\controller\applet;

use think\facade\App;
use crmeb\basic\BaseController;
use app\services\applet\SchoolStudentServices;
use app\validate\applet\SchoolStudentValidate;

class SchoolStudent extends BaseController
{

    public function __construct(App $app, SchoolStudentServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    // 家长在班级里搜索孩子
    public function findChild()
    {
        $param = $this->request->param();
        $validate = new SchoolStudentValidate();
        if (!$validate->scene('findChild')->check($param)) {
            return show(400, $validate->getError());
        }

        $res = $this->services->findChild($param);
        if ($res['status'] == 1) {
            return show(200, '成功', $res['data']);
        } else {
            return show(400, $res['msg']);
        }
    }

    // 家长添加学生
    public function addStudent()
    {
        $param = $this->request->param();
        $validate = new SchoolStudentValidate();
        // var_dump($param);
        // die;
        if (!$validate->scene('addStudent')->check($param)) {
            return show(400, $validate->getError());
        }

        $res = $this->services->addStudent($param);
        if ($res['status'] == 1) {
            return show(200, $res['msg']);
        } else {
            return show(400, $res['msg']);
        }
    }



    // 家属修改学生信息
    public function update()
    {
        $param = $this->request->param();
        $validate = new SchoolStudentValidate();
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

    // 老师查看学生列表
    public function studentList()
    {
        $param = $this->request->param();
        $validate = new SchoolStudentValidate();
        if (!$validate->scene('studentList')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->services->studentList($param, $this->request->tokenUser());
        if ($res['status'] == 1) {
            return show(200, $res['msg'], $res['data']);
        } else {
            return show(400, $res['msg']);
        }
    }

    // 删除学生
    public function delete()
    {
        $param = $this->request->param();
        $validate = new SchoolStudentValidate();
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
}
