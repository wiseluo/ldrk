<?php

namespace app\model;

use crmeb\basic\BaseModel;
use think\model\concern\SoftDelete;
use crmeb\traits\ModelTrait;
use think\facade\Db;

class RiskDistrictPro extends BaseModel
{
    use ModelTrait;
    use SoftDelete;

    protected $pk = 'id';

    protected $name = 'risk_district_pro';

    protected $autoWriteTimestamp = true;
    
    protected $deleteTime = 'delete_time';



    public function getRiskLevelList()
    {
        return ['low'=> '系低风险', 'middling'=> '系中风险', 'high'=> '系高风险'];
    }

    public function getRiskLevelTextAttr($value, $data){
        $arr = $this->getRiskLevelList();
        return $arr[$data['risk_level']];
    }
    
    // 最近14天的人数
    public function getLastDaysNumsAttr($value, $data){
        
        if(isset($data['street_id']) && $data['street_id'] > 0){
            $street_id = $data['street_id'];
            return Db::name('declare')
            ->where('create_time', '>=', strtotime('-14 day'))
            ->where('declare_type','<>','leave')
            ->where('street_id','=',$street_id)->count();
        }
        if(isset($data['county_id'])){
            $county_id = $data['county_id'];
            return Db::name('declare')
            ->where('create_time', '>=', strtotime('-14 day'))
            ->where('declare_type','<>','leave')
            ->where('county_id','=',$county_id)->count();
        }
        return 0;
    }

}
