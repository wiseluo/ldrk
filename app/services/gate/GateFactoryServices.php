<?php
declare (strict_types=1);

namespace app\services\gate;

use app\services\gate\BaseServices;
use app\dao\GateFactoryDao;

class GateFactoryServices extends BaseServices
{
    public function __construct(GateFactoryDao $dao)
    {
        $this->dao = $dao;
    }
}
