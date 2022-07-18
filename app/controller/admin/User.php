<?php
namespace app\controller\admin;

use app\controller\admin\AuthController;
use think\facade\App;
use app\services\admin\UserServices;
use app\validate\admin\UserValidate;

class User extends AuthController
{
    /**
     * user constructor.
     * @param App $app
     * @param UserServices $service
     */
    public function __construct(App $app, UserServices $service)
    {
        parent::__construct($app);
        $this->service = $service;
    }

    public function index()
    {
        $param = $this->request->param();
        $param['size'] = $this->request->param('size', 10);
        $res = $this->service->getListService($param);
        return show(200, '成功', $res);
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

    public function subChange()
    {
        $param = $this->request->param();
        $param['size'] = $this->request->param('size', 10);
        $res = $this->service->getSubChangeListService($param);
        return show(200, '成功', $res);
    }
}
