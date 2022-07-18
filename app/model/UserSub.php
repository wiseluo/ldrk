<?php

namespace app\model;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;

class UserSub extends BaseModel
{
    use ModelTrait;
    /**
     * @var string
     */
    protected $pk = 'id';

    protected $name = 'user_sub';

    protected $autoWriteTimestamp = true;
    
}
