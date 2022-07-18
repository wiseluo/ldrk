<?php

namespace app\model;

use crmeb\basic\BaseModel;
use think\model\concern\SoftDelete;
use crmeb\traits\ModelTrait;

//老师
class SchoolTeacher extends BaseModel
{
    use ModelTrait;
    use SoftDelete;

    protected $pk = 'id';
    protected $name = 'school_teacher';
    protected $autoWriteTimestamp = true;
    protected $deleteTime = 'delete_time';



}
