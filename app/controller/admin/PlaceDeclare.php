<?php
namespace app\controller\admin;

use think\facade\App;
use app\controller\admin\AuthController;
use app\services\admin\PlaceDeclareServices;
use app\validate\admin\PlaceDeclareValidate;

class PlaceDeclare extends AuthController
{
    public function __construct(App $app, PlaceDeclareServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    public function index()
    {
        $param = $this->request->param();
        $param['list_type'] = $this->request->param('list_type', 'index');
        $param['node_id'] = $this->request->param('node_id', 0);
        $param['size'] = $this->request->param('size', 10);
        $res = $this->services->indexService($param, $this->request->adminInfo());
        return show(200, '成功', $res);
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

    public function delete($id)
    {
        $res = $this->services->deleteService($id);
        if($res['status']) {
            return show(200, $res['msg']);
        }else{
            return show(400, $res['msg']);
        }
    }

    public function hsjcResultLog()
    {
        $param = $this->request->param();
        $validate = new PlaceDeclareValidate();
        if(!$validate->scene('hsjcResultLog')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->services->hsjcResultLogService($param['id']);
        if($res['status'] == 1) {
            return show(200, $res['msg'], $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }
}
