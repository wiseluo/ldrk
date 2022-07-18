<?php
namespace app\controller\applet;

use think\facade\App;
use crmeb\basic\BaseController;
use app\services\SgzxServices;
use app\validate\applet\SgzxValidate;

//数管中心
class Sgzx extends BaseController
{
    public function __construct(App $app, SgzxServices $services)
    {
        parent::__construct($app);
        $this->service = $services;
    }

    public function qgrkk()
    {
        $param = $this->request->param();
        $validate = new SgzxValidate();
        if(!$validate->scene('qgrkk')->check($param)) {
            return show(40001, $validate->getError());
        }
        
        $res = $this->service->rkkService($param['real_name'], $param['id_card']);
        if($res['status'] == -1) {
            return show(40001, $res['msg']);
        }else if($res['status'] == 1) {
            return show(200, $res['msg'], $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }

}
