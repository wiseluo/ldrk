<?php
namespace app\controller\admin;

use think\facade\App;
use app\controller\admin\AuthController;
use app\services\admin\GateDeclareServices;
// use app\validate\admin\GateValidate;

class GateDeclare extends AuthController
{

    public function __construct(App $app, GateDeclareServices $services)
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


}
