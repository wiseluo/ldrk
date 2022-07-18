<?php
namespace app\controller\admin;

use think\facade\App;
use app\services\admin\MessageRecordServices;
use app\controller\admin\AuthController;

/**
 * 消息控制器
 * Class SystemClearData
 * @package app\controller\admin\v1\system
 */
class MessageRecord extends AuthController
{
    public function __construct(App $app, MessageRecordServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    public function index()
    {
        $param['keyword'] = $this->request->param('keyword', '');
        $param['status'] = $this->request->param('status', '');
        $param['isread'] = $this->request->param('isread', '');
        $param['page'] = $this->request->param('page', 0);
        $param['size'] = $this->request->param('size', 10);
        $res = $this->services->getRecordList($param, $this->adminId);
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

    public function query()
    {
        $param['keyword'] = $this->request->param('keyword', '');
        $param['status'] = $this->request->param('status', '');
        $param['isread'] = $this->request->param('isread', '');
        $param['page'] = $this->request->param('page', 0);
        $param['size'] = $this->request->param('size', 10);
        $res = $this->services->getRecordQueryList($param);
        return show(200, '成功', $res);
    }
}