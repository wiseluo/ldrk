<?php

namespace app\model;

use crmeb\basic\BaseModel;
use think\model\concern\SoftDelete;
use crmeb\traits\ModelTrait;

class Gate extends BaseModel
{
    use ModelTrait;
    use SoftDelete;

    protected $pk = 'id';

    protected $name = 'gate';

    protected $autoWriteTimestamp = true;
    
    protected $deleteTime = 'delete_time';
}
