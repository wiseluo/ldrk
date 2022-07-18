<?php

namespace app\dao;

use app\dao\BaseDao;
use app\model\CompanyClassify;

class CompanyClassifyDao extends BaseDao
{
    /**
     * 设置模型
     * @return string
     */
    protected function setModel(): string
    {
        return CompanyClassify::class;
    }

    public function getList()
    {
        return $this->getModel()::order('sort', 'asc')
            ->select()
            ->toArray();
    }

    public function getListForApp()
    {
        $where = [];
        $where[] = ['id', '<>', 18];
        return $this->getModel()::where($where)
            ->order('sort', 'asc')
            ->select()
            ->toArray();
    }

}
