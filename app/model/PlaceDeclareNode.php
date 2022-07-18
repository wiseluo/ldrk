<?php

namespace app\model;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;

class PlaceDeclareNode extends BaseModel
{
    use ModelTrait;

    protected $pk = 'id';

    protected $name = 'place_declare_node';

    protected $autoWriteTimestamp = true;

}
