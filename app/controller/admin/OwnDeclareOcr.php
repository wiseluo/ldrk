<?php
namespace app\controller\admin;

use think\facade\App;
use app\controller\admin\AuthController;
use app\services\admin\OwnDeclareOcrServices;

class OwnDeclareOcr extends AuthController
{
    public function __construct(App $app, OwnDeclareOcrServices $services)
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


}
