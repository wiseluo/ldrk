<?php

namespace app\dao;

use app\dao\BaseDao;
use app\model\AinatAdmin;

class AinatAdminDao extends BaseDao
{
    protected function setModel(): string
    {
        return AinatAdmin::class;
    }

    /**
     * 用管理员名查找管理员信息
     * @param string $account
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function accountByAdmin(string $account)
    {
        return $this->search(['account' => $account, 'is_del' => 0, 'status' => 1])->find();
    }

    /**
     * 获取adminid
     * @param int $level
     * @return array
     */
    public function getAdminIds(int $level)
    {
        return $this->getModel()->where('level', '>=', $level)->column('id', 'id');
    }

    /**
     * 获取低于等级的管理员名称和id
     * @param string $field
     * @param int $level
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getOrdAdmin(string $field = 'real_name,id', int $level = 0)
    {
        return $this->getModel()->where('level', '>=', $level)->field($field)->select()->toArray();
    }

    public function getOrdAdminIds(string $key = 'real_name', string $keyword = '')
    {
        return $this->getModel()->where($key, 'like', "%".$keyword."%")->column('id');
    }

    public function getList($param, $admin)
    {
        $where = [];
        $where[] = ['is_del', '=', $param['is_del']];
        $where[] = ['id', '<>', 1];

        if($param['real_name']) {
            $where[] = ['real_name|phone', 'LIKE', '%'. $param['real_name'] .'%'];
        }
        if($param['status'] !== '') {
            $where[] = ['status', '=', $param['status']];
        }
        if($param['roles']) {
            // if(is_array($param['roles'])){
            //     $roles_arr = $param['roles'];
            // }else{
            //     $roles_arr = explode(',',$param['roles']);
            // }
            
            // $where[] = ['roles', 'in', $roles_arr];
            $where[] = ['roles', 'like', '%'. $param['roles'] .'%'];
        }

        return $this->getModel()::where($where)
            ->order('id', 'desc')
            ->paginate($param['size'])
            ->toArray();
    }
    
}
