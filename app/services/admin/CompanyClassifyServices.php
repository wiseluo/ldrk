<?php

namespace app\services\admin;

use app\services\user\BaseServices;
use app\dao\CompanyDao;
use app\dao\CompanyClassifyDao;

class CompanyClassifyServices extends BaseServices
{
    public function __construct(CompanyClassifyDao $dao)
    {
        $this->dao = $dao;
    }

    public function getList()
    {
        return $this->dao->getList();
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
    
    public function saveService($param)
    {
        $data = [
            'classify_name' => $param['classify_name'],
            'sort' => $param['sort'],
        ];
        try {
            $this->dao->save($data);
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }
    
    public function updateService($param, $id)
    {
        $company_classify = $this->dao->get($id);
        if($company_classify == null) {
            return ['status' => 0, 'msg' => '员工类型不存在'];
        }
        $data = [
            'classify_name' => $param['classify_name'],
            'sort' => $param['sort'],
        ];
        try {
            $this->dao->update($id, $data);
            if($company_classify['classify_name'] != $param['classify_name']) {
                app()->make(CompanyDao::class)->update(['classify_id'=> $id], ['classify_name'=> $param['classify_name']]);
            }
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }
}
