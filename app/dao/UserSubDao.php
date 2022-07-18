<?php

declare (strict_types=1);

namespace app\dao;

use app\dao\BaseDao;
use app\model\UserSub;


/**
 * 用户
 * Class UserDao
 * @package app\dao\user
 */
class UserSubDao extends BaseDao
{
    
    protected function setModel(): string
    {
        return UserSub::class;
    }

    public function getSubChangeList($param)
    {
        $where = [];
        //$where[] = ['us.update_time', '>', time()-129600]; //36小时内有修改
        //分段导出
        if(isset($param['_where_id_lg']) && $param['_where_id_lg'] > 0) {
            $where[] = ['us.id', '<', $param['_where_id_lg']];
        }
        if( isset($param['real_name']) && $param['real_name'] != '') {
            $where[] = ['u.real_name', '=', $param['real_name']];
        }
        if( isset($param['phone']) && $param['phone'] != '') {
            $where[] = ['u.phone', '=', $param['phone']];
        }
        if( isset($param['id_card']) && $param['id_card'] != '') {
            $where[] = ['u.id_card', '=', $param['id_card']];
        }
        if( isset($param['start_date']) && $param['start_date']) {
            $where[] = ['us.update_time', '>=', strtotime($param['start_date']) ];
        }
        if( isset($param['end_date']) && $param['end_date']) {
            $where[] = ['us.update_time', '<=', strtotime($param['end_date']) ];
        }

        return $this->getModel()::alias('us')
            ->leftJoin('user u', 'u.id=us.user_id')
            ->field('us.*,u.real_name,u.id_card,u.phone')
            ->where($where)
            ->order('id', 'desc')
            ->paginate($param['size'])
            ->toArray();
    }
}
