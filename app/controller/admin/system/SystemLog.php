<?php
namespace app\controller\admin\system;

use think\facade\App;
use app\controller\admin\AuthController;
use app\services\admin\system\log\SystemLogServices;
use app\services\admin\system\admin\SystemAdminServices;

/**
 * 管理员操作记录表控制器
 * Class SystemLog
 * @package app\controller\admin\v1\system
 */
class SystemLog extends AuthController
{
    /**
     * 构造方法
     * SystemLog constructor.
     * @param App $app
     * @param SystemLogServices $services
     */
    public function __construct(App $app, SystemLogServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
        $this->services->deleteLog();
    }

    /**
     * 显示操作记录
     */
    public function index()
    {
        $param = $this->request->param();
        $param['pages'] = $this->request->param('pages', 1);
        $param['path'] = $this->request->param('path', '');
        $param['ip'] = $this->request->param('ip','');
        $param['admin_id'] = $this->request->param('admin_id', '');
        $param['data'] = $this->request->param('data', '');
        $param['time'] = $this->request->param('time', '');

        return $this->success($this->services->getLogList($param, (int)$this->adminInfo['level']));
    }

    public function search_admin(SystemAdminServices $services)
    {
        $info = $services->getOrdAdmin('id,real_name', $this->adminInfo['level']);
        return $this->success(compact('info'));
    }

}

