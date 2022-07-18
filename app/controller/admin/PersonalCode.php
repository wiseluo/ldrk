<?php
namespace app\controller\admin;

use think\facade\App;
use app\controller\admin\AuthController;
use app\services\admin\PersonalCodeServices;
use app\validate\admin\PersonalCodeValidate;

class PersonalCode extends AuthController
{
    public function __construct(App $app, PersonalCodeServices $services)
    {
        parent::__construct($app);
        $this->service = $services;
    }

    public function index()
    {
        $param = $this->request->param();
        $param['size'] = $this->request->param('size', 10);
        return show(200, '成功', $this->service->getListService($param));
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



}
