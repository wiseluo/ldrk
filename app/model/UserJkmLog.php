<?php

namespace app\model;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;

class UserJkmLog extends BaseModel
{
    use ModelTrait;

    protected $pk = 'id';

    protected $name = 'user_jkm_log';

}
