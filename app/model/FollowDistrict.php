<?php

namespace app\model;

use crmeb\basic\BaseModel;
use think\model\concern\SoftDelete;
use crmeb\traits\ModelTrait;
use think\facade\Db;

class FollowDistrict extends BaseModel
{
    use ModelTrait;
    use SoftDelete;

    protected $pk = 'id';

    protected $name = 'follow_district';

    protected $autoWriteTimestamp = true;
    
    protected $deleteTime = 'delete_time';

}
