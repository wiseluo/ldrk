<?php

namespace app\model;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;

class AdminCompany extends BaseModel
{
    use ModelTrait;
    /**
     * @var string
     */
    protected $pk = 'id';

    protected $name = 'system_admin_company';

    protected $autoWriteTimestamp = true;
    
}
