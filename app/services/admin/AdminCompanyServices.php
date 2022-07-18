<?php

namespace app\services\admin;

use app\services\admin\BaseServices;
use app\dao\AdminCompanyDao;

class AdminCompanyServices extends BaseServices
{
    public function __construct(AdminCompanyDao $dao)
    {
        $this->dao = $dao;
    }

    public function getListService($param, $admin)
    {
        return $this->dao->getList($param, $admin);
    }

    public function followService($param, $admin)
    {
        $admin_company = $this->dao->get(['company_id' => $param['company_id'], 'admin_id' => $admin['id']]);
        if($admin_company) {
            return ['status' => 0, 'msg' => '您已关注了该企业'];
        }
        $data = [
            'company_id' => $param['company_id'],
            'admin_id' => $admin['id'],
        ];
        try {
            $this->dao->save($data);
            return ['status' => 1, 'msg' => '关注成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '关注失败-'. $e->getMessage()];
        }
    }

    public function unfollowService($param, $admin)
    {
        $admin_company = $this->dao->get(['company_id' => $param['company_id'], 'admin_id' => $admin['id']]);
        if($admin_company == null) {
            return ['status' => 0, 'msg' => '您未关注该企业'];
        }
        try {
            $this->dao->delete($admin_company['id']);
            return ['status' => 1, 'msg' => '取关成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '取关失败-'. $e->getMessage()];
        }
    }

}
