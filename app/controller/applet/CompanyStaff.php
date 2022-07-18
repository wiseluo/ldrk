<?php
namespace app\controller\applet;

use think\facade\App;
use crmeb\basic\BaseController;
use app\services\applet\CompanyStaffServices;
use app\validate\applet\CompanyStaffValidate;

class CompanyStaff extends BaseController
{

    public function __construct(App $app, CompanyStaffServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    public function index()
    {
        $param = $this->request->param();
        $param['size'] = $this->request->param('size', 10);
        $res = $this->services->getList($param, $this->request->tokenUser()['id']);
        if($res['status']) {
            return show(200, '成功', $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }

    public function listStatistics()
    {
        $res = $this->services->getListStatistics($this->request->tokenUser()['id']);
        if($res['status']) {
            return show(200, '成功', $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }

    public function scanGetName()
    {
        $param = $this->request->param();
        $validate = new CompanyStaffValidate();
        if(!$validate->scene('scanGetName')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->services->scanGetNameService($param, $this->request->tokenUser()['id']);
        if($res['status'] == 1) {
            return show(200, $res['msg'], $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }

    public function scanCode()
    {
        $param = $this->request->param();
        $validate = new CompanyStaffValidate();
        if(!$validate->scene('scanCode')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->services->scanCodeService($param, $this->request->tokenUser());
        if($res['status'] == 1) {
            return show(200, $res['msg']);
        }else{
            return show(400, $res['msg']);
        }
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

    public function batchDelete()
    {
        $param = $this->request->param();
        $validate = new CompanyStaffValidate();
        if(!$validate->scene('batchDelete')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->services->batchDeleteService($param['ids'], $this->request->tokenUser()['id']);
        if($res['status']) {
            return show(200, $res['msg']);
        }else{
            return show(400, $res['msg']);
        }
    }
    
    public function linkTransfer()
    {
        $param = $this->request->param();
        $validate = new CompanyStaffValidate();
        if(!$validate->scene('linkTransfer')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->services->linkTransferService($param['id'], $this->request->tokenUser()['id']);
        if($res['status']) {
            return show(200, $res['msg']);
        }else{
            return show(400, $res['msg']);
        }
    }

    public function oneclickRemind()
    {
        $param = $this->request->param();
        $validate = new CompanyStaffValidate();
        if(!$validate->scene('oneclickRemind')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->services->oneclickRemindService($param, $this->request->tokenUser()['id']);
        if($res['status']) {
            return show(200, $res['msg']);
        }else{
            return show(400, $res['msg']);
        }
    }
    
    public function checkFrequency()
    {
        $param = $this->request->param();
        $validate = new CompanyStaffValidate();
        if(!$validate->scene('checkFrequency')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->services->checkFrequencyService($param, $this->request->tokenUser()['id']);
        if($res['status'] == 1) {
            return show(200, $res['msg']);
        }else{
            return show(400, $res['msg']);
        }
    }
    
    public function userCheckFrequency()
    {
        $param = $this->request->param();
        $validate = new CompanyStaffValidate();
        if(!$validate->scene('userCheckFrequency')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->services->userCheckFrequencyService($param, $this->request->tokenUser()['id']);
        if($res['status'] == 1) {
            return show(200, $res['msg']);
        }else{
            return show(400, $res['msg']);
        }
    }
}
