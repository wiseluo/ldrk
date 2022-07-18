<?php

declare (strict_types=1);

namespace app\dao;

use app\dao\BaseDao;
use app\model\GateDeclare;

class GateDeclareDao extends BaseDao
{
    
    protected function setModel(): string
    {
        return GateDeclare::class;
    }

    public function getList($param)
    {
        $whereclose = null;
        $where = $this->_param_where($param,$whereclose);
        
        return $this->getModel()::where($where)
                ->where($whereclose)
                ->append(['xcm_result_text'])
                ->order('id desc')
                ->paginate($param['size'])
                ->toArray();
    }

    private function _param_where($param,&$whereclose)
    {
        $where = [];
        //分段导出
        if(isset($param['_where_id_lg']) && $param['_where_id_lg'] > 0) {
            $where[] = ['id', '<', $param['_where_id_lg']];
        }
        if( isset($param['gate_code']) && $param['gate_code'] != '') {
            $where[] = ['gate_code', '=', $param['gate_code']];
        }
        if( isset($param['gate_name']) && $param['gate_name'] != '') {
            $where[] = ['gate_name', '=', $param['gate_name']];
        }
        if( isset($param['gate_short_name']) && $param['gate_short_name'] != '') {
            $where[] = ['gate_short_name', '=', $param['gate_short_name']];
        }
        if( isset($param['place_unit']) && $param['place_unit'] != '') {
            $where[] = ['place_unit', '=', $param['place_unit']];
        }
        if( isset($param['link_man']) && $param['link_man'] != '') {
            $where[] = ['link_man', '=', $param['link_man']];
        }
        if( isset($param['link_phone']) && $param['link_phone'] != '') {
            $where[] = ['link_phone', '=', $param['link_phone']];
        }
        if( isset($param['real_name']) && $param['real_name'] != '') {
            $where[] = ['real_name', '=', $param['real_name']];
        }
        if( isset($param['id_card']) && $param['id_card'] != '') {
            $where[] = ['id_card', '=', $param['id_card']];
        }
        if( isset($param['phone']) && $param['phone'] != '') {
            $where[] = ['phone', '=', $param['phone']];
        }
        if( isset($param['yw_street']) && $param['yw_street'] != '') {
            $where[] = ['yw_street', '=', $param['yw_street']];
        }
        if( isset($param['yw_street_id']) && $param['yw_street_id']  > 0) {
            $where[] = ['yw_street_id', '=', $param['yw_street_id']];
        }
        if( isset($param['jkm_mzt']) && $param['jkm_mzt'] != '') {
            $where[] = ['jkm_mzt', '=', $param['jkm_mzt']];
        }
        if( isset($param['hsjc_result']) && $param['hsjc_result'] != '') {
            $where[] = ['hsjc_result', '=', $param['hsjc_result']];
        }
        if( isset($param['xcm_result']) && $param['xcm_result'] != '') {
            $where[] = ['xcm_result', '=', $param['xcm_result']];
        }

        if( isset($param['start_datetime']) && $param['start_datetime'] != '' ) {
			$whereclose = function($query) use ($param){
                $start_date = Date('Y-m-d',strtotime($param['start_datetime']));
                $end_date = Date('Y-m-d',strtotime($param['end_datetime']));
                $query->whereTime('create_date','between',[$start_date, $end_date]);
				$query->whereTime('create_datetime','between',[$param['start_datetime'], $param['end_datetime']]);
			};
        }else{
            if( isset($param['start_date']) && $param['start_date']) {
                $whereclose = function($query) use ($param){
                    $query->whereTime('create_date','between',[$param['start_date'], $param['end_date']]);
                };
            }
        }
        if( isset($param['ryxx_result']) && $param['ryxx_result']  != '') {
            $where[] = ['ryxx_result', '=', $param['ryxx_result']];
        }
        // if( isset($param['end_date']) && $param['end_date']) {
        //     $where[] = ['create_time', '<=', strtotime($param['end_date'].' 23:59:59') ];
        // }
        return $where;
    }

}
