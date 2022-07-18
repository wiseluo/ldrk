<?php

namespace app\dao;

use app\dao\BaseDao;
use app\model\UserHsjcLog;

class UserHsjcLogDao extends BaseDao
{
    /**
     * 设置模型
     * @return string
     */
    protected function setModel(): string
    {
        return UserHsjcLog::class;
    }

    public function getSixRecentList($id_card)
    {
        
        // return $this->getModel()
        //     ->where('id_card', $id_card)
        //     ->order('hsjc_time', 'desc')
        //     ->limit(6)
        //     ->select()
        //     ->toArray();
        
        return $this->getModel()
            ->where('id_card', $id_card)
            ->order('hsjc_time', 'desc')
            ->limit(6)
            ->group('hsjc_time') // 暂时
            ->select()
            ->toArray();



    }
}
