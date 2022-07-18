<?php

namespace app\model;

use crmeb\basic\BaseModel;
use think\model\concern\SoftDelete;
use crmeb\traits\ModelTrait;
use app\dao\CompanyClassifyDao;

//学校班级
class SchoolClass extends BaseModel
{
    use ModelTrait;
    use SoftDelete;

    protected $pk = 'id';
    protected $name = 'school_class';
    protected $autoWriteTimestamp = true;
    protected $deleteTime = 'delete_time';
}
