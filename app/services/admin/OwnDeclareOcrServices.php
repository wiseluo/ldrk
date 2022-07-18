<?php
declare (strict_types=1);

namespace app\services\admin;

use app\services\user\BaseServices;
use app\dao\OwnDeclareOcrDao;

class OwnDeclareOcrServices extends BaseServices
{
    public function __construct(OwnDeclareOcrDao $dao)
    {
        $this->dao = $dao;
    }

    public function getList($param)
    {
        return $this->dao->getList($param);
    }

    public function readService($id)
    {
        $data = $this->dao->get($id);
        if($data) {
            return ['status' => 1, 'msg'=> '成功', 'data'=> $data];
        }else{
            return ['status' => 0, 'msg'=> '不存在'];
        }
    }
    

}
