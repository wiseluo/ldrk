<?php

declare (strict_types=1);

namespace app\dao\slave;

use app\dao\PlaceDao;
use app\model\slave\PlaceSlave;

class PlaceSlaveDao extends PlaceDao
{
    
    protected function setModel(): string
    {
        return PlaceSlave::class;
    }

}
