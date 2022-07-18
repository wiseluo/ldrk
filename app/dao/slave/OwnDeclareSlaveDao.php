<?php

declare (strict_types=1);

namespace app\dao\slave;

use app\dao\OwnDeclareDao;
use app\model\slave\OwnDeclareSlave;

class OwnDeclareSlaveDao extends OwnDeclareDao
{
    
    protected function setModel(): string
    {
        return OwnDeclareSlave::class;
    }

}
