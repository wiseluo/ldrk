<?php

namespace app\model\slave;

use app\model\OwnDeclare;

class OwnDeclareSlave extends OwnDeclare
{
    // 设置当前模型的数据库连接
    protected $connection = 'mysql_slave'; // 从库的

}
