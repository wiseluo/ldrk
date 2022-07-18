<?php

declare (strict_types=1);

namespace app\dao;

use app\dao\BaseDao;
use app\model\UserVaccination;

class UserVaccinationDao extends BaseDao
{
    
    protected function setModel(): string
    {
        return UserVaccination::class;
    }

}
