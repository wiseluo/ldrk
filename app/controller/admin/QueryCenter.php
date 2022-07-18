<?php
namespace app\controller\admin;

use think\facade\App;
use app\controller\admin\AuthController;
use app\services\admin\QueryCenterServices;
use app\validate\admin\QueryCenterValidate;

class QueryCenter extends AuthController
{

    public function __construct(App $app, QueryCenterServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    public function healthInfo()
    {
        $param = $this->request->param();
        $validate = new QueryCenterValidate();
        if(!$validate->scene('healthInfo')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->services->healthInfoService($param);
        if($res['status']) {
            return show(200, $res['msg'], $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }

    public function rygk()
    {
        $param = $this->request->param();
        $param['size'] = $this->request->param('size', 10);
        $res = $this->services->rygkService($param);
        return show(200, '成功', $res);
    }

    public function placeClassifyDateNums(){
        $param = $this->request->param();
        $res = $this->services->placeClassifyDateNums($param);
        return show(200, '成功', $res);
    }

    public function placeTypeDateNums(){
        $param = $this->request->param();
        $res = $this->services->placeTypeDateNums($param);
        return show(200, '成功', $res);
    }
    public function placeStreetDateNums(){
        $param = $this->request->param();
        $res = $this->services->placeStreetDateNums($param);
        return show(200, '成功', $res);
    }
    public function placeDateNumsByName(){
        $param = $this->request->param();
        $name = $param['name'];
        $res = $this->services->placeDateNumsByName($param,$name);
        return show(200, '成功', $res);
    }

    public function placeHourNums(){
        $param = $this->request->param();
        $res = $this->services->placeHourNums($param);
        return show(200, '成功', $res);
    }



}
