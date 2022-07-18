<?php
declare (strict_types=1);

namespace app\services\admin;

use app\services\user\BaseServices;
use app\dao\system\config\SystemConfigDao;
use think\facade\Cache;

class SystemConfigServices extends BaseServices
{
    public function __construct(SystemConfigDao $dao)
    {
        $this->dao = $dao;
    }

    public function getListService($param)
    {
        return $this->dao->getList($param);
    }

    public function readService($id)
    {
        $config = $this->dao->get($id);
        if($config) {
            return ['status' => 1, 'msg'=> '成功', 'data'=> $config];
        }else{
            return ['status' => 0, 'msg'=> '不存在'];
        }
    }
    
    public function updateService($param, $id)
    {
        $config = $this->dao->get($id);
        if($config == false) {
            return ['status' => 0, 'msg' => '该配置不存在'];
        }
        
        $data = [
            'menu_name' => $param['menu_name'],
            'value' => $param['value'],
            'desc' => $param['desc'],
        ];
        try {
            $this->dao->update($id, $data);
            Cache::clear();
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }
    

}
