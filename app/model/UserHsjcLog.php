<?php

namespace app\model;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;

class UserHsjcLog extends BaseModel
{
    use ModelTrait;

    protected $pk = 'id';

    protected $name = 'user_hsjc_log';

    protected $autoWriteTimestamp = true;
    
}
