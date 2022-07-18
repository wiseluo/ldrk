<?php
namespace app\controller\applet;

use think\facade\App;
use crmeb\basic\BaseController;
use app\services\applet\PlaceServices;
use app\validate\applet\PlaceValidate;

class Place extends BaseController
{

    public function __construct(App $app, PlaceServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    public function read()
    {
        $res = $this->services->readService($this->request->tokenUser()['id']);
        if($res['status']) {
            return show(200, $res['msg'], $res['data']);
        }else{
            return show(404, $res['msg']);
        }
    }

    public function apply()
    {
        $param = $this->request->param();
        $validate = new PlaceValidate();
        if(!$validate->scene('save')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->services->saveService($param, $this->request->tokenUser()['id']);
        if($res['status'] == 1) {
            return show(200, $res['msg']);
        }else{
            return show(400, $res['msg']);
        }
    }

    public function applyV2()
    {
        //test_log('applyV2');
        $param = $this->request->param();
        //test_log($param);
        try{
            $validate = new PlaceValidate();
            if(!$validate->scene('applyV2')->check($param)) {
                //test_log('PlaceValidate:'.$validate->getError());
                return show(400, $validate->getError());
            }
            $res = $this->services->applyV2Service($param, $this->request->tokenUser()['id']);
            if($res['status']) {
                return show(200, $res['msg']);
            }else{
                return show(400, $res['msg']);
            }
        }catch(\Exception $e){
            test_log('applyV2 error:'. $e->getMessage());
            return show(400, $e->getMessage());
        }
    }

    public function update()
    {
        $param = $this->request->param();
        $validate = new PlaceValidate();
        if(!$validate->scene('update')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->services->updateService($param, $this->request->tokenUser()['id']);
        if($res['status'] == 1) {
            return show(200, $res['msg']);
        }else{
            return show(400, $res['msg']);
        }
    }

    public function type()
    {
        $param = $this->request->param();
        $param['place_type'] = $this->request->param('place_type', '');
        return show(200, '成功', $this->services->getTypeList($param));
    }

    public function delete($id)
    {
        $res = $this->services->deleteService($id, $this->request->tokenUser()['id']);
        if($res['status']) {
            return show(200, $res['msg']);
        }else{
            return show(400, $res['msg']);
        }
    }
}
