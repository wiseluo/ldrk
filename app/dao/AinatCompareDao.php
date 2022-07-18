<?php

namespace app\dao;

use app\dao\BaseDao;
use app\model\AinatCompare;

class AinatCompareDao extends BaseDao
{
    protected function setModel(): string
    {
        return AinatCompare::class;
    }

    public function getList($param, $export=0)
    {
        $where = [];
        $where[] = ['sign', '=', $param['sign']];
        if(isset($param['order']) && $param['order'] != '') {
            $order = $param['order'] .' '. $param['sort'];
        }else{
            $order = 'id asc';
        }

        $query = $this->getModel()::where($where)
            ->order($order);
        if($export == 1) {
            $list = $query->select();
        }else{
            $list = $query->paginate($param['size']);
        }
        return $list->toArray();
    }
    
    //未对比的部分列表
    public function getComparePartList($param)
    {
        $where = [];
        $where[] = ['sign', '=', $param['sign']];
        //$where[] = ['result', '=', 0];

        return $this->getModel()::where($where)
            ->field('id,user_idcard,natest_hours')
            ->page($param['page'], $param['size'])
            ->select()
            ->toArray();
    }

    public function getCompareProgress($param)
    {
        $where = [];
        $where[] = ['sign', '=', $param['sign']];
        return $this->getModel()::where($where)
            ->field('sum(if(result=0,0,1)) cur,count(id) max')
            ->select()
            ->toArray();
    }

    public function getCompareTotalBySign($sign)
    {
        return $this->getModel()::where('sign', $sign)->count('id');
    }
}
