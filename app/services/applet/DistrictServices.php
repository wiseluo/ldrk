<?php

namespace app\services\applet;

use app\services\user\BaseServices;
use app\dao\DistrictDao;

class DistrictServices extends BaseServices
{
    public function __construct(DistrictDao $dao)
    {
        $this->dao = $dao;
    }

    public function getList($param)
    {
        return $this->dao->getList($param);
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
