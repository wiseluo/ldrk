<?php
namespace app\controller\admin;

use think\facade\App;
use app\controller\admin\AuthController;
use app\services\admin\OwnDeclareServices;

class DataWarning extends AuthController
{

    public function __construct(App $app, OwnDeclareServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    // 未按时返义乌
    public function backouttime()
    {
        $param = $this->request->param();
        $param['pid'] = $this->request->param('pid', 0);
        $warning_type = 'backouttime';
        return show(200, '成功', $this->services->getDataWarning($param,$warning_type));
    }

    public function riskarea()
    {
        $param = $this->request->param();
        $param['pid'] = $this->request->param('pid', 0);
        $warning_type = 'riskarea';
        return show(200, '成功', $this->services->getDataWarning($param,$warning_type));
    }

}
