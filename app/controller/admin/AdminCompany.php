<?php
namespace app\controller\admin;

use think\facade\App;
use app\controller\admin\AuthController;
use app\services\admin\AdminCompanyServices;
use app\validate\admin\AdminCompanyValidate;

class AdminCompany extends AuthController
{
    public function __construct(App $app, AdminCompanyServices $service)
    {
        parent::__construct($app);
        $this->service = $service;
    }

    public function index()
    {
        $param = $this->request->param();
        $param['size'] = $this->request->param('size', 10);
        $res = $this->service->getListService($param, $this->adminInfo);
        return show(200, '成功', $res);
    }

    public function follow()
    {
        $param = $this->request->param();
        $validate = new AdminCompanyValidate();
        if(!$validate->scene('follow')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->service->followService($param, $this->adminInfo);
        if($res['status']) {
            return show(200, $res['msg']);
        }else{
            return show(400, $res['msg']);
        }
    }
    
    public function unfollow()
    {
        $param = $this->request->param();
        $validate = new AdminCompanyValidate();
        if(!$validate->scene('unfollow')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->service->unfollowService($param, $this->adminInfo);
        if($res['status']) {
            return show(200, $res['msg']);
        }else{
            return show(400, $res['msg']);
        }
    }

}
