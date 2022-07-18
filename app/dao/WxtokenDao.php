<?php

namespace app\dao;

use app\dao\BaseDao;
use app\model\Wxtoken;

class WxtokenDao extends BaseDao
{
    /**
     * 设置模型
     * @return string
     */
    protected function setModel(): string
    {
        return Wxtoken::class;
    }

}
