<?php

declare (strict_types=1);

namespace app\dao;

use app\dao\BaseDao;
use app\model\GateFactory;

class GateFactoryDao extends BaseDao
{
    
    protected function setModel(): string
    {
        return GateFactory::class;
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
        if( isset($param['name']) && $param['name'] != '') {
            $where[] = ['name', 'like', '%'. $param['name'] .'%'];
        }
        if( isset($param['link_man']) && $param['link_man'] != '') {
            $where[] = ['link_man', 'like', '%'. $param['link_man'] .'%'];
        }
        if( isset($param['link_phone']) && $param['link_phone'] != '') {
            $where[] = ['link_phone', '=', $param['link_phone']];
        }
        return $where;
    }

}
