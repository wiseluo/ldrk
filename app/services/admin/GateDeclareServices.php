<?php
declare (strict_types=1);

namespace app\services\admin;

use app\services\user\BaseServices;
use app\dao\GateDeclareDao;
use app\dao\GateFactoryDao;
use think\facade\Db;

class GateDeclareServices extends BaseServices
{
    public function __construct(GateDeclareDao $dao)
    {
        $this->dao = $dao;
    }

    public function getList($param)
    {
        return $this->dao->getList($param);
    }

}
