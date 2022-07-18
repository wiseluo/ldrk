<?php

namespace app\dao;

use app\dao\BaseDao;
use app\model\Community;

class CommunityDao extends BaseDao
{
    /**
     * 设置模型
     * @return string
     */
    protected function setModel(): string
    {
        return Community::class;
    }

    public function getListByYwStreetId($yw_street_id)
    {
        $where = [];
        $where[] = ['yw_street_id', '=', $yw_street_id];
        
        return $this->getModel()
            ->where($where)
            ->field('id,name')
            ->select()
            ->toArray();
    }

    public function getNameById($id)
    {
        return $this->getModel()->where('id', $id)->value('name');
    }

}
