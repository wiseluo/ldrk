<?php

namespace app\dao;

use app\dao\BaseDao;
use app\model\Help;

class HelpDao extends BaseDao
{
    /**
     * 设置模型
     * @return string
     */
    protected function setModel(): string
    {
        return Help::class;
    }

    public function getList($param)
    {
        $where = [];

        if(isset($param['title']) && $param['title'] != '') {
            $where[] = ['title', 'like', '%'. $param['title'] .'%'];
        }

        $param['size'] = isset($param['size']) ? $param['size'] : 20;

        return $this->getModel()
            ->where($where)
            ->order('id', 'desc')
            ->paginate($param['size'])
            ->toArray();
    }
}
