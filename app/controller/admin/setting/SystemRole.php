<?php
namespace app\controller\admin\setting;

use app\controller\admin\AuthController;
use app\services\admin\system\admin\SystemRoleServices;
use app\services\admin\system\SystemMenusServices;
use think\facade\App;

/**
 * Class SystemRole
 * @package app\controller\admin\v1\setting
 */
class SystemRole extends AuthController
{
    /**
     * SystemRole constructor.
     * @param App $app
     * @param SystemRoleServices $services
     */
    public function __construct(App $app, SystemRoleServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $where = $this->request->getMore([
            ['status', ''],
            ['role_name', ''],
        ]);
        return $this->success($this->services->getRoleList($where));
    }

    public function roleSelect()
    {
        $param = $this->request->param();
        return show('200', '', $this->services->getRoleSelect($param));
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create(SystemMenusServices $services)
    {
        $menus = $services->getMenus($this->adminInfo['level'] == 0 ? [] : $this->adminInfo['roles']);
        return $this->success(compact('menus'));
    }

    /**
     * 保存新建的资源
     *
     * @return \think\Response
     */
    public function save($id)
    {
        $data = $this->request->postMore([
            'role_name',
            ['status', 0],
            ['level', 3],
            ['checked_menus', [], '', 'rules']
        ]);
        if (!$data['role_name']) return $this->fail('请输入身份名称');
        if (!is_array($data['rules']) || !count($data['rules']))
            return $this->fail('请选择最少一个权限');
        $data['rules'] = implode(',', $data['rules']);
        if ($id) {
            unset($data['level']);
            if (!$this->services->update($id, $data)) return $this->fail('修改失败!');
            \think\facade\Cache::clear();
            return $this->success('修改成功!');
        } else {
            if (!$this->services->save($data)) return $this->fail('添加身份失败!');
            \think\facade\Cache::clear();
            return $this->success('添加身份成功!');
        }
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param int $id
     * @return \think\Response
     */
    public function edit(SystemMenusServices $services, $id)
    {
        $role = $this->services->get($id);
        if (!$role) {
            return $this->fail('修改的规格不存在');
        }
        $menus = $services->getMenus($this->adminInfo['level'] == 0 ? [] : $this->adminInfo['roles']);
        return $this->success(['role' => $role->toArray(), 'menus' => $menus]);
    }

    /**
     * 删除指定资源
     *
     * @param int $id
     * @return \think\Response
     */
    public function delete($id)
    {
        if($id <= 9) {
            return $this->fail('系统角色，不能删除');
        }
        if (!$this->services->delete($id))
            return $this->fail('删除失败,请稍候再试!');
        else {
            \think\facade\Cache::clear();
            return $this->success('删除成功!');
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
        if (!$id) {
            return $this->fail('缺少参数');
        }
        // if($id <= 9) {
        //     return $this->fail('系统角色，不能修改');
        // }
        $role = $this->services->get($id);
        if (!$role) {
            return $this->fail('没有查到此身份');
        }
        $role->status = $status;
        if ($role->save()) {
            \think\facade\Cache::clear();
            return $this->success('修改成功');
        } else {
            return $this->fail('修改失败');
        }
    }
}
