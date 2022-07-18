<?php
namespace app\controller\admin;

use think\facade\App;
use app\controller\admin\AuthController;
use app\services\admin\CompanyServices;
use app\validate\admin\CompanyValidate;

class Company extends AuthController
{

    public function __construct(App $app, CompanyServices $services)
    {
        parent::__construct($app);
        $this->service = $services;
    }

    public function index()
    {
        $param = $this->request->param();
        $param['size'] = $this->request->param('size', 10);
        return show(200, '成功', $this->service->getList($param,$this->adminInfo));
    }

    public function unqualifiedList()
    {
        $param = $this->request->param();
        $param['size'] = $this->request->param('size', 10);
        return show(200, '成功', $this->service->getUnqualifiedList($param,$this->adminInfo));
    }

    public function unqualifiedSms()
    {
        $param = $this->request->param();
        $this->service->unqualifiedSmsService($param);
        return show(200, '已发送');
    }

    public function read($id)
    {
        $res = $this->service->readService($id);
        if($res['status']) {
            return show(200, $res['msg'], $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }

    public function update($id)
    {
        $param = $this->request->param();
        $validate = new CompanyValidate();
        if(!$validate->scene('update')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->service->updateService($param, $id);
        if($res['status']) {
            return show(200, $res['msg']);
        }else{
            return show(400, $res['msg']);
        }
    }

    public function delete($id)
    {
        $res = $this->service->deleteService($id);
        if($res['status']) {
            return show(200, $res['msg']);
        }else{
            return show(400, $res['msg']);
        }
    }

    public function staff()
    {
        $param = $this->request->param();
        $validate = new CompanyValidate();
        if(!$validate->scene('staff')->check($param)) {
            return show(400, $validate->getError());
        }
        $param['size'] = $this->request->param('size', 10);
        $res = $this->service->staffService($param);
        if($res['status']) {
            return show(200, $res['msg'], $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }

    public function staffCompanyList()
    {
        $param = $this->request->param();
        $validate = new CompanyValidate();
        if(!$validate->scene('staffCompanyList')->check($param)) {
            return show(400, $validate->getError());
        }
        $list = $this->service->staffCompanyListService($param['id']);
        return show(200, '成功', $list);
    }

    public function staffList()
    {
        $param = $this->request->param();
        $param['size'] = $this->request->param('size', 10);
        $list = $this->service->staffListService($param,$this->adminInfo);
        return show(200, '成功', $list);
    }

    public function staffDelete($id)
    {
        $res = $this->service->staffDeleteService($id);
        if($res['status']) {
            return show(200, $res['msg']);
        }else{
            return show(400, $res['msg']);
        }
    }

    public function checkFrequency()
    {
        $param = $this->request->param();
        $validate = new CompanyValidate();
        if(!$validate->scene('checkFrequency')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->service->checkFrequencyService($param);
        if($res['status']) {
            return show(200, $res['msg']);
        }else{
            return show(400, $res['msg']);
        }
    }
    
    public function transferLink()
    {
        $param = $this->request->param();
        $validate = new CompanyValidate();
        if(!$validate->scene('transferLink')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->service->transferLinkService($param);
        if($res['status']) {
            return show(200, $res['msg']);
        }else{
            return show(400, $res['msg']);
        }
    }

    // 批量修改企业类型
    public function batchUpdateCompanyClassify(){
        $param = $this->request->param();
        $validate = new CompanyValidate();
        if(!$validate->scene('batchUpdateCompanyClassify')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->service->batchUpdateCompanyClassify($param);
        if($res['status']) {
            return show(200, $res['msg']);
        }else{
            return show(400, $res['msg']);
        }
    }

    // 批量修员工类型
    public function batchUpdateCompanyStaffClassify(){
        $param = $this->request->param();
        $validate = new CompanyValidate();
        if(!$validate->scene('batchUpdateCompanyStaffClassify')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->service->batchUpdateCompanyStaffClassify($param);
        if($res['status']) {
            return show(200, $res['msg']);
        }else{
            return show(400, $res['msg']);
        }
    }


}
