<?php

namespace app\services;

use think\facade\Db;
use think\facade\Config;
use Curl\Curl;
use app\dao\OwnDeclareDao;
use app\services\admin\system\config\SystemConfigServices;

//数管中心接口服务
class ZzsbViewServices
{
    public function zzsb_view_db($lastid)
    {
        $result = Db::connect('zzsb_view')->query('SELECT `zzsb_view`.* FROM `zzsb_view` where id > '. $lastid .' order by id LIMIT 100');
        return $result;
    }

    public function csm_ryxx_db($id_card)
    {
        // $result = Db::connect('zzsb_view')->query('SELECT * FROM `csm_ryxx` where idcard= '. $id_card .' LIMIT 1');
        // return $result;
        $result = Db::name('csm_ryxx')->where('idcard','=',$id_card)->find();
        return $result;
    }

    public function zzsb_view($lastid)
    {
        if(Config::get('app.app_host') == 'dev') {
            // 测试环境走 curl
            $curl = new Curl();
            $curl->setReferer('localhost');
            $result = $curl->get('http://localhost:8083/sgzx_view/zzsb_view', ['lastid'=>$lastid]);
            $data = $curl->response;
            $res = json_decode($data,true);
            if(isset($res['code']) && $res['code'] == 200){
                return $res['data'];
            }else{
                test_log("zzsb_view获取自主申报视图失败". $data);
            }
        }else{
            $result = $this->zzsb_view_db($lastid);
        }
        return $result;
    }

    public function csm_ryxx($id_card)
    {
        // if(Config::get('app.app_host') == 'dev') {
        //     // 测试环境走 curl
        //     $curl = new Curl();
        //     $curl->setReferer('localhost');
        //     $result = $curl->get('http://localhost:8083/sgzx_view/csm_ryxx', ['id_card'=> $id_card]);
        //     $data = $curl->response;
        //     $res = json_decode($data,true);
        //     if(isset($res['code']) && $res['code'] == 200){
        //         return $res['data'];
        //     }else{
        //         test_log("csm_ryxx场所码人员信息视图". $data);
        //     }
        // }else{
            $result = $this->csm_ryxx_db($id_card);
        // }
        return $result;
    }

    public function setControlStateService()
    {
        $zzsb_view_lastid = app()->make(SystemConfigServices::class)->value(['menu_name'=> 'zzsb_view_lastid'], 'value');
        $list = $this->zzsb_view($zzsb_view_lastid);
        $ownDeclareDao = app()->make(OwnDeclareDao::class);
        foreach($list as $v) {
            $ownDeclareDao->update($v['from_id'], ['control_state'=> $v['state']]);
            $zzsb_view_lastid = $v['id'];
        }
        app()->make(SystemConfigServices::class)->update(['menu_name'=> 'zzsb_view_lastid'], ['value'=> $zzsb_view_lastid]);
        \crmeb\services\SystemConfigService::clear();
        return $zzsb_view_lastid;
    }
}
