<?php
declare (strict_types=1);

namespace app\services\user;

use app\services\user\BaseServices;
use app\dao\BarrierDistrictDao;

class BarrierDistrictServices extends BaseServices
{
    public function __construct(BarrierDistrictDao $dao)
    {
        $this->dao = $dao;
    }

    public function getBarrierListService()
    {
        return $this->dao->getBarrierList();
    }

    public function readService($id)
    {
        $gov = $this->dao->get($id);
        if($gov) {
            return ['status' => 1, 'msg'=> '成功', 'data'=> $gov];
        }else{
            return ['status' => 0, 'msg'=> '不存在'];
        }
    }
    
}
