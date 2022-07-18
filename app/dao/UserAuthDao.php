<?php
 
declare (strict_types = 1);

namespace app\dao;

use app\dao\BaseDao;
use app\model\User;

/**
 *
 * Class UserAuthDao
 * @package app\dao\user
 */
class UserAuthDao extends BaseDao
{

    /**
     * 设置模型
     * @return string
     */
    protected function setModel(): string
    {
        return User::class;
    }

}
