<?php

namespace app\model;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;

class District extends BaseModel
{
    use ModelTrait;

    protected $pk = 'id';

    protected $name = 'district';

}
