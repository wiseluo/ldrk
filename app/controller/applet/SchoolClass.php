<?php

namespace app\controller\applet;

use think\facade\App;
use crmeb\basic\BaseController;
use app\services\applet\SchoolClassServices;
use app\validate\applet\SchoolClassValidate;

class SchoolClass extends BaseController
{

    public function __construct(App $app, SchoolClassServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    // 获取班级列表 通用
    public function list()
    {
        $param = $this->request->param();
        $param['size'] = $this->request->param('size', 10);
        $validate = new SchoolClassValidate();
        if (!$validate->scene('index')->check($param)) {
            return show(400, $validate->getError());
        }

        $res = $this->services->getList($param);
        if ($res['status']) {
            return show(200, '成功', $res['data']);
        } else {
            return show(400, $res['msg']);
        }
    }



    // 校园联系人添加班级
    public function create()
    {
        $param = $this->request->param();
        $validate = new SchoolClassValidate();
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

    // 校园联系人修改班级
    public function update()
    {
        $param = $this->request->param();
        $validate = new SchoolClassValidate();
        if (!$validate->scene('update')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->services->update($param, $this->request->tokenUser()['id']);
        if ($res['status'] == 1) {
            return show(200, $res['msg']);
        } else {
            return show(400, $res['msg']);
        }
    }

    // 校园联系人删除班级
    public function delete()
    {
        $param = $this->request->param();
        $validate = new SchoolClassValidate();
        if (!$validate->scene('delete')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->services->delete($param, $this->request->tokenUser()['id']);
        if ($res['status']) {
            return show(200, $res['msg']);
        } else {
            return show(400, $res['msg']);
        }
    }

    // 老师绑定的班级列表
    public function teachingToClass()
    {
        $res = $this->services->teachingToClass($this->request->tokenUser()['id_card']);
        if ($res['status']) {
            return show(200, $res['msg'], $res['data']);
        } else {
            return show(400, $res['msg']);
        }
    }
}
