<?php

namespace app\model\slave;

use app\model\CompanyStaff;

//企业员工
class CompanyStaffSlave extends CompanyStaff
{
    // 设置当前模型的数据库连接
    protected $connection = 'mysql_slave'; // 从库的


}
