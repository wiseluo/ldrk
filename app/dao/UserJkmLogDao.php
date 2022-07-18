<?php

namespace app\dao;

use app\dao\BaseDao;
use app\model\UserJkmLog;

class UserJkmLogDao extends BaseDao
{
    /**
     * 设置模型
     * @return string
     */
    protected function setModel(): string
    {
        return UserJkmLog::class;
    }

}
