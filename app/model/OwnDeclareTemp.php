<?php

namespace app\model;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;

/**
 * Class User
 * @package app\model\user
 */
class OwnDeclareTemp extends BaseModel
{
    use ModelTrait;
    
    protected $pk = 'id';

    protected $name = 'declare_temp';

    protected $autoWriteTimestamp = true;
    

}
