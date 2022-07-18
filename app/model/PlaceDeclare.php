<?php

namespace app\model;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;

class PlaceDeclare extends BaseModel
{
    use ModelTrait;

    protected $pk = 'id';

    protected $name = 'place_declare';

    protected $autoWriteTimestamp = true;

    public function getXcmResultList()
    {
        return ['0'=> '未查询', '1'=> '代表没有去过高风险地区', '2'=> '代表去过高风险地区', '3'=> '代表用户没有行程记录','9'=>'代表去过高风险地区'];
    }

    public function getXcmResultTextAttr($value, $data)
    {
        $arr = $this->getXcmResultList();
        return $arr[$data['xcm_result']];
    }
}
