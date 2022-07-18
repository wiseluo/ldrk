<?php

namespace app\dao;

use app\dao\BaseDao;
use app\model\District;

class DistrictDao extends BaseDao
{
    /**
     * 设置模型
     * @return string
     */
    protected function setModel(): string
    {
        return District::class;
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

    public function getNameById($id)
    {
        return $this->getModel()->where('id', $id)->value('name');
    }

    public function getCodeById($id)
    {
        return $this->getModel()->where('id', $id)->value('code');
    }

    public function getListByPid($pid)
    {
        return $this->getModel()->field('id,name')->where('pid', $pid)->select()->toArray();
    }
}
