<?php

declare (strict_types=1);

namespace app\dao;

use app\dao\BaseDao;
use app\model\PlaceDeclareNode;

class PlaceDeclareNodeDao extends BaseDao
{
    
    protected function setModel(): string
    {
        return PlaceDeclareNode::class;
    }

    public function getList()
    {
        return $this->getModel()::field('id,describe')
            ->order('id desc')
            ->limit(9)
            ->select()
            ->toArray();
    }

}
