<?php

namespace app\dao\slave;

use app\dao\CompanyDao;
use app\model\slave\CompanySlave;

class CompanySlaveDao extends CompanyDao
{
    /**
     * 设置模型
     * @return string
     */
    protected function setModel(): string
    {
        return CompanySlave::class;
    }

}
