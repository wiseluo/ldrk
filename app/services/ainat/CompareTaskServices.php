<?php

namespace app\services\ainat;

use app\dao\AinatCompareDao;
use app\dao\AinatCompareTaskDao;
use app\services\ainat\BaseServices;

class CompareTaskServices extends BaseServices
{
    public function __construct(AinatCompareTaskDao $dao)
    {
        $this->dao = $dao;
    }

    public function indexService($param, $admin)
    {
        return $this->dao->getList($param, $admin);
    }

    public function readService($id)
    {
        $task = $this->dao->get($id);
        if($task == null) {
            return ['status' => 0, 'msg'=> '历史任务不存在'];
        }
        $param = [];
        $param['sign'] = $task['sign'];
        $param['size'] = 100;
        $compare = app()->make(AinatCompareDao::class)->getList($param);
        return ['status' => 1, 'msg'=> '成功', 'data'=> $compare];
    }
    
    public function saveService($param, $admin)
    {
        $data = [
            'sign' => $param['sign'],
            'name' => $param['name'],
            'admin_id' => $admin['id'],
            'admin_name' => $admin['real_name'],
        ];
        try {
            app()->make(AinatCompareTaskDao::class)->save($data);
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }
    public function deleteService($id)
    {
        $task = $this->dao->get($id);
        if($task == null) {
            return ['status' => 0, 'msg' => '任务不存在'];
        }
        
        try {
            $this->dao->delete($id);
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }

}
