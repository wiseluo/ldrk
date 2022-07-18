<?php

namespace app\dao;

use app\dao\BaseDao;
use app\model\AinatCompareTask;

class AinatCompareTaskDao extends BaseDao
{
    protected function setModel(): string
    {
        return AinatCompareTask::class;
    }

    public function getList($param, $admin)
    {
        $where = [];
        $where[] = ['admin_id', '=', $admin['id']];

        return $this->getModel()::where($where)
            ->order('id desc')
            ->paginate($param['size'])
            ->toArray();
    }
    
}
