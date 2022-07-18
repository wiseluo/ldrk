<?php
namespace app\controller\admin;

use think\facade\App;
use app\controller\admin\AuthController;
use app\services\admin\PlaceServices;
use app\validate\admin\PlaceValidate;

class Place extends AuthController
{

    public function __construct(App $app, PlaceServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    public function index()
    {
        $param = $this->request->param();
        $param['size'] = $this->request->param('size', 10);
        return show(200, '成功', $this->services->getList($param));
    }

    public function deleteList()
    {
        $param = $this->request->param();
        $param['size'] = $this->request->param('size', 10);
        $res = $this->services->deleteListService($param);
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
        $validate = new PlaceValidate();
        if(!$validate->scene('save')->check($param)) {
            return show(400, $validate->getError());
        }
        $param['superior_gov'] = $this->request->param('superior_gov', '');
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
        $validate = new PlaceValidate();
        if(!$validate->scene('update')->check($param)) {
            return show(400, $validate->getError());
        }
        $param['superior_gov'] = $this->request->param('superior_gov', '');
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

    public function restore($id)
    {
        $res = $this->services->restoreService($id);
        if($res['status']) {
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


    // 批量行程码校验等级
    public function batchUpdateXcmLevel(){
        $param = $this->request->param();
        $res = $this->services->batchUpdateXcmLevel($param);
        if($res['status']) {
            return show(200, $res['msg']);
        }else{
            return show(400, $res['msg']);
        }
    }
    public function batchUpdateIsNeedFace(){
        $param = $this->request->param();
        $res = $this->services->batchUpdateIsNeedFace($param);
        if($res['status']) {
            return show(200, $res['msg']);
        }else{
            return show(400, $res['msg']);
        }
    }
    

}
