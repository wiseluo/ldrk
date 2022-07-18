<?php
namespace app\controller\user;

use think\facade\App;
use crmeb\basic\BaseController;
use app\services\user\BarrierDistrictServices;

class BarrierDistrict extends BaseController
{

    public function __construct(App $app, BarrierDistrictServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    public function index()
    {
        return show(200, '成功', $this->services->getBarrierListService());
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

}
