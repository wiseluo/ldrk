<?php

declare (strict_types=1);

namespace app\dao;

use app\dao\BaseDao;
use app\model\UserManager;

class UserManagerDao extends BaseDao
{
    
    protected function setModel(): string
    {
        return UserManager::class;
    }

    public function getList($param)
    {
        $where = [];
        if( isset($param['real_name']) && $param['real_name'] != '') {
            $where[] = ['real_name', '=', $param['real_name']];
        }
        if( isset($param['phone']) && $param['phone'] != '') {
            $where[] = ['phone', '=', $param['phone']];
        }
        if( isset($param['id_card']) && $param['id_card'] != '') {
            $where[] = ['id_card', '=', $param['id_card']];
        }

        return $this->getModel()::where($where)
            ->paginate($param['size'])
            ->toArray();
    }
}
