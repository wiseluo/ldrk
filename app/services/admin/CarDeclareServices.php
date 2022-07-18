<?php
declare (strict_types=1);

namespace app\services\admin;

use app\services\user\BaseServices;
use app\dao\CarDeclareDao;
use think\facade\Db;

class CarDeclareServices extends BaseServices
{
    public function __construct(CarDeclareDao $dao)
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
    
    public function deleteService($id)
    {
        $own_declare = $this->dao->get($id);
        if($own_declare == null) {
            return ['status' => 0, 'msg' => '申报不存在'];
        }
        try {
            $this->dao->softDelete($id);
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }
    
}
