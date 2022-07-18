<?php
declare (strict_types=1);

namespace app\services\admin;

use app\services\user\BaseServices;
use app\dao\BarrierDistrictDao;
use app\dao\DistrictDao;

class BarrierDistrictServices extends BaseServices
{
    public function __construct(BarrierDistrictDao $dao)
    {
        $this->dao = $dao;
    }

    public function getList($param)
    {
        return $this->dao->getList($param);
    }

    public function readService($id)
    {
        $gov = $this->dao->get($id);
        if($gov) {
            return ['status' => 1, 'msg'=> '成功', 'data'=> $gov];
        }else{
            return ['status' => 0, 'msg'=> '不存在'];
        }
    }
    
    public function saveService($param)
    {
        $districtDao = app()->make(DistrictDao::class);
        $data = [
            'province_id' => $param['province_id'],
            'province' => $districtDao->getNameById($param['province_id']),
        ];
        $data['district_id'] = $data['province_id'];
        $data['district'] = $data['province'];
        if($param['city_id'] == 0) {
            $data['city_id'] = 0;
            $data['city'] = '';
        }else{
            $data['city_id'] = $param['city_id'];
            $data['city'] = $districtDao->getNameById($param['city_id']);
            $data['district_id'] = $data['city_id'];
            $data['district'] = $data['city'];
        }
        if($param['county_id'] == 0) {
            $data['county_id'] = 0;
            $data['county'] = '';
        }else{
            $data['county_id'] = $param['county_id'];
            $data['county'] = $districtDao->getNameById($param['county_id']);
            $data['district_id'] = $data['county_id'];
            $data['district'] = $data['county'];
        }
        if($param['street_id'] == 0) {
            $data['street_id'] = 0;
            $data['street'] = '';
        }else{
            $data['street_id'] = $param['street_id'];
            $data['street'] = $districtDao->getNameById($param['street_id']);
            $data['district_id'] = $data['street_id'];
            $data['district'] = $data['street'];
        }
        try {
            $this->dao->save($data);
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }

    public function updateService($param, $id)
    {
        $barrier_district = $this->dao->get($id);
        if($barrier_district == false) {
            return ['status' => 0, 'msg' => '该卡口地区不存在'];
        }
        $districtDao = app()->make(DistrictDao::class);
        $data = [
            'province_id' => $param['province_id'],
            'province' => $districtDao->getNameById($param['province_id']),
        ];
        $data['district_id'] = $data['province_id'];
        $data['district'] = $data['province'];
        if($param['city_id'] == 0) {
            $data['city_id'] = 0;
            $data['city'] = '';
        }else{
            $data['city_id'] = $param['city_id'];
            $data['city'] = $districtDao->getNameById($param['city_id']);
            $data['district_id'] = $data['city_id'];
            $data['district'] = $data['city'];
        }
        if($param['county_id'] == 0) {
            $data['county_id'] = 0;
            $data['county'] = '';
        }else{
            $data['county_id'] = $param['county_id'];
            $data['county'] = $districtDao->getNameById($param['county_id']);
            $data['district_id'] = $data['county_id'];
            $data['district'] = $data['county'];
        }
        if($param['street_id'] == 0) {
            $data['street_id'] = 0;
            $data['street'] = '';
        }else{
            $data['street_id'] = $param['street_id'];
            $data['street'] = $districtDao->getNameById($param['street_id']);
            $data['district_id'] = $data['street_id'];
            $data['district'] = $data['street'];
        }
        try {
            $this->dao->update($id, $data);
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }
    
    public function deleteService($id)
    {
        $barrier_district = $this->dao->get($id);
        if($barrier_district == null) {
            return ['status' => 0, 'msg' => '卡口地区不存在'];
        }
        try {
            $this->dao->softDelete($id);
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }

}
