<?php

namespace app\model;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;

class Wxtoken extends BaseModel
{
    use ModelTrait;

    protected $pk = 'id';

    protected $name = 'wxtoken';

    protected $autoWriteTimestamp = true;


}
