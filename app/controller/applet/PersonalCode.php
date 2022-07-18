<?php
namespace app\controller\applet;

use think\facade\App;
use crmeb\basic\BaseController;
use app\services\applet\PersonalCodeServices;
use app\validate\applet\PersonalCodeValidate;

class PersonalCode extends BaseController
{

    public function __construct(App $app, PersonalCodeServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    public function index()
    {
        $param = $this->request->param();
        $param['size'] = $this->request->param('size', 10);
        $res = $this->services->getListService($param, $this->request->tokenUser()['id_card']);
        return show(200, '成功', $res);
    }

    public function read($id)
    {
        $res = $this->services->readService($id);
        if($res['status']) {
            return show(200, $res['msg'], $res['data']);
        }else{
            return show(404, $res['msg']);
        }
    }

    public function save()
    {
        $param = $this->request->param();
        $validate = new PersonalCodeValidate();
        if(!$validate->scene('save')->check($param)) {
            return show(400, $validate->getError());
        }
        $param['phone'] = $this->request->param('phone', '');
        $param['urgent_phone'] = $this->request->param('urgent_phone', '');
        $res = $this->services->saveService($param, $this->request->tokenUser());
        if($res['status'] == 1) {
            return show(200, $res['msg'], $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }

    public function update($id)
    {
        $param = $this->request->param();
        $validate = new PersonalCodeValidate();
        if(!$validate->scene('update')->check($param)) {
            return show(400, $validate->getError());
        }
        $param['phone'] = $this->request->param('phone', '');
        $param['urgent_phone'] = $this->request->param('urgent_phone', '');
        $res = $this->services->updateService($id, $param, $this->request->tokenUser());
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

}
