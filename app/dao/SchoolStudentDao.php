<?php

namespace app\dao;

use app\dao\BaseDao;
use app\model\SchoolStudent;

class SchoolStudentDao extends BaseDao
{
    /**
     * 设置模型
     * @return string
     */
    protected function setModel(): string
    {
        return SchoolStudent::class;
    }


    public function getList($param, $field)
    {
        $where = [];

        // $where[] = ['student_id', '=', $param['student_id']];
        if (isset($param['class_id']) && $param['class_id'] != '') {
            $where[] = ['class_id', '=', $param['class_id']];
        }


        if (isset($param['size']) && $param['size'] != '') {
            $size =  $param['size'];
        } else {
            $size = 10;
        }

        return $this->getModel()
            ->field($field)
            ->where($where)
            ->paginate($size)
            ->toArray();
    }
}
