<?php
namespace app\controller\admin\system;

use think\facade\App;
use app\services\admin\system\log\ClearServices;
use app\controller\admin\AuthController;


/**
 * 清除数据控制器
 * Class Clear
 * @package app\controller\admin\v1\system
 */
class Clear extends AuthController
{
    public function __construct(App $app, ClearServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * 刷新数据缓存
     */
    public function refresh_cache()
    {
        $this->services->refresCache();
        return $this->success('数据缓存刷新成功!');
    }


    /**
     * 删除日志
     */
    public function delete_log()
    {
        $this->services->deleteLog();
        return $this->success('数据缓存刷新成功!');
    }
}


