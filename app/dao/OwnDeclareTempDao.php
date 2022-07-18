<?php

declare (strict_types=1);

namespace app\dao;

use app\dao\BaseDao;
use app\model\OwnDeclareTemp;

class OwnDeclareTempDao extends BaseDao
{
    
    protected function setModel(): string
    {
        return OwnDeclareTemp::class;
    }

    public function getTempList($param)
    {
        $where = [];
        $where[] = ['uuid','=',$param['uuid']];
        
        return $this->getModel()::where($where)
                ->select()
                ->toArray();
    }
    
    public function updateLimit($data, $size)
    {
        return $this->getModel()::where('id','>',0)->limit($size)->update($data);
    }

}
