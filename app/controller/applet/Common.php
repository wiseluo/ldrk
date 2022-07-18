<?php
namespace app\controller\applet;

use crmeb\basic\BaseController;
use app\validate\applet\CommonValidate;
use app\dao\CommunityDao;
use app\dao\FollowDistrictDao;
use app\dao\RiskDistrictDao;
use app\dao\RiskDistrictProDao;
use app\services\SgzxServices;
use app\services\applet\BarrierDistrictServices;
use think\facade\Db;
use app\model\Company;
use think\facade\Cache;

class Common extends BaseController
{
    public function enterpriseInfo()
    {
        $param = $this->request->param();
        $validate = new CommonValidate();
        if(!$validate->scene('enterpriseInfo')->check($param)) {
            return show(400, $validate->getError());
        }
        $sgzx_res = app()->make(SgzxServices::class)->enterpriseInfo($param['credit_code']);
        if($sgzx_res['status'] == 0) {
            return show(400, '社会信用代码证错误');
        }
        return show(200, '成功', ['name'=> $sgzx_res['data']['companyName']]);
    }

    public function communityList()
    {
        $param = $this->request->param();
        $validate = new CommonValidate();
        if(!$validate->scene('communityList')->check($param)) {
            return show(400, $validate->getError());
        }
        $list = app()->make(CommunityDao::class)->getListByYwStreetId($param['yw_street_id']);
        return show(200, '成功', $list);
    }
    
    public function barrierDistrictList()
    {
        return show(200, '成功', app()->make(BarrierDistrictServices::class)->getBarrierListService());
    }

    public function riskAndFollowDistrictList(){

            $data = app()->make(RiskDistrictDao::class)->getAllRiskCityData();
            $city_arr = array_values(array_unique( array_column($data,'city')));
            // $followdata = app()->make(FollowDistrictDao::class)->getAllFollowCityData();
            // $follow_ciry_arr = array_values(array_unique( array_column($followdata,'city') ));
            $data_pro = app()->make(RiskDistrictProDao::class)->getAllRiskCityData();
            $high_city_arr = [];
            foreach($data_pro as $key => $value){
                if(!in_array($value['city'],$high_city_arr)){
                    array_push($high_city_arr,$value['city']);
                }
            }
            $follow_city = Db::name('system_config')->where('menu_name','=','follow_city')->value('value');

            $follow_city_arr =  array_filter( explode(',',$follow_city) );
            if($high_city_arr){
                foreach($high_city_arr as $highcity){
                    array_push($follow_city_arr,$highcity);
                }
            }
            $follow_city_arr = array_unique($follow_city_arr); // 去重

            $return['follow_ciry_arr'] = $follow_city_arr;
            $return['risk_city_arr'] = $city_arr;
            $return['high_risk_city_arr'] = $high_city_arr;
            Cache::set('riskAndFollowDistrictList',$return,3600);
            return show(200, '成功', $return);

            
    }

    private function _cache_follow_city(){
        $hasCache = Cache::get('system_config_follow_city');
        if($hasCache){
            return $hasCache;
        }else{
            $follow_city = Db::name('system_config')->where('menu_name','=','follow_city')->value('value');
            if($follow_city){
                Cache::set('system_config_follow_city',$follow_city,7200);
                return $follow_city;
            }
            return '';
        }
    }



    public function companyClassifyList(){
        // $hasCache = Cache::get('companyClassifyList');
        // if($hasCache){
        //     return  show(200, '成功cache', $hasCache);
        // }
        $data = Company::getCompanyClassifyList();
        $new_data = [];
        foreach($data as $key => $value){
            array_push($new_data,['name'=>$value,'key'=>$key]);
        }
        // Cache::set('companyClassifyList',$new_data,36000);
        return show(200, '成功', $new_data);
    }


}
