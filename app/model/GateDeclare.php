<?php

namespace app\model;

use crmeb\basic\BaseModel;
use think\model\concern\SoftDelete;
use crmeb\traits\ModelTrait;

class GateDeclare extends BaseModel
{
    use ModelTrait;
    use SoftDelete;

    protected $pk = 'id';

    protected $name = 'gate_declare';

    protected $autoWriteTimestamp = true;
    
    protected $deleteTime = 'delete_time';
}
