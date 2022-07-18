<?php

namespace app\dao;

use app\dao\BaseDao;
use app\model\PersonalCode;

class PersonalCodeDao extends BaseDao
{
    protected function setModel(): string
    {
        return PersonalCode::class;
    }

    //后台列表
    public function getList($param)
    {
        $where = [];
        //分段导出
        if(isset($param['_where_id_lg']) && $param['_where_id_lg'] > 0) {
            $where[] = ['id', '<', $param['_where_id_lg']];
        }
        if(isset($param['real_name']) && $param['real_name'] != '') {
            $where[] = ['real_name', '=', $param['real_name']];
        }
        if(isset($param['phone']) && $param['phone'] != '') {
            $where[] = ['phone', '=', $param['phone']];
        }
        if(isset($param['id_card']) && $param['id_card'] != '') {
            $where[] = ['id_card', '=', $param['id_card']];
        }
        if(isset($param['agent_name']) && $param['agent_name'] != '') {
            $where[] = ['agent_name', '=', $param['agent_name']];
        }
        if(isset($param['agent_idcard']) && $param['agent_idcard'] != '') {
            $where[] = ['agent_idcard', '=', $param['agent_idcard']];
        }
        if(isset($param['agent_phone']) && $param['agent_phone'] != '') {
            $where[] = ['agent_phone', '=', $param['agent_phone']];
        }

        return $this->getModel()::where($where)
            ->order('id', 'desc')
            ->paginate($param['size'])
            ->toArray();
    }

    //用户申领的列表
    public function getUserPersonalCodeList($param, $id_card)
    {
        return $this->getModel()::where('agent_idcard', $id_card)->order('id', 'desc')->paginate($param['size'])->toArray();
    }
}
