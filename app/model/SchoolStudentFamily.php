<?php

namespace app\model;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\model\concern\SoftDelete;

/**
 * Class User
 * @package app\model\user
 */
class SchoolStudentFamily extends BaseModel
{
    use ModelTrait;
    use SoftDelete;

    protected $pk = 'id';

    protected $name = 'school_student_family';

    protected $autoWriteTimestamp = true;
}
