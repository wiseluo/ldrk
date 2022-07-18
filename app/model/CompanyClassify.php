<?php

namespace app\model;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;

//企业类型
class CompanyClassify extends BaseModel
{
    use ModelTrait;

    protected $pk = 'id';
    protected $name = 'company_classify';
    protected $autoWriteTimestamp = true;

}
