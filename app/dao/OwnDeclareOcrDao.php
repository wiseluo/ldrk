<?php

declare (strict_types=1);

namespace app\dao;

use app\dao\BaseDao;
use app\model\OwnDeclareOcr;

class OwnDeclareOcrDao extends BaseDao
{
    
    protected function setModel(): string
    {
        return OwnDeclareOcr::class;
    }

    public function getList($param)
    {
        $where = [];
        //分段导出
        if(isset($param['_where_id_lg']) && $param['_where_id_lg'] > 0) {
            $where[] = ['id', '<', $param['_where_id_lg']];
        }
        if( isset($param['keyword']) && $param['keyword'] != '') {
            $where[] = ['id_card', 'LIKE', '%'. $param['keyword'] .'%'];
        }

        if( isset($param['id_card']) && $param['id_card'] != '') {
            $where[] = ['id_card', 'LIKE', '%'. $param['id_card'] .'%'];
        }
        if( isset($param['travel_content']) && $param['travel_content'] != '') {
            $where[] = ['travel_content', 'LIKE', '%'. $param['travel_content'] .'%'];
        }
        if( isset($param['travel_route']) && $param['travel_route'] != '') {
            $where[] = ['travel_route', 'LIKE', '%'. $param['travel_route'] .'%'];
        }

        if( isset($param['list_type']) && $param['list_type'] == 'data_error') {
            $where[] = function ($query) use($param)  {
                // $query->where('ocr_result','>',0);
                $query->where('travel_route','=','');
            };
        }
        $param['size'] = isset($param['size']) ? $param['size'] : 20;        
        return $this->getModel()::where($where)
                ->field('id,declare_id,id_card,travel_content,travel_route,travel_time,remark,create_time,update_time,travel_img,match_result')
                ->order('id desc')
                ->paginate($param['size'])
                ->toArray();
    }

    public function getUnmatchOcr()
    {
        $where = [];
        $where[] = ['match_result', '=', 0];
        // $where[] = ['travel_route', '=', ''];
        
        return $this->getModel()::where($where)
                ->field('id,declare_id,travel_content')
                ->order('id desc')
                ->limit(100)
                ->select()
                ->toArray();
    }
}
