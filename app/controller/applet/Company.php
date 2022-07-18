<?php
namespace app\controller\applet;

use think\facade\App;
use crmeb\basic\BaseController;
use app\services\applet\CompanyServices;
use app\validate\applet\CompanyValidate;

class Company extends BaseController
{

    public function __construct(App $app, CompanyServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    public function index()
    {
        return show(200, '成功', $this->services->getUserCompanyList($this->request->tokenUser()['id']));
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

    public function save()
    {
        $param = $this->request->param();
        // $param['company_classify'] = $this->request->param('company_classify', 'company');
        // if($param['company_classify'] == 'gov'){
        //     return show(400, '暂时不支持该企业类型的申请');
        // }
        $validate = new CompanyValidate();
        if(!$validate->scene('save')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->services->saveService($param, $this->request->tokenUser());
        if($res['status'] == 1) {
            return show(200, $res['msg']);
        }else{
            return show(400, $res['msg']);
        }
    }

    public function update()
    {
        $param = $this->request->param();
        $validate = new CompanyValidate();
        if(!$validate->scene('update')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->services->updateService($param, $this->request->tokenUser());
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
    
    public function classifyVerify()
    {
        $param = $this->request->param();
        $validate = new CompanyValidate();
        if(!$validate->scene('classifyVerify')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->services->classifyVerifyService($param);
        if($res['status'] == 1) {
            return show(200, $res['msg'], $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }
    
    public function classifyList()
    {
        return show(200, '成功', $this->services->classifyListService());
    }
    
    public function staffClassifyList()
    {
        $param = $this->request->param();
        return show(200, '成功', $this->services->staffClassifyListService($param));
    }
}
