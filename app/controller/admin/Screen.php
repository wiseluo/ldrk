<?php
namespace app\controller\admin;

use think\facade\App;
use app\services\admin\ScreenServices;
use crmeb\basic\BaseController;

class Screen extends BaseController
{
    public function __construct(App $app, ScreenServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    public function declareDate()
    {
        $param = $this->request->param();
        $param['date_type'] = $this->request->param('date_type', 'today');
        $res = $this->services->declareDateService($param);
        return show(200, '成功', $res);
    }

    public function sourceProvince()
    {
        $data = $this->services->sourceProvinceService();
        return show(200, '成功', $data);
    }

    public function floatingPopulation()
    {
        $res = $this->services->floatingPopulationService();
        return show(200, '成功', $res);
    }
    
    public function riskareaCome()
    {
        $res = $this->services->riskareaComeService();
        return show(200, '成功', $res);
    }

    public function backouttime(){
        $res = $this->services->backouttimeService();
        return show(200, '成功', $res);
    }
    
    public function riskareaProvinceCome()
    {
        $type = $this->request->param('type', 'come');
        $res = $this->services->riskareaProvinceService($type);
        return show(200, '成功', $res);
    }

    public function comeYWStreet()
    {
        $res = $this->services->comeYWStreetService();
        return show(200, '成功', $res);
    }

    public function nums(){
        $res = $this->services->getOwnDeclareNums();
        return show(200, '成功', $res);
    }


    public function control(){
        $res = $this->services->getControlNums();
        return show(200, '成功', $res);
    }

    public function riskarea(){
        $res = $this->services->getRiskarea();
        return show(200, '成功', $res);
    }


}
