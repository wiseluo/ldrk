<?php
namespace app\controller\admin;

use think\facade\App;
use app\controller\admin\AuthController;
use app\services\admin\CompanyStaffClassifyServices;
use app\validate\admin\CompanyStaffClassifyValidate;

class CompanyStaffClassify extends AuthController
{

    public function __construct(App $app, CompanyStaffClassifyServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    public function index()
    {
        $param = $this->request->param();
        $param['size'] = $this->request->param('size', 10);
        return show(200, '成功', $this->services->getList($param));
    }

    public function read($id)
    {
        $res = $this->services->readService($id);
        if($res['status']) {
            return show(200, $res['msg'], $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }

    public function save()
    {
        $param = $this->request->param();
        $validate = new CompanyStaffClassifyValidate();
        if(!$validate->scene('save')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->services->saveService($param);
        if($res['status']) {
            return show(200, $res['msg']);
        }else{
            return show(400, $res['msg']);
        }

    }

    public function update($id)
    {
        $param = $this->request->param();
        $validate = new CompanyStaffClassifyValidate();
        if(!$validate->scene('update')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->services->updateService($param, $id);
        if($res['status']) {
            return show(200, $res['msg']);
        }else{
            return show(400, $res['msg']);
        }
    }

}
