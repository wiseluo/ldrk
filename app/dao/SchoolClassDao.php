<?php

namespace app\dao;

use app\dao\BaseDao;
use app\model\SchoolClass;

class SchoolClassDao extends BaseDao
{
    /**
     * 设置模型
     * @return string
     */
    protected function setModel(): string
    {
        return SchoolClass::class;
    }

    public function getList($param)
    {
        $where = [];
        // //分段导出
        if (isset($param['class_name']) && $param['class_name'] != '') {
            $where[] = ['class_name', 'LIKE', '%' . $param['class_name'] . '%'];
        }
        if (isset($param['school_code']) && $param['school_code'] != '') {
            $where[] = ['school_code', '=', $param['school_code']];
        }

        return $this->getModel()::where($where)->field('id,class_name,weight')->order('weight', 'asc')->paginate($param['size'])->toArray();
    }
}
