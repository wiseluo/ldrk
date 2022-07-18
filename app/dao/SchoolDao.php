<?php

namespace app\dao;

use app\dao\BaseDao;
use app\model\School;

class SchoolDao extends BaseDao
{
    /**
     * 设置模型
     * @return string
     */
    protected function setModel(): string
    {
        return School::class;
    }

    public function getUserSchoolInfo($user_id)
    {
        $where = [];
        $where[] = ['link_id', '=', $user_id];
        $res = $this->getModel()->field('name,code,yw_street,community,link_name,link_phone ,school_qrcode,user_count,create_time')
            ->where($where)
            ->find();
        if (!empty($res)) {
            $res['school_qrcode'] = $res->school_qrcode_arr;
        }
        return $res;
    }
}
