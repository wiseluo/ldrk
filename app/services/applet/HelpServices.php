<?php

namespace app\services\applet;

use app\services\applet\BaseServices;
use app\dao\HelpDao;

class HelpServices extends BaseServices
{
    public function __construct(HelpDao $dao)
    {
        $this->dao = $dao;
    }

    public function getList($param)
    {
        return $this->dao->getList($param);
    }

    public function readService($id)
    {
        $help = $this->dao->get($id);
        if($help) {
            return ['status' => 1, 'msg'=> '成功', 'data'=> $help];
        }else{
            return ['status' => 0, 'msg'=> '不存在'];
        }
    }
    
}
