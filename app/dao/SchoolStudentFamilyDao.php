<?php

namespace app\dao;

use app\dao\BaseDao;
use app\model\SchoolStudentFamily;
use app\model\User;
use app\services\FysjServices;

class SchoolStudentFamilyDao extends BaseDao
{
    /**
     * 设置模型
     * @return string
     */
    protected function setModel(): string
    {
        return SchoolStudentFamily::class;
    }

    public function getList($param, $field = 'id as family_id,relationship_name,real_name,id_card,phone')
    {
        $where = [];

        // $where[] = ['student_id', '=', $param['student_id']];
        if (isset($param['student_id']) && $param['student_id'] != '') {
            $where[] = ['student_id', '=', $param['student_id']];
        }

        if (isset($param['id_card']) && $param['id_card'] != '') {
            $where[] = ['id_card', '=', $param['id_card']];
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



    public function checkFamilly($param)
    {
        $where = [];

        $where[] = ['f.school_code', '=', $param['school_code']];
        $where[] = ['f.class_id', '=', $param['class_id']];
        // if( isset($param['name']) && $param['name'] != '') {
        //     $where[] = ['c.name', '=', $param['name']];
        // }
        $type = $param['type'];
        $currentTime = time();

        if ($type == "default") {
            $res = $this->getModel()->alias('f')
                ->leftJoin('user u', 'f.id_card = u.id_card')
                ->field('id as family_id,f.real_name as family_name,f.id_card as family_idcard,f.phone as family_phone,f.relationship_name,f.student_name,f.student_number,u.hsjc_time,u.jkm_mzt,u.ryxx_result,u.xcm_result,u.xcm_gettime,u.jkm_time,u.jkm_gettime,u.ryxx_gettime')
                ->where($where)
                ->paginate($param['size'])
                ->order('student_number asc')
                ->toArray();


            if (!empty($res['data'])) {
                $fysjService = app()->make(FysjServices::class);

                foreach ($res['data'] as $key => &$value) {
                    // 人员管控情况
                    $ryxx_res = $fysjService->getRyxxServiceV2($value['family_idcard'], ['ryxx_result' => $value['ryxx_result'] ?? '', 'xcm_result' => $value['xcm_result']]);
                    $value['ryxx_res'] = $ryxx_res['ryxx_result'];
                    $value['ryxx_cc'] = $ryxx_res['ryxx_cc'];
                    unset($value['ryxx_result']);
                }
            }

            return $res;
        } elseif ($type == "error") {
            $data = [];
            $res = $this->getModel()->alias('f')
                ->leftJoin('user u', 'f.id_card = u.id_card')
                ->field('id as family_id,f.real_name as family_name,f.id_card as family_idcard,f.phone as family_phone,f.relationship_name,f.student_name,f.student_number,u.hsjc_time,u.jkm_mzt,u.ryxx_result,u.xcm_result,u.xcm_gettime,u.jkm_time,u.jkm_gettime,u.ryxx_gettime')
                ->where($where)
                ->paginate($param['size'])
                ->order('student_number asc')
                ->toArray();


            if (!empty($res['data'])) {


                $fysjService = app()->make(FysjServices::class);

                foreach ($res['data'] as $key => &$value) {
                    // 判断健康码获取时间 是否超过两小时 超过的话 获取实时健康码
                    if ($currentTime - strtotime($value['jkm_gettime']) > (3600 * 2)) {
                        // 获取家属健康码
                        $jkmRes =  $fysjService->getJkmActualService($value['family_idcard'], $value['family_phone']);
                        // 更新家属健康码
                        app()->make(User::class)->update(['id_card' => $value['family_idcard'], ['jkm_mzt' => $jkmRes['jkm_mzt'], 'jkm_time' => $jkmRes['jkm_time'], 'jkm_gettime' => $currentTime]]);

                        $value['jkm_mzt'] = $jkmRes['jkm_mzt'];
                        $value['jkm_time'] = $jkmRes['jkm_time'];
                        $value['jkm_gettime'] = $currentTime;
                    }


                    // 人员管控情况
                    $ryxx_res = $fysjService->getRyxxServiceV2($value['family_idcard'], ['ryxx_result' => $value['ryxx_result'] ?? '', 'xcm_result' => $value['xcm_result']]);
                    $value['ryxx_res'] = $ryxx_res['ryxx_result'];
                    $value['ryxx_cc'] = $ryxx_res['ryxx_cc'];
                    unset($value['ryxx_result']);

                    // 如果管控状态不为空 健康码不是绿色 行程码不是未去过高风险
                    if ($value['ryxx_res'] != '' || $value['jkm_mzt'] != '绿码' || $value['xcm_result'] != '1') {
                        array_push($data, $value);
                    }
                }
            }
            return $data;
        } else {
            return [];
        }
    }
}
