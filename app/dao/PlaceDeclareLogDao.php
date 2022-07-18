<?php

declare (strict_types=1);

namespace app\dao;

use app\dao\BaseDao;
use app\model\PlaceDeclareLog;
use think\facade\Db;

class PlaceDeclareLogDao extends BaseDao
{
    
    protected function setModel(): string
    {
        return PlaceDeclareLog::class;
    }

    public function getList($param)
    {
        $where = $this->_param_where($param);
        
        return $this->getModel()::where($where)
                ->order('id desc')
                ->paginate($param['size'])
                ->toArray();
    }

    private function _param_where($param)
    {
        $where = [];
        //分段导出
        if(isset($param['_where_id_lg']) && $param['_where_id_lg'] > 0) {
            $where[] = ['id', '<', $param['_where_id_lg']];
        }
        if( isset($param['place_code']) && $param['place_code'] != '') {
            $where[] = ['place_code', '=', $param['place_code']];
        }
        if( isset($param['place_name']) && $param['place_name'] != '') {
            $where[] = ['place_name', '=', $param['place_name']];
        }
        if( isset($param['place_short_name']) && $param['place_short_name'] != '') {
            $where[] = ['place_short_name', '=', $param['place_short_name']];
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

        return $where;
    }


}
