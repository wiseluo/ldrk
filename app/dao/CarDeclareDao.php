<?php

declare (strict_types=1);

namespace app\dao;

use app\dao\BaseDao;
use app\model\CarDeclare;
use think\facade\Db;

class CarDeclareDao extends BaseDao
{
    
    protected function setModel(): string
    {
        return CarDeclare::class;
    }

    public function getList($param)
    {
        $where = [];
        if( isset($param['keyword']) && $param['keyword'] != '') {
            $where[] = ['real_name|phone|id_card', 'LIKE', '%'. $param['keyword'] .'%'];
        }
        $this->_param_where($param,$where);
        
        $param['size'] = isset($param['size']) ? $param['size'] : 20;
        return $this->getModel()::where($where)
                ->order('id desc')
                ->paginate($param['size'])
                ->toArray();
    }

    private function _param_where($param,&$where){
        if( isset($param['real_name']) && $param['real_name'] != '') {
            $where[] = ['real_name', 'LIKE', '%'. $param['real_name'] .'%'];
        }
        if( isset($param['id_card']) && $param['id_card'] != '') {
            $where[] = ['id_card', 'LIKE', '%'. $param['id_card'] .'%'];
        }
        if( isset($param['phone']) && $param['phone'] != '') {
            $where[] = ['phone', 'LIKE', '%'. $param['phone'] .'%'];
        }
        if( isset($param['travel_route']) && $param['travel_route'] != '') {
            $where[] = ['travel_route', 'LIKE', '%'. $param['travel_route'] .'%'];
        }
    }
 
}
