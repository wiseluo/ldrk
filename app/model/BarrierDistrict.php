<?php

namespace app\model;

use crmeb\basic\BaseModel;
use think\model\concern\SoftDelete;
use crmeb\traits\ModelTrait;

class BarrierDistrict extends BaseModel
{
    use ModelTrait;
    use SoftDelete;

    protected $pk = 'id';

    protected $name = 'barrier_district';

    protected $autoWriteTimestamp = true;
    
    protected $deleteTime = 'delete_time';


}
