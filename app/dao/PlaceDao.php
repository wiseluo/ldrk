<?php

namespace app\dao;

use app\dao\BaseDao;
use app\model\Place;

class PlaceDao extends BaseDao
{
    /**
     * 设置模型
     * @return string
     */
    protected function setModel(): string
    {
        return Place::class;
    }

    public function getList($param)
    {
        $where = [];
        //分段导出
        if(isset($param['_where_id_lg']) && $param['_where_id_lg'] > 0) {
            $where[] = ['id', '<', $param['_where_id_lg']];
        }
        if(isset($param['yw_street_id']) && $param['yw_street_id'] > 0) {
            $where[] = ['yw_street_id', '=', $param['yw_street_id']];
        }
        if(isset($param['code']) && $param['code'] != '') {
            $where[] = ['code', '=', $param['code']];
        }
        if(isset($param['name']) && $param['name'] != '') {
            $where[] = ['name', 'like', '%'. $param['name'] .'%'];
        }
        if(isset($param['short_name']) && $param['short_name'] != '') {
            $where[] = ['short_name', 'like', '%'. $param['short_name'] .'%'];
        }
        if(isset($param['credit_code']) && $param['credit_code'] != '') {
            $where[] = ['credit_code', '=', $param['credit_code']];
        }
        if(isset($param['place_type_id']) && $param['place_type_id'] != '') {
            $where[] = ['place_type_id', '=', $param['place_type_id']];
        }
        if(isset($param['link_man']) && $param['link_man'] != '') {
            $where[] = ['link_man', '=', $param['link_man']];
        }
        if(isset($param['link_phone']) && $param['link_phone'] != '') {
            $where[] = ['link_phone', '=', $param['link_phone']];
        }
        if(isset($param['superior_gov']) && $param['superior_gov'] != '') {
            $where[] = ['superior_gov', '=', $param['superior_gov']];
        }
        if(isset($param['community']) && $param['community'] != '') {
            $where[] = ['community', '=', $param['community']];
        }
        if(isset($param['xcm_level']) && $param['xcm_level'] > 0) {
            $where[] = ['xcm_level', '=', $param['xcm_level']];
        }
        return $this->getModel()
            ->append(['place_classify_text'])
            ->where($where)
            ->order('id', 'desc')
            ->paginate($param['size'])
            ->toArray();
    }

    public function getDeleteList($param)
    {
        $where = [];
        if(isset($param['yw_street_id']) && $param['yw_street_id'] > 0) {
            $where[] = ['yw_street_id', '=', $param['yw_street_id']];
        }
        if(isset($param['name']) && $param['name'] != '') {
            $where[] = ['name', 'like', '%'. $param['name'] .'%'];
        }
        if(isset($param['short_name']) && $param['short_name'] != '') {
            $where[] = ['short_name', 'like', '%'. $param['short_name'] .'%'];
        }
        if(isset($param['credit_code']) && $param['credit_code'] != '') {
            $where[] = ['credit_code', '=', $param['credit_code']];
        }
        if(isset($param['place_type_id']) && $param['place_type_id'] != '') {
            $where[] = ['place_type_id', '=', $param['place_type_id']];
        }
        if(isset($param['link_man']) && $param['link_man'] != '') {
            $where[] = ['link_man', '=', $param['link_man']];
        }
        if(isset($param['link_phone']) && $param['link_phone'] != '') {
            $where[] = ['link_phone', '=', $param['link_phone']];
        }
        if(isset($param['superior_gov']) && $param['superior_gov'] != '') {
            $where[] = ['superior_gov', '=', $param['superior_gov']];
        }
        if(isset($param['community']) && $param['community'] != '') {
            $where[] = ['community', '=', $param['community']];
        }
        
        return $this->getModel()::onlyTrashed()
            ->where($where)
            ->order('id', 'desc')
            ->paginate($param['size'])
            ->toArray();
    }

    public function getNeedInit($limit=1)
    {
        $where[] = ['code', '=', ''];
        return $this->getModel()
            ->where($where)
            ->limit($limit)
            ->select()
            ->toArray();
    }

}
