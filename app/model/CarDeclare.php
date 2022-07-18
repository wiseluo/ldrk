<?php

namespace app\model;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use app\model\MessageRecord;
use think\facade\Config;


class CarDeclare extends BaseModel
{
    use ModelTrait;
    
    protected $pk = 'id';

    protected $name = 'car_declare';

    protected $autoWriteTimestamp = true;
    


}
