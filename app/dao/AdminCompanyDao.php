<?php

declare (strict_types=1);

namespace app\dao;

use app\dao\BaseDao;
use app\model\AdminCompany;

class AdminCompanyDao extends BaseDao
{
    
    protected function setModel(): string
    {
        return AdminCompany::class;
    }

    public function getList($param, $admin)
    {
        $where = [];
        if( isset($param['name']) && $param['name'] != '') {
            $where[] = ['c.name', '=', $param['name']];
        }
        if( isset($param['credit_code']) && $param['credit_code'] != '') {
            $where[] = ['c.credit_code', '=', $param['credit_code']];
        }
        if( isset($param['link_name']) && $param['link_name'] != '') {
            $where[] = ['c.link_name', '=', $param['link_name']];
        }
        if( isset($param['link_phone']) && $param['link_phone'] != '') {
            $where[] = ['c.link_phone', '=', $param['link_phone']];
        }
        if(isset($param['yw_street_id']) && $param['yw_street_id'] > 0) {
            $where[] = ['c.yw_street_id', '=', $param['yw_street_id']];
        }
        if(isset($param['community_id']) && $param['community_id'] != '') {
            $where[] = ['c.community_id', '=', $param['community_id']];
        }
        if(isset($param['link_idcard']) && $param['link_idcard'] != '') {
            $where[] = ['c.link_idcard', '=', $param['link_idcard']];
        }
        if(isset($admin['level']) && $admin['level'] != 0 && $admin['level'] != 1) { //0,1为所有数据
            $where[] = ['ac.admin_id', '=', $admin['id']];
        }

        return $this->getModel()::alias('ac')
            ->leftJoin('company c', 'c.id=ac.company_id')
            ->leftJoin('company_classify cc', 'cc.id=c.classify_id')
            ->field('c.*,cc.classify_name')
            ->where($where)
            ->paginate($param['size'])
            ->toArray();
    }
}
