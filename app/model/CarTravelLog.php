<?php

namespace app\model;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use app\model\MessageRecord;
use think\facade\Config;


class CarTravelLog extends BaseModel
{
    use ModelTrait;
    
    protected $pk = 'id';

    protected $name = 'car_travel_log';

    protected $autoWriteTimestamp = true;
    


}
