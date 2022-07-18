<?php

namespace app\model;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\model\concern\SoftDelete;

class Community extends BaseModel
{
    use ModelTrait;
    use SoftDelete;

    protected $pk = 'id';

    protected $name = 'community';

    protected $autoWriteTimestamp = true;
    
    protected $deleteTime = 'delete_time';
    
}
