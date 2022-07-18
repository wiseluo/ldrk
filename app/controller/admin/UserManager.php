<?php
namespace app\controller\admin;

use think\facade\App;
use app\controller\admin\AuthController;
use app\services\admin\UserManagerServices;
use app\validate\admin\UserManagerValidate;

class UserManager extends AuthController
{
    public function __construct(App $app, UserManagerServices $service)
    {
        parent::__construct($app);
        $this->service = $service;
    }

    public function index()
    {
        $param = $this->request->param();
        $param['size'] = $this->request->param('size', 10);
        $res = $this->service->getListService($param);
        return show(200, '成功', $res);
    }

    public function read($id)
    {
        $res = $this->service->readService($id);
        if($res['status']) {
            return show(200, $res['msg'], $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }

    public function save()
    {
        $param = $this->request->param();
        $validate = new UserManagerValidate();
        if(!$validate->scene('save')->check($param)) {
            return show(400, $validate->getError());
        }
        $param['yw_street_id'] = $this->request->param('yw_street_id');
        $param['yw_street'] = $this->request->param('yw_street');
        $res = $this->service->saveService($param);
        return show(200, '成功', $res);
    }
    
    public function update($id)
    {
        $param = $this->request->param();
        // $validate = new UserAdminValidate();
        // if(!$validate->scene('update')->check($param)) {
        //     return show(400, $validate->getError());
        // }
        $param['yw_street_id'] = $this->request->param('yw_street_id');
        $param['yw_street'] = $this->request->param('yw_street');
        $res = $this->service->updateService($param, $id);
        if($res['status']) {
            return show(200, $res['msg']);
        }else{
            return show(400, $res['msg']);
        }
    }

    public function delete($id)
    {
        $res = $this->service->deleteService($id);
        if($res['status']) {
            return show(200, $res['msg']);
        }else{
            return show(400, $res['msg']);
        }
    }

}
