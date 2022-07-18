<?php

namespace app\model;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;

class UserVaccination extends BaseModel
{
    use ModelTrait;
    /**
     * @var string
     */
    protected $pk = 'id';

    protected $name = 'user_vaccination';

    protected $autoWriteTimestamp = true;
    
}
