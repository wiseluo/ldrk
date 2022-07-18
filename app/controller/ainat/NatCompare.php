<?php
namespace app\controller\ainat;

use think\facade\App;
use app\controller\ainat\AuthController;
use app\services\ainat\NatCompareServices;
use app\validate\ainat\NatCompareValidate;

class NatCompare extends AuthController
{
    protected $service;

    public function __construct(App $app, NatCompareServices $services)
    {
        parent::__construct($app);
        $this->service = $services;
    }

    public function index()
    {
        $param = $this->request->param();
        $validate = new NatCompareValidate();
        if(!$validate->scene('index')->check($param)) {
            return show(400, $validate->getError());
        }
        $param['order'] = $this->request->param('order', '');
        $param['sort'] = $this->request->param('sort', 'asc');
        $param['size'] = $this->request->param('size', 100);
        return show(200, '成功', $this->service->indexService($param));
    }
    
    public function compare()
    {
        $param = $this->request->param();
        $validate = new NatCompareValidate();
        if(!$validate->scene('compare')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->service->compareService($param);
        if($res['status']) {
            return show(200, $res['msg']);
        }else{
            return show(400, $res['msg']);
        }
    }

    public function compareProgress()
    {
        $param = $this->request->param();
        $validate = new NatCompareValidate();
        if(!$validate->scene('compareProgress')->check($param)) {
            return show(400, $validate->getError());
        }
        return show(200, '成功', $this->service->compareProgressService($param));
    }

    public function actualHsjc()
    {
        $param = $this->request->param();
        $validate = new NatCompareValidate();
        if(!$validate->scene('actualHsjc')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->service->actualHsjcService($param);
        if($res['status']) {
            return show(200, $res['msg'], $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }

}
