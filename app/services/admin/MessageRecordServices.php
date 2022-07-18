<?php

namespace app\services\admin;

use app\dao\MessageRecordDao;
use app\services\admin\BaseServices;

class MessageRecordServices extends BaseServices
{
    public function __construct(MessageRecordDao $dao)
    {
        $this->dao = $dao;
    }

    public function getRecordList($param, $admin_id)
    {
        return $this->dao->getMessageList($param, 'admin', $admin_id);
    }

    public function getService($id)
    {
        return $this->dao->get($id);
    }
    
    public function readService($id)
    {
        $data = $this->dao->get($id);
        if($data) {
            $data->save(['isread'=> 1]);
            return ['status' => 1, 'msg'=> '成功', 'data'=> $data];
        }else{
            return ['status' => 0, 'msg'=> '不存在'];
        }
    }

    public function getRecordQueryList($param)
    {
        return $this->dao->getMessageQueryList($param);
    }

}
