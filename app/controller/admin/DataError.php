<?php
namespace app\controller\admin;

use think\facade\App;
use app\controller\admin\AuthController;
use app\dao\OwnDeclareOcrDao;
use app\services\admin\OwnDeclareServices;

class DataError extends AuthController
{

    public function __construct(App $app, OwnDeclareServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    public function leave()
    {
        $param = $this->request->param();
        $param['pid'] = $this->request->param('pid', 0);
        $declare_type = 'leave';
        return show(200, '成功', $this->services->getDataError($param,$declare_type));
    }

    public function come()
    {
        $param = $this->request->param();
        $param['pid'] = $this->request->param('pid', 0);
        $declare_type = 'come';
        return show(200, '成功', $this->services->getDataError($param,$declare_type));
    }

    public function riskarea()
    {
        $param = $this->request->param();
        $param['pid'] = $this->request->param('pid', 0);
        $declare_type = 'riskarea';
        return show(200, '成功', $this->services->getDataError($param,$declare_type));
    }

    public function ocr(){
        $param = $this->request->param();
        $dao = app()->make(OwnDeclareOcrDao::class);
        $param['list_type'] = 'data_error'; 
        return show(200, '成功', $dao->getList($param));
    }


    public function todayMany(){
        $param = $this->request->param();
        return show(200, '成功', $this->services->getTodayMany($param));
    }

    public function travelAsterisk()
    {
        $param = $this->request->param();
        $param['isasterisk'] = 1;
        return show(200, '成功', $this->services->travelAsteriskService($param));
    }

    public function jkmmzt()
    {
        $param = $this->request->param();
        return show(200, '成功', $this->services->jkmmztService($param));
    }
}
