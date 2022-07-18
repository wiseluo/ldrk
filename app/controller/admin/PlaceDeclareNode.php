<?php
namespace app\controller\admin;

use think\facade\App;
use app\controller\admin\AuthController;
use app\services\admin\PlaceDeclareNodeServices;

class PlaceDeclareNode extends AuthController
{
    public function __construct(App $app, PlaceDeclareNodeServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    public function index()
    {
        $res = $this->services->indexService();
        return show(200, '成功', $res);
    }

}
