<?php

namespace app\controller\gate;

use think\facade\App;
use crmeb\basic\BaseController;
use app\validate\gate\GateDeclareValidate;
use app\services\gate\GateFactoryServices;

class GateFactory extends BaseController
{
    public function __construct(App $app, GateFactoryServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }
}
