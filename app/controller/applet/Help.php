<?php
namespace app\controller\applet;

use think\facade\App;
use crmeb\basic\BaseController;
use app\services\applet\HelpServices;

class Help extends BaseController
{

    public function __construct(App $app, HelpServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    public function index()
    {
        $param = $this->request->param();
        return show(200, '成功', $this->services->getList($param));
    }

    public function detail(){
        $id = (int)$this->request->param('id');
        $res = $this->services->readService($id);
        if($res['status']) {
            return show(200, $res['msg'], $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }


}
