<?php

namespace app\dao;

use app\dao\BaseDao;
use app\model\BarrierDistrict;

class BarrierDistrictDao extends BaseDao
{
    /**
     * 设置模型
     * @return string
     */
    protected function setModel(): string
    {
        return BarrierDistrict::class;
    }

    public function getList($param)
    {
        $where = [];
        if( isset($param['province_id']) && $param['province_id'] > 0) {
            $where[] = ['province_id', '=', $param['province_id']];
        }
        if( isset($param['city_id']) && $param['city_id'] > 0) {
            $where[] = ['city_id', '=', $param['city_id']];
        }
        if( isset($param['county_id']) && $param['county_id'] > 0) {
            $where[] = ['county_id', '=', $param['county_id']];
        }
        if( isset($param['street_id']) && $param['street_id'] > 0) {
            $where[] = ['street_id', '=', $param['street_id']];
        }
        
        if( isset($param['time']) && $param['time'] != '') {
            $where[] = function ($query) use($param)  {
                $query->whereTime('start_date','<=',$param['time']);
            };
            $where[] = function ($query) use($param)  {
                $query->whereTime('end_date','>',$param['time']);
            };
        }

        return $this->getModel()
            ->where($where)
            ->paginate($param['size'])
            ->toArray();
    }

    public function getBarrierList()
    {
        return $this->getModel()
            ->field('id,district')
            ->select()
            ->toArray();
    }
}
