<?php
namespace app\controller\admin\setting;

use app\controller\admin\AuthController;
use app\services\admin\system\admin\SystemAdminServices;
use app\validate\admin\setting\SystemAdminValidate;
use crmeb\services\CacheService;
use \behavior\SmsVerifyTool;
use think\facade\{App, Config};

/**
 * Class SystemAdmin
 * @package app\controller\admin\v1\setting
 */
class SystemAdmin extends AuthController
{
    /**
     * SystemAdmin constructor.
     * @param App $app
     * @param SystemAdminServices $services
     */
    public function __construct(App $app, SystemAdminServices $service)
    {
        parent::__construct($app);
        $this->service = $service;
    }

    /**
     * 显示管理员资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $param = $this->request->param();
        $param['real_name'] = $this->request->param('real_name', '');
        $param['status'] = $this->request->param('status', '');
        $param['is_del'] = $this->request->param('is_del', 0);
        $param['roles'] = $this->request->param('roles', '');
        $param['page'] = $this->request->param('page', 1);
        $param['size'] = $this->request->param('size', 10);
        $res = $this->service->getAdminList($param, $this->adminInfo);
        return show(200, '成功', $res);
    }

    /**
     * 保存管理员
     * @return mixed
     */
    public function save()
    {
        $param = $this->request->param();
        $validate = new SystemAdminValidate();
        if(!$validate->scene('save')->check($param)) {
            return show(400, $validate->getError());
        }

        $param['status'] = $this->request->param('status', 1);
        $param['yw_street_id'] = $this->request->param('yw_street_id', 0);
        $res = $this->service->saveService($param,$this->adminInfo);
        if($res['status']) {
            return show(200, $res['msg']);
        }else{
            return show(400, $res['msg']);
        }
    }

    /**
     * 修改管理员信息
     * @param $id
     * @return mixed
     */
    public function update($id)
    {
        $param = $this->request->param();
        $validate = new SystemAdminValidate();
        if(!$validate->scene('update')->check($param)) {
            return show(400, $validate->getError());
        }

        $param['status'] = $this->request->param('status', 1);
        $param['yw_street_id'] = $this->request->param('yw_street_id', 0);
        $res = $this->service->updateService($param, $id,$this->adminInfo);
        if($res['status']) {
            return show(200, $res['msg']);
        }else{
            return show(400, $res['msg']);
        }
    }

    /**
     * 删除管理员
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        if (!$id) return show(400, '删除失败，缺少参数');
        $res = $this->service->deleteService($id);
        if($res['status']) {
            return show(200, $res['msg']);
        }else{
            return show(400, $res['msg']);
        }
    }

    /**
     * 修改状态
     * @param $id
     * @param $status
     * @return mixed
     */
    public function set_status($id, $status)
    {
        $this->service->update((int)$id, ['status' => $status]);
        return show(200, '操作成功');
    }

    /**
     * 获取当前登陆管理员的信息
     * @return mixed
     */
    public function info()
    {
        return show(200, '成功', $this->adminInfo);
    }

    /**
     * 退出登陆
     * @return mixed
     */
    public function logout()
    {
        $key = trim(ltrim($this->request->header(Config::get('cookie.token_name')), 'Bearer'));
        CacheService::redisHandler()->delete($key);
        return $this->success();
    }

    public function adminRoles()
    {
        $res = $this->service->adminRolesService($this->adminId);
        return show(200, '成功', $res);
    }

    public function switchAdminRoles($role_id)
    {
        $res = $this->service->switchAdminRolesService($this->adminId, $role_id);
        if($res['status']) {
            return show(200, $res['msg'], $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }
    
    //绑定手机号
    public function phoneBinding()
    {
        $param = $this->request->param();
        $validate = new SystemAdminValidate();
        if(!$validate->scene('phoneBinding')->check($param)) {
            return show(400, $validate->getError());
        }
        $smsVerifyTool = new SmsVerifyTool();
        $sms_res = $smsVerifyTool->verifySmsCode('phone_binding', $param['phone'], $param['smscode']);
        if($sms_res['status'] == 0) {
            return show(400, $sms_res['msg']);
        }
        $admin_phone = $this->service->get(['phone'=> $param['phone']]);
        if($admin_phone) {
            if($admin_phone['id'] != $this->adminId) {
                return show(400, '该手机号已被别的账号绑定');
            }else{
                return show(400, '手机号已绑定，不用重复绑定');
            }
        }
        $admin = $this->service->get($this->adminId);
        $res = $admin->save(['phone'=> $param['phone'], 'account'=> $param['phone']]);
        if($res['status']) {
            return show(200, $res['msg']);
        }else{
            return show(400, $res['msg']);
        }
    }

    public function batchRole(){
        $param = $this->request->param();
        $res = $this->service->batchRole($param,$this->adminInfo);
        if($res['status']){
            return show(200, '成功');
        }
        return show(400, $res['msg']);
    }

    public function batchStatus()
    {
        $param = $this->request->param();
        $validate = new SystemAdminValidate();
        if(!$validate->scene('batchStatus')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->service->batchStatusService($param);
        if($res['status']) {
            return show(200, $res['msg']);
        }else{
            return show(400, $res['msg']);
        }
    }

    public function adminInfoAuth()
    {
        $param = $this->request->param();
        $res = $this->service->adminInfoAuth($this->adminId);
        return show(200, '成功',$res);

    }
}
