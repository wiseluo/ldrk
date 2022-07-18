<?php

namespace app\dao;

use app\dao\BaseDao;
use app\model\CompanyStaffClassify;
use think\facade\Db;

class CompanyStaffClassifyDao extends BaseDao
{
    /**
     * 设置模型
     * @return string
     */
    protected function setModel(): string
    {
        return CompanyStaffClassify::class;
    }

    public function getList($param)
    {
        $where = [];
        if(isset($param['classify_id']) && $param['classify_id'] != '') {
            $where[] = ['classify_id', '=', $param['classify_id']];
        }
        if(isset($param['classify_name']) && $param['classify_name'] != '') {
            $where[] = ['classify_name', 'like', '%'. $param['classify_name'] .'%'];
        }
        if(isset($param['gov_name']) && $param['gov_name'] != '') {
            $where[] = ['gov_name', 'like', '%'. $param['gov_name'] .'%'];
        }
        if(isset($param['industry_code']) && $param['industry_code'] != '') {
            $where[] = ['industry_code', 'like', '%'. $param['industry_code'] .'%'];
        }
        if(isset($param['jinhua_full_name']) && $param['jinhua_full_name'] != '') {
            $where[] = ['jinhua_full_name', 'like', '%'. $param['jinhua_full_name'] .'%'];
        }
        if(isset($param['jinhua_short_name']) && $param['jinhua_short_name'] != '') {
            $where[] = ['jinhua_short_name', 'like', '%'. $param['jinhua_short_name'] .'%'];
        }
        if(isset($param['yiwu_name']) && $param['yiwu_name'] != '') {
            $where[] = ['yiwu_name', 'like', '%'. $param['yiwu_name'] .'%'];
        }
        if(isset($param['check_frequency']) && $param['check_frequency'] != '') {
            $where[] = ['check_frequency', 'like', '%'. $param['check_frequency'] .'%'];
        }

        return $this->getModel()::where($where)
            ->order('sort', 'asc')
            ->paginate($param['size'])
            ->toArray();
    }

    public function getListSelect($param)
    {
        $where = [];
        if(isset($param['classify_id']) && $param['classify_id'] != '') {
            $where[] = ['classify_id', '=', $param['classify_id']];
        }

        return $this->getModel()::where($where)
            ->order('sort', 'asc')
            ->select()
            ->toArray();
    }
}
