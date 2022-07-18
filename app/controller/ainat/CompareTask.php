<?php
namespace app\controller\ainat;

use think\facade\App;
use app\controller\ainat\AuthController;
use app\services\ainat\CompareTaskServices;
use app\validate\ainat\CompareTaskValidate;

class CompareTask extends AuthController
{
    protected $service;

    public function __construct(App $app, CompareTaskServices $services)
    {
        parent::__construct($app);
        $this->service = $services;
    }

    public function index()
    {
        $param = $this->request->param();
        $param['size'] = $this->request->param('size', 10);
        return show(200, '成功', $this->service->indexService($param, $this->adminInfo));
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
        $validate = new CompareTaskValidate();
        if(!$validate->scene('save')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->service->saveService($param, $this->adminInfo);
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
