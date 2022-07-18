<?php

namespace app\model;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;

//企业员工
class CompanyStaff extends BaseModel
{
    use ModelTrait;

    protected $pk = 'id';
    protected $name = 'company_staff';
    protected $autoWriteTimestamp = true;

}
