<?php
namespace app\controller\admin;

use think\facade\App;
use app\controller\admin\AuthController;
use app\services\admin\DistrictServices;
use app\dao\CommunityDao;

class District extends AuthController
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
        return show(200, '成功', $this->services->getList($param));
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

    public function communityList()
    {
        $yw_street_id = $this->request->param('yw_street_id', 0);
        if($yw_street_id == 0) {
            return show(400, '镇街id必填');
        }
        $list = app()->make(CommunityDao::class)->getListByYwStreetId($yw_street_id);
        return show(200, '成功', $list);
    }
}
