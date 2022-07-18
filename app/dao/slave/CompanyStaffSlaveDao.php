<?php

namespace app\dao\slave;

use app\dao\CompanyStaffDao;
use app\model\slave\CompanyStaffSlave;

class CompanyStaffSlaveDao extends CompanyStaffDao
{
    /**
     * 设置模型
     * @return string
     */
    protected function setModel(): string
    {
        return CompanyStaffSlave::class;
    }

}
