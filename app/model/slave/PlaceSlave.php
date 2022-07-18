<?php

namespace app\model\slave;

use app\model\Place;

class PlaceSlave extends Place
{
    // 设置当前模型的数据库连接
    protected $connection = 'mysql_slave'; // 从库的
    
}
