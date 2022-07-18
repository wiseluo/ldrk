<?php

namespace app\dao;

use app\dao\BaseDao;
use app\model\RiskDistrictPro;

class RiskDistrictProDao extends BaseDao
{
    /**
     * 设置模型
     * @return string
     */
    protected function setModel(): string
    {
        return RiskDistrictPro::class;
    }

    public function getList($param)
    {
        $where = [];
        if( isset($param['province_id']) && $param['province_id'] > 0) {
            $where[] = ['province_id', '=', $param['province_id']];
        }
        if( isset($param['city_id']) && $param['city_id'] > 0) {
            $where[] = ['city_id', '=', $param['city_id']];
        }
        if( isset($param['county_id']) && $param['county_id'] > 0) {
            $where[] = ['county_id', '=', $param['county_id']];
        }
        if( isset($param['street_id']) && $param['street_id'] > 0) {
            $where[] = ['street_id', '=', $param['street_id']];
        }
        if( isset($param['risk_level']) && $param['risk_level'] != '') {
            $where[] = ['risk_level', '=', $param['risk_level']];
        }
        if( isset($param['start_date']) && $param['start_date'] != '') {
            $where[] = ['start_date', '=', $param['start_date']];
        }
        if( isset($param['time']) && $param['time'] != '') {
            $where[] = function ($query) use($param)  {
                $query->whereTime('start_date','<=',$param['time']);
            };
            $where[] = function ($query) use($param)  {
                $query->whereTime('end_date','>',$param['time']);
            };
        }

        $param['size'] = isset($param['size']) ? $param['size'] : 20;
        return $this->getModel()
            ->where($where)
            ->append(['risk_level_text','last_days_nums'])
            ->order('province_id,city_id,county_id', 'desc')
            ->paginate($param['size'])
            ->toArray();
    }

    public function getAllRiskCityData(){
        return $this->getModel()
            // ->append(['risk_level_text','last_days_nums'])
            ->field('city,risk_level,high_pro')
            ->select()
            ->toArray();
    }



    public function getCityCodeConcat()
    {
        $where = [];
        // $where[] = ['high_pro', '=', 1];
        $list = $this->getModel()::field('city_code')->where($where)->select();
        $city_code = '';
        foreach($list as $v) {
            if($city_code == '') {
                $city_code = $v['city_code'];
            }else{
                $city_code .= ','. $v['city_code'];
            }
        }
        return $city_code;
    }

}
