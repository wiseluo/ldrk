<?php

namespace app\model;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;

class PlaceDeclareLog extends BaseModel
{
    use ModelTrait;

    protected $pk = 'id';

    protected $name = 'place_declare_log';

    protected $autoWriteTimestamp = true;

}
