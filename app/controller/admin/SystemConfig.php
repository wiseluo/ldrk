<?php

namespace app\controller\admin;

use think\facade\App;
use app\controller\admin\AuthController;
use app\services\admin\SystemConfigServices;

class SystemConfig extends AuthController
{
    public function __construct(App $app, SystemConfigServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    public function index()
    {
        $param = $this->request->param();
        $param['size'] = $this->request->param('size', 10);
        return show(200, '成功', $this->services->getListService($param));
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

    public function update($id)
    {
        $param = $this->request->param();
        $param['menu_name'] = $this->request->param('menu_name', '');
        $param['value'] = $this->request->param('value', '');
        $param['desc'] = $this->request->param('desc', '');
        
        $res = $this->services->updateService($param, $id);
        if($res['status']) {
            return show(200, $res['msg']);
        }else{
            return show(400, $res['msg']);
        }
    }


}
