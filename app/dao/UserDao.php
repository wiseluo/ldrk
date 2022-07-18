<?php

declare (strict_types=1);

namespace app\dao;

use app\dao\BaseDao;
use app\model\User;


/**
 * 用户
 * Class UserDao
 * @package app\dao\user
 */
class UserDao extends BaseDao
{
    
    protected function setModel(): string
    {
        return User::class;
    }

    public function getUserList($param)
    {
        $where = [];
        //分段导出
        if(isset($param['_where_id_lg']) && $param['_where_id_lg'] > 0) {
            $where[] = ['id', '<', $param['_where_id_lg']];
        }
        if( isset($param['real_name']) && $param['real_name'] != '') {
            $where[] = ['real_name', '=', $param['real_name']];
        }
        if( isset($param['phone']) && $param['phone'] != '') {
            $where[] = ['phone', '=', $param['phone']];
        }
        if( isset($param['id_card']) && $param['id_card'] != '') {
            $where[] = ['id_card', '=', $param['id_card']];
        }
        if( isset($param['declare_type']) && $param['declare_type'] != '') {
            $where[] = ['declare_type', '=', $param['declare_type']];
        }
        if( isset($param['gender']) && $param['gender'] != '') {
            $where[] = ['gender', '=', $param['gender']];
        }
        if( isset($param['vaccination']) && $param['vaccination'] != '') {
            $where[] = ['vaccination', '=', $param['vaccination']];
        }
        if( isset($param['hsjc_result']) && $param['hsjc_result'] != '') {
            $where[] = ['hsjc_result', '=', $param['hsjc_result']];
        }

        return $this->getModel()::where($where)
            ->append(['age', 'gender_text', 'card_type_text', 'declare_type_text', 'position_type_text'])
            ->order('id', 'desc')
            ->paginate($param['size'])
            ->toArray();
    }

    public function getUserByIds($ids)
    {
        return $this->getModel()::whereIn('id', $ids)->select()->toArray();
    }

}
