<?php
namespace app\controller\admin;

use think\facade\App;
use app\controller\admin\AuthController;
use app\services\admin\BarrierDistrictServices;
use app\validate\admin\BarrierDistrictValidate;

class BarrierDistrict extends AuthController
{
    public function __construct(App $app, BarrierDistrictServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    public function index()
    {
        $param = $this->request->param();
        $param['keyword'] = $this->request->param('keyword', '');
        $param['size'] = $this->request->param('size', 10);
        $res = $this->services->getList($param);
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

    public function save()
    {
        $param = $this->request->param();
        $validate = new BarrierDistrictValidate();
        if(!$validate->scene('save')->check($param)) {
            return show(400, $validate->getError());
        }
        $param['city_id'] = $this->request->param('city_id', 0);
        $param['county_id'] = $this->request->param('county_id', 0);
        $param['street_id'] = $this->request->param('street_id', 0);
        $res = $this->services->saveService($param);
        if($res['status']) {
            return show(200, $res['msg']);
        }else{
            return show(400, $res['msg']);
        }

    }

    public function update($id)
    {
        $param = $this->request->param();
        $validate = new BarrierDistrictValidate();
        if(!$validate->scene('update')->check($param)) {
            return show(400, $validate->getError());
        }
        $param['city_id'] = $this->request->param('city_id', 0);
        $param['county_id'] = $this->request->param('county_id', 0);
        $param['street_id'] = $this->request->param('street_id', 0);
        $res = $this->services->updateService($param, $id);
        if($res['status']) {
            return show(200, $res['msg']);
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

}
