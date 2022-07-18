<?php
namespace app\controller\applet;

use think\facade\App;
use crmeb\basic\BaseController;
use app\services\applet\DistrictServices;
use think\facade\Cache;

class District extends BaseController
{

    public function __construct(App $app, DistrictServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    public function index()
    {
        $param = $this->request->param();
        $param['pid'] = $this->request->param('pid', 0);
        $hasCache = Cache::get('District_pid_'.$param['pid']);
        if($hasCache){
            // test_log('from District_pid_'.$param['pid'].' Cache');
            return show(200, '成功', $hasCache);
        }else{
            $data = $this->services->getList($param);
            if($data){
                // test_log('from District_pid_'.$param['pid'].' DB');
                Cache::set('District_pid_'.$param['pid'],$data,36000);
            }
            return show(200, '成功', $data);
        }

        // return show(200, '成功', $this->services->getList($param));
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
