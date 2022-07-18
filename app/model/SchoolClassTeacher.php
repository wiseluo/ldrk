<?php

namespace app\model;

use crmeb\basic\BaseModel;
use think\model\concern\SoftDelete;
use crmeb\traits\ModelTrait;

// 班级老师关系表
class SchoolClassTeacher extends BaseModel
{
    use ModelTrait;
    use SoftDelete;

    protected $pk = 'id';
    protected $name = 'school_class_teacher_relationship';
    protected $autoWriteTimestamp = true;
    protected $deleteTime = 'delete_time';


}
