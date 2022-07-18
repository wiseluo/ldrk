<?php

namespace app\model;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;

class UserManager extends BaseModel
{
    use ModelTrait;
    /**
     * @var string
     */
    protected $pk = 'id';

    protected $name = 'user_manager';

    protected $autoWriteTimestamp = true;
    
}
