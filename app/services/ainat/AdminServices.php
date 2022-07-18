<?php

namespace app\services\ainat;

use app\services\ainat\BaseServices;
use crmeb\exceptions\AdminException;
use app\dao\AinatAdminDao;

/**
 * Class SystemAdminServices
 * @package app\services\system\admin
 */
class AdminServices extends BaseServices
{
    public function __construct(AinatAdminDao $dao)
    {
        $this->dao = $dao;
    }

    public function verifyLogin($adminInfo)
    {
        if ($adminInfo->is_del == 1) {
            throw new AdminException('您已被禁止登录!', 400);
        }
        if (!$adminInfo->status) {
            throw new AdminException('您已被禁止登录!', 400);
        }
        $adminInfo->last_ip = app('request')->ip();
        $adminInfo->last_time = time();
        $adminInfo->login_count++;
        $adminInfo->save();

        return $adminInfo;
    }

    /**
     * 后台登陆获取菜单获取token
     * @param string $type zzd=浙政钉登录 web=浏览器直接登录
     * @param string $account
     * @return array
     */
    public function login($adminInfo, $login_by='')
    {
        $tokenInfo = $this->createToken($adminInfo->id, 'admin', $login_by);
        // /** @var SystemMenusServices $services */
        // $services = app()->make(SystemMenusServices::class);
        // [$menus, $uniqueAuth] = $services->getMenusList($adminInfo->role_id, (int)$adminInfo['level']);
        return [
            'token' => $tokenInfo['token'],
            'expires_time' => $tokenInfo['params']['exp'],
            //'menus' => $menus,
            //'unique_auth' => $uniqueAuth,
            'user_info' => [
                'id' => $adminInfo->getData('id'),
                'account' => $adminInfo->getData('account'),
                'real_name' => $adminInfo->real_name,
                'role_id' => $adminInfo->role_id,
                'role_code' => $adminInfo->role_code,
            ],
            'logo' => sys_config('site_logo'),
            'logo_square' => sys_config('site_logo_square'),
            'version' => get_crmeb_version(),
        ];
    }

    public function adminInfoAuth($admin_id){
        $adminInfo = $this->dao->get(['id'=> $admin_id]);
        /** @var SystemMenusServices $services */
        $services = app()->make(SystemMenusServices::class);
        [$menus, $uniqueAuth] = $services->getMenusList($adminInfo->role_id, (int)$adminInfo['level']);
        return [
            'menus' => $menus,
            'unique_auth' => $uniqueAuth,
            'user_info' => [
                'id' => $adminInfo->getData('id'),
                'account' => $adminInfo->getData('account'),
                'real_name' => $adminInfo->real_name,
                'role_id' => $adminInfo->role_id,
                'role_code' => $adminInfo->role_code,
            ]
        ];
    }

    public function smslogin($phone)
    {
        $admin = $this->dao->get(['phone'=> $phone]);
        if (!$admin) {
            throw new AdminException('管理员不存在!', 400);
        }
        $adminInfo = $this->verifyLogin($admin);
        return $this->login($adminInfo, 'sms');
    }

    /**
     * 获取登陆前的login等信息
     * @return array
     */
    public function getLoginInfo()
    {
        return [
            'slide' => sys_data('admin_login_slide') ?? [],
            'logo_square' => sys_config('site_logo_square'),//透明
            'logo_rectangle' => sys_config('site_logo'),//方形
            'login_logo' => sys_config('login_logo')//登陆
        ];
    }

    public function getService($id)
    {
        return $this->dao->get($id);
    }

    public function getAdminIds($keyword)
    {
        return $this->dao->getOrdAdminIds('real_name',$keyword);
    }
    
    /**
     * 管理员列表
     * @param array $where
     * @return array
     */
    public function getAdminList(array $param, $admin)
    {
        $list = $this->dao->getList($param, $admin);
        $allRole = app()->make(SystemRoleServices::class)->getRoleArray();
        foreach ($list['data'] as &$item) {
            if ($item['roles']) {
                $roles = [];
                foreach ($item['roles'] as $id) {
                    if (isset($allRole[$id])) $roles[] = $allRole[$id];
                }
                if ($roles) {
                    $item['roles_text'] = implode(',', $roles);
                } else {
                    $item['roles_text'] = '';
                }
            }
        }
        return $list;
    }

    public function saveService(array $param,$adminInfo=[])
    {
        if ($this->dao->count(['account' => $param['phone']])) {
            throw new AdminException('管理员手机号已存在:'.$param['phone'], 400);
        }
        
        $roles = explode(',', $param['roles']);
        $role = app()->make(SystemRoleServices::class)->getService($roles[0]);

        $data = [
            'account' => $param['phone'],
            'phone' => $param['phone'],
            'real_name' => $param['real_name'],
            'pwd' => $this->passwordHash('cs123456'),
            'roles' => $param['roles'],
            'role_id' => $roles[0], //默认当前角色为第一个角色
            'level' => $role['level'], //级别默认为当前角色的取数据级别
            'status' => $param['status'],
            'yw_street_id' => $param['yw_street_id'],
        ];

        try {
            $this->dao->save($data);
            \think\facade\Cache::clear();
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }

    public function updateService($param, $id,$adminInfo=[])
    {
        $admin = $this->dao->get($id);
        $data = [];
        if ($admin == null) {
            throw new AdminException('管理员不存在,无法修改', 400);
        }
        if ($admin->is_del) {
            if(!isset($param['is_del'])){
                throw new AdminException('管理员已经删除', 400);
            }else{
                $data['is_del'] = 0;
            }
        }
        
        //修改账号
        if ($param['phone'] != $admin->account) {
            if($this->dao->isAccountUseable($param['phone'], $id)) {
                throw new AdminException('管理员手机号已存在', 400);
            }else{
                $data['phone'] = $param['phone'];
                $data['account'] = $param['phone'];
            }
        }
        
        if($param['roles'] != $admin->roles) {
            $roles = explode(',', $param['roles']);
            $role = app()->make(SystemRoleServices::class)->getService($roles[0]);
            $data['level'] = $role['level'];
            $data['role_id'] = $roles[0];
            $data['roles'] = $param['roles'];
        }
        
        $data['status'] = $param['status'];
        $data['yw_street_id'] = $param['yw_street_id'];
        try {
            $this->dao->update($id, $data);
            \think\facade\Cache::clear();
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }

    public function getMaxRoleLevelService($roles)
    {
        //取数据级别为角色等级最大的，默认3只取自己的数据
        $level = 3;
        foreach($roles as $k => $v) {
            $role = app()->make(SystemRoleServices::class)->getService($v);
            if($role == null) {
                throw new AdminException($v.'-角色不存在', 400);
            }
            if($level > $role['level']) {
                $level = $role['level'];
            }
        }
        return $level;
    }

    public function adminRolesService($admin_id)
    {
        $roles = $this->dao->value(['id'=> $admin_id], 'roles');
        $roles_arr = explode(',', $roles);
        return array_values(app()->make(SystemRoleServices::class)->getRoleArray(['id'=> $roles_arr], 'id,role_name,role_code'));
    }

    public function switchAdminRolesService($admin_id, $role_id)
    {
        $roles = $this->dao->value(['id'=> $admin_id], 'roles');
        $roles_arr = explode(',', $roles);
        if(!in_array($role_id, $roles_arr)) {
            return ['status'=> 0, 'msg'=> '该角色不属于您的所属角色'];
        }
        $role = app()->make(SystemRoleServices::class)->getService($role_id);
        try{
            $this->dao->update($admin_id, ['role_id'=> $role_id, 'level'=> $role['level']]);
            $auth = $this->adminInfoAuth($admin_id);
            return ['status'=> 1, 'msg'=> '角色切换中...', 'data'=> $auth];
        } catch (\Exception $e) {
            return ['status'=> 0, 'msg'=> '切换角色失败'];
        }
    }

    public function deleteService($id)
    {
        try {
            $this->dao->update((int)$id, ['is_del' => 1, 'status' => 0]);
            return ['status'=>1, 'msg'=> '删除成功'];
        } catch (\Exception $e){
            return ['status'=>0, 'msg'=>$e->getMessage()];
        }
    }

}
