<?php

namespace app\dao;

use app\dao\BaseDao;
use app\model\SchoolClassTeacher;

class SchoolClassTeacherDao extends BaseDao
{
    /**
     * 设置模型
     * @return string
     */
    protected function setModel(): string
    {
        return SchoolClassTeacher::class;
    }

    // //用于定时任务:计算企业核酸检测数
    // public function taskUnfixCompanyListByLimit()
    // {
    //     return $this->getModel()
    //         ->field('id,user_count')
    //         ->where('fix_tag','=',0)
    //         ->limit(100)
    //         ->select()
    //         ->toArray();
    // }


    public function getList($param)
    {
        $where = [];
        //分段导出
        if (isset($param['class_name']) && $param['class_name'] != '') {
            $where[] = ['class_name', 'LIKE', '%' . $param['class_name'] . '%'];
        }
        if (isset($param['audit_status']) && $param['audit_status'] != '') {
            $where[] = ['audit_status', '=', $param['audit_status']];
        }
        if (isset($param['school_code']) && $param['school_code'] != '') {
            $where[] = ['school_code', '=', $param['school_code']];
        }

        return $this->getModel()::where($where)->field('id,school_name,class_name,teacher_name,teacher_phone,teacher_idcard,audit_status,create_time')->order('create_time', 'desc')->paginate($param['size'])->toArray();
    }
}
