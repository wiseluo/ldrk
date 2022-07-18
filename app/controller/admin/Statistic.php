<?php
namespace app\controller\admin;

use think\facade\App;
use app\controller\admin\AuthController;
use app\services\admin\OwnDeclareServices;

class Statistic extends AuthController
{

    public function __construct(App $app, OwnDeclareServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    public function fromwhere()
    {
        $param = $this->request->param();
        $param['pid'] = $this->request->param('pid', 0);
        $statistic_type = 'fromwhere';
        return show(200, '成功', $this->services->getStatistic($param,$statistic_type));
    }

    public function ywstreet()
    {
        $param = $this->request->param();
        $param['pid'] = $this->request->param('pid', 0);
        $statistic_type = 'ywstreet';
        return show(200, '成功', $this->services->getStatistic($param,$statistic_type));
    }

    public function age()
    {
        $param = $this->request->param();
        $param['pid'] = $this->request->param('pid', 0);
        $statistic_type = 'age';
        return show(200, '成功', $this->services->getStatistic($param,$statistic_type));
    }

    public function jiance()
    {
        $param = $this->request->param();
        $param['pid'] = $this->request->param('pid', 0);
        $statistic_type = 'jiance';
        return show(200, '成功', $this->services->getStatistic($param,$statistic_type));
    }
    public function huji()
    {
        $param = $this->request->param();
        $param['pid'] = $this->request->param('pid', 0);
        $statistic_type = 'huji';
        return show(200, '成功', $this->services->getStatistic($param,$statistic_type));
    }
}
