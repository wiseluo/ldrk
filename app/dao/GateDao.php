<?php

namespace app\dao;

use app\dao\BaseDao;
use app\model\Gate;

class GateDao extends BaseDao
{
    /**
     * 设置模型
     * @return string
     */
    protected function setModel(): string
    {
        return Gate::class;
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
        if(isset($param['gate_factory_name']) && $param['gate_factory_name'] != '') {
            $where[] = ['gate_factory_name', '=', $param['gate_factory_name']];
        }
        if(isset($param['start_date']) && $param['start_date']) {
            $where[] = ['create_time', '>=', strtotime($param['start_date'] .'00:00:00')];
        }
        if(isset($param['end_date']) && $param['end_date']) {
            $where[] = ['create_time', '<=', strtotime($param['end_date'] .'23:59:59')];
        }

        return $this->getModel()
            ->where($where)
            ->order('id', 'desc')
            ->paginate($param['size'])
            ->toArray();
    }

    public function getNeedInit($limit=1){

        $where[] = ['code','=',''];

        return $this->getModel()
            ->where($where)
            ->limit($limit)
            ->select()
            ->toArray();

    }

}
