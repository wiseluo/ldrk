<?php

namespace app\dao;

use app\dao\BaseDao;
use app\model\PlaceType;

class PlaceTypeDao extends BaseDao
{
    /**
     * 设置模型
     * @return string
     */
    protected function setModel(): string
    {
        return PlaceType::class;
    }

    public function getList($param)
    {
        $where = [];
        if(isset($param['place_type']) && $param['place_type'] > 0) {
            $where[] = ['place_type', 'like', '%'. $param['place_type'] .'%'];
        }

        return $this->getModel()
            ->where($where)
            ->order('sort', 'asc')
            ->select()
            ->toArray();
    }

}
