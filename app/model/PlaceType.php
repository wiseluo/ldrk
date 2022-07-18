<?php

namespace app\model;

use crmeb\basic\BaseModel;
use think\model\concern\SoftDelete;
use crmeb\traits\ModelTrait;

class PlaceType extends BaseModel
{
    use ModelTrait;
    use SoftDelete;

    protected $pk = 'id';

    protected $name = 'place_type';

    protected $autoWriteTimestamp = true;
    
    protected $deleteTime = 'delete_time';

}
