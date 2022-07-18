<?php

namespace app\controller\applet;

use app\dao\SchoolDao;
use think\facade\App;
use crmeb\basic\BaseController;
use app\services\applet\SchoolServices;
use app\validate\applet\SchoolValidate;

class School extends BaseController
{

    public function __construct(App $app, SchoolServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    // 查询微信用户是否是校园联系人
    public function getSchoolInfo()
    {
        return show(200, '成功', $this->services->getUserSchoolInfo($this->request->tokenUser()['id']));
    }

    public function create()
    {
        $param = $this->request->param();
        $validate = new SchoolValidate();
        if (!$validate->scene('save')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->services->saveService($param, $this->request->tokenUser());
        if ($res['status'] == 1) {
            return show(200, $res['msg']);
        } else {
            return show(400, $res['msg']);
        }
    }

    // 查询修改学校信息
    public function update()
    {
        $param = $this->request->param();
        $validate = new SchoolValidate();
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

    // 学校白名单
    public function whiteList()
    {
        $param = $this->request->param();
        $validate = new SchoolValidate();
        if (!$validate->scene('whiteList')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->services->whiteList($param);
        if ($res['status'] == 1) {
            return show(200, $res['msg'], $res['data']);
        } else {
            return show(400, $res['msg']);
        }
    }
}
