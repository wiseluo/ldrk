<?php
namespace app\controller\user;

use think\facade\App;
use crmeb\basic\BaseController;
use app\services\SgzxServices;
use app\validate\user\SgzxValidate;
use think\facade\Log;

//数管中心
class Sgzx extends BaseController
{
    public function __construct(App $app, SgzxServices $services)
    {
        parent::__construct($app);
        $this->service = $services;
    }

    public function phpinfo(){
        echo phpinfo();
    }

    public function test_ff(){
        echo Date('Y-m-d');
        Log::error(Date('Y-m-d'));
    }


    public function qgrkk()
    {
        return show(200,'暂时放行1',[]);

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
