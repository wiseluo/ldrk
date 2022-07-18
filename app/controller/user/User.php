<?php

namespace app\controller\user;

use app\controller\user\AuthController;
use app\services\user\UserServices;
use crmeb\services\CacheService;
use think\facade\{App, Config};
use app\validate\user\UserValidate;
use \behavior\SmsVerifyTool;
use app\validate\admin\common\SmsValidate;

class User extends AuthController
{
    public function __construct(App $app, UserServices $service)
    {
        parent::__construct($app);
        $this->service = $service;
    }

    public function index()
    {
        $param = $this->request->param();
        $param['keyword'] = $this->request->param('keyword', '');
        $param['user_category_id'] = $this->request->param('user_category_id', '');
        $param['status'] = $this->request->param('status', '');
        $param['job_status'] = $this->request->param('job_status', '');
        $param['page'] = $this->request->param('page', 1);
        $param['size'] = $this->request->param('size', 10);
        $param['company_id'] = $this->userInfo['company_id'];
        $param['list_type'] = $this->request->param('list_type', '');
        $res = $this->service->getListService($param);
        return show(200, '成功', $res);
    }

    public function read($id)
    {
        $res = $this->service->readService($id);
        $company_id = $this->userInfo['company_id'];
        if($res['status']) {
            if($res['data']['company_id'] != $company_id){
                if($id == $this->userInfo['id']){
                    return show(200, $res['msg'], $res['data']);
                }else{
                    return show(400, '不能查看非本公司人员的信息');
                }
            }else{
                return show(200, $res['msg'], $res['data']);
            }
            return show(200, $res['msg'], $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }

    public function save()
    {
        $param = $this->request->param();
        $validate = new UserValidate();
        if(!$validate->scene('save')->check($param)) {
            return show(400, $validate->getError());
        }
        $param['card_id'] = strtoupper($this->request->param('card_id', ''));
        $param['pid'] = $this->request->param('pid',0);
        $param['pname'] = $this->request->param('pname','');
        $param['user_type'] = $this->request->param('user_type', 'user');
        $param['permanent_address'] = $this->request->param('permanent_address', '');
        $param['position'] = $this->request->param('position', '');
        $param['job_status'] = $this->request->param('job_status', 1);
        $param['ischeck'] = $this->request->param('ischeck', 1);
        $param['foreigner'] = 0;
        $res = $this->service->saveService($param, $this->userInfo);
        if($res['status']) {
            return show(200, $res['msg']);
        }else{
            return show(400, $res['msg']);
        }
    }

    public function update($id)
    {
        $param = $this->request->param();
        $validate = new UserValidate();
        if(!$validate->scene('update')->check($param)) {
            return show(400, $validate->getError());
        }
        $param['position'] = $this->request->param('position', '');
        $res_user = $this->service->getUserInfo($id, $this->userInfo);
        if(!$res_user['status']) {
            return show(400, $res_user['msg']);
        }
        $res = $this->service->updateService($param, $id);
        if($res['status']) {
            return show(200, $res['msg']);
        }else{
            return show(400, $res['msg']);
        }
    }

    public function delete($id)
    {
        $res = $this->service->deleteService($id);
        if($res['status']) {
            return show(200, $res['msg']);
        }else{
            return show(400, $res['msg']);
        }
    }
    
    /**
     * 退出登陆
     * @return mixed
     */
    public function logout()
    {
        $key = trim(ltrim($this->request->header(Config::get('cookie.token_name')), 'Bearer'));
        CacheService::redisHandler()->delete($key);
        return show(200, '登出成功');
    }

}
