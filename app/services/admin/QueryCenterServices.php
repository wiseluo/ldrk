<?php

namespace app\services\admin;

use app\dao\PlaceDeclareDateNumsDao;
use app\dao\PlaceDeclareHourNumsDao;
use app\services\user\BaseServices;
use app\dao\UserDao;
use app\services\FysjServices;
use think\facade\Db;

class QueryCenterServices extends BaseServices
{

    public function healthInfoService($param)
    {
        $user = app()->make(UserDao::class)->get(['id_card'=> $param['id_card']]);
        if($user == null) {
            return ['status'=> 0, 'msg'=> '系统中不存在该用户'];
        }
        $fysjServices = app()->make(FysjServices::class);
        $jkm = $fysjServices->getJkmActualService($user['id_card'], '');
        $hsjc = $fysjServices->getShsjcService($user['real_name'], $user['id_card']);
        $ymjz = $fysjServices->getXgymyfjzService($user['id_card']);
        $res = array_merge($jkm, $hsjc, $ymjz);
        $res['id_card'] = $user['id_card'];
        $res['real_name'] = $user['real_name'];
        $res['phone'] = $user['phone'];
        return ['status'=> 1, 'msg'=> '成功', 'data' => $res];
    }

    public function rygkService($param)
    {
        $where = [];
        //分段导出
        if(isset($param['_where_id_lg']) && $param['_where_id_lg'] > 0) {
            $where[] = ['id', '>', $param['_where_id_lg']];
        }
        if(isset($param['name']) && $param['name'] != '') {
            $where[] = ['name', 'like', '%'. $param['name'] .'%'];
        }
        if(isset($param['idcard']) && $param['idcard'] != '') {
            $where[] = ['idcard', '=', $param['idcard']];
        }
        if(isset($param['phonenum']) && $param['phonenum'] != '') {
            $where[] = ['phonenum', '=', $param['phonenum']];
        }
        if(isset($param['town']) && $param['town'] != '') {
            $where[] = ['town', '=', $param['town']];
        }
        if(isset($param['village']) && $param['village'] != '') {
            $where[] = ['village', '=', $param['village']];
        }
        if(isset($param['cc']) && $param['cc'] != '') {
            $where[] = ['cc', '=', $param['cc']];
        }
        if(isset($param['village']) && $param['village'] != '') {
            $where[] = ['village', '=', $param['village']];
        }
        if(isset($param['company_name']) && $param['company_name'] != '') {
            $where[] = ['company_name', 'like', '%'. $param['company_name'] .'%'];
        }
        if(isset($param['lxname']) && $param['lxname'] != '') {
            $where[] = ['lxname', 'like', '%'. $param['lxname'] .'%'];
        }
        if(isset($param['lxphone']) && $param['lxphone'] != '') {
            $where[] = ['lxphone', 'like', '%'. $param['lxphone'] .'%'];
        }
        if(isset($param['person_classification']) && $param['person_classification'] != '') {
            $where[] = ['person_classification', 'like', '%'. $param['person_classification'] .'%'];
        }
        if(isset($param['state']) && $param['state'] != '') {
            $where[] = ['state', 'like', '%'. $param['state'] .'%'];
        }
        return Db::name('yw_rygk')->where($where)->paginate($param['size'])->toArray();
    }

    
    public function placeClassifyDateNums($param=[]){
        $data = app()->make(PlaceDeclareDateNumsDao::class)->getPlaceClassifyDateNums();
        return $data;
    }
    public function placeTypeDateNums($param=[]){
        $data = app()->make(PlaceDeclareDateNumsDao::class)->getPlaceTypeDateNums();
        return $data;
    }
    
    public function placeStreetDateNums($param=[]){
        $data = app()->make(PlaceDeclareDateNumsDao::class)->getPlaceStreetDateNums();
        return $data;
    }

    public function placeDateNumsByName($param=[],$name='jkm'){
        $data = app()->make(PlaceDeclareDateNumsDao::class)->getPlaceDateNumsByName($name);
        return $data;
    }

    public function placeHourNums($param=[]){
        $data = app()->make(PlaceDeclareHourNumsDao::class)->getPlaceHourNums();
        return $data;
    }



}
