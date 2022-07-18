<?php

namespace app\model;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;

//企业员工类型
class CompanyStaffClassify extends BaseModel
{
    use ModelTrait;

    protected $pk = 'id';
    protected $name = 'company_staff_classify';
    protected $autoWriteTimestamp = true;

}
