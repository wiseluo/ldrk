<?php

namespace app\dao;

use app\dao\BaseDao;
use app\model\Area;

class AreaDao extends BaseDao
{
    /**
     * 设置模型
     * @return string
     */
    protected function setModel(): string
    {
        return Area::class;
    }

    public function getList($param)
    {
        $where = [];
        $where[] = ['pid', '=', $param['pid']];
        
        return $this->getModel()
            ->where($where)
            ->field('id,name,level')
            ->select()
            ->toArray();
    }
}
