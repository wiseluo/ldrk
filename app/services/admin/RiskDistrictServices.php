<?php
declare (strict_types=1);

namespace app\services\admin;

use app\services\user\BaseServices;
use app\dao\RiskDistrictDao;
use app\dao\DistrictDao;
use behavior\PubTool;
use crmeb\services\SwooleTaskService;
use think\facade\Cache;

class RiskDistrictServices extends BaseServices
{
    public function __construct(RiskDistrictDao $dao)
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

        if((int)$param['county_id'] == 0 && (int)$param['city_id'] > 0){
            $city_code = $districtDao->getCodeById($param['city_id']);
        }else if($param['county_id'] > 0){
            $city_code = $districtDao->getCodeById($param['county_id']);
        }else{
            return ['status' => 0, 'msg'=> '只能到市或者县'];
        }

        $province = $districtDao->getNameById($param['province_id']);
        if(in_array($province,['北京','上海','重庆','天津'])){
            // 遇见直辖市的，先直接传直辖市编码，
            // 北京110000
            // 上海310000
            // 重庆500000
            // 天津120000
            switch($province){
                case '北京';
                    $city_code = '110000';
                    break;
                case '上海';
                    $city_code = '310000';
                    break;
                case '重庆';
                    $city_code = '500000';
                    break;
                case '天津';
                    $city_code = '120000';
                    break;
            }
        }

        $data = [
            'province_id' => $param['province_id'],
            'province' => $province,
            'city_id' => $param['city_id'],
            'city' => $districtDao->getNameById($param['city_id']),
            'address' => $param['address'],
            'risk_level' => $param['risk_level'],
            'start_date' => $param['start_date'],
            'city_code' => $city_code,
            'high_pro' => $param['high_pro'],
        ];
        if($param['county_id'] == 0) {
            $data['county_id'] = 0;
            $data['county'] = '';
        }else{
            $data['county_id'] = $param['county_id'];
            $data['county'] = $districtDao->getNameById($param['county_id']);
        }
        if($param['street_id'] == 0) {
            $data['street_id'] = 0;
            $data['street'] = '';
        }else{
            $data['street_id'] = $param['street_id'];
            $data['street'] = $districtDao->getNameById($param['street_id']);
        }
        try {
            $this->dao->save($data);
            Cache::delete('RiskareaList');
            PubTool::publish('clearCacheByName',['name'=>'RiskareaList']);
            //设置申报预警
            $task_param = [
                'type'=> 'add',
                'province'=> $data['province'],
                'city'=> $data['city'],
                'start_date'=> $data['start_date'],
            ];
            SwooleTaskService::declare()->taskType('declare')->data(['action'=>'setDeclareSysWarning','param'=> $task_param])->push();
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }

    public function updateService($param, $id)
    {
        $risk_district = $this->dao->get($id);
        if($risk_district == false) {
            return ['status' => 0, 'msg' => '该中高风险地区不存在'];
        }
        $data = [
            'address' => $param['address'],
            'risk_level' => $param['risk_level'],
            'start_date' => $param['start_date'],
            'high_pro' => $param['high_pro'],
        ];
        try {
            $this->dao->update($id, $data);
            Cache::delete('RiskareaList');
            PubTool::publish('clearCacheByName',['name'=>'RiskareaList']);
            //时间往前调整，设置新时间与旧时间之间的申报为预警
            if($risk_district['start_date'] > $param['start_date']) {
                $task_param = [
                    'type'=> 'update',
                    'province'=> $risk_district['province'],
                    'city'=> $risk_district['city'],
                    'old_start_date'=> $risk_district['start_date'],
                    'new_start_date'=> $param['start_date'],
                ];
                SwooleTaskService::declare()->taskType('declare')->data(['action'=>'setDeclareSysWarning','param'=> $task_param])->push();
            }
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }
    
    public function deleteService($id)
    {
        $risk_district = $this->dao->get($id);
        if($risk_district == null) {
            return ['status' => 0, 'msg' => '风险地区不存在'];
        }
        try {
            $this->dao->softDelete($id);
            Cache::delete('RiskareaList');
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }

}
