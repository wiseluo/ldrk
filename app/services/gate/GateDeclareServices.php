<?php
declare (strict_types=1);

namespace app\services\gate;

use app\dao\GateDao;
use app\services\gate\BaseServices;
use \behavior\SsjptTool;
use crmeb\services\SwooleTaskService;
use think\facade\Config;
use think\facade\Cache;
use app\dao\GateDeclareDao;
use app\services\FysjServices;
use app\dao\PlaceDeclareDao;
use app\dao\UserDao;
use think\facade\Db;

class GateDeclareServices extends BaseServices
{
    public function __construct(GateDeclareDao $dao)
    {
        $this->dao = $dao;
    }

    public function addHistoryService($param,$gateFactoryInfo=[]){
        $cacheGate = Cache::get('gate_code_'.$param['gate_code']);
        if($cacheGate){
            // test_log('来自缓存');
            $gate = $cacheGate;
        }else{
            // test_log('来自数据库');
            $gate = app()->make(GateDao::class)->get(['code'=> $param['gate_code']]);
            if($gate){
                $cacheGate = Cache::set('gate_code_'.$param['gate_code'],$gate,7200);
            }else{
                return ['status' => 0, 'msg' => '不存在的gate_code'];
            }
        }

        $gate_code = $param['gate_code'];

        $sign = md5($gate_code .'|'. microtime(true));


        // 需要将人员的管控状态返回去
        $fysjService = app()->make(FysjServices::class);
        $res = $fysjService->getRyxxServiceV2($param['id_card']);
        $ryxx_result = ''; // #无需管控
        $ryxx_cc = '绿';
        if($res['status'] == 1){
            $ryxx_result = $res['ryxx_result'];
            $ryxx_cc = $res['ryxx_cc'];
        }

        $data = [
            'sign' => $sign,
            'gate_code' => $gate_code,
            'gate_name' => $gate['name'],
            'place_short_name' => $gate['short_name'],
            'link_man' => $gate['link_man'],
            'link_phone' => $gate['link_phone'],
            'real_name' => $param['real_name'],
            'id_card' => $param['id_card'],
            'phone' => $param['phone'],
            'yw_street_id' => $gate['yw_street_id'],
            'yw_street' => $gate['yw_street'],
            'create_datetime' => $param['create_datetime'],
            'create_date' => date('Y-m-d',strtotime($param['create_datetime'])),
            'ryxx_result' => $ryxx_result,
            'ryxx_cc' => $ryxx_cc,
            // 非必填项
            'vaccination_times' => isset($param['vaccination_times']) ? $param['vaccination_times'] : 0,
            'vaccination_date' => isset($param['vaccination_date']) ? $param['vaccination_date'] : 0,
            'vaccination' => (isset($param['vaccination_times']) && $param['vaccination_times'] > 0)? 1 : 0,
            'xgymjz_get' => (isset($param['vaccination_times']) && $param['vaccination_times'] > 0)? 1 : 0,
            'jkm_time' => isset($param['jkm_time']) ? $param['jkm_time'] : null,
            'jkm_mzt' => isset($param['jkm_mzt']) ? $param['jkm_mzt'] : '',
            'jkm_get' => (isset($param['jkm_mzt']) && $param['jkm_mzt'] != '' ) ? 1 : 0,
            'hsjc_time' => isset($param['hsjc_time']) ? $param['hsjc_time'] : null,
            'hsjc_result' => isset($param['hsjc_result']) ? $param['hsjc_result'] : '',
            'hsjc_jcjg' => isset($param['hsjc_jcjg']) ? $param['hsjc_jcjg'] : '',
            'hsjc_get' => (isset($param['hsjc_result']) && $param['hsjc_result'] != '' ) ? 1 : 0,
            'xcm_result' => isset($param['xcm_result']) ? $param['xcm_result'] : '',

        ];


        try {
            $gateDeclare = $this->dao->save($data);
            // SwooleTaskService::place()->taskType('place')->data(['action'=>'placeDeclareTaskService','param'=> ['real_name' => $userInfo['real_name'], 'id_card' => $userInfo['id_card'], 'place_declare_id'=> $place_declare->id, 'sign'=> $sign]])->push();

            return ['status' => 1, 'msg' => '操作成功', 'data'=> ['sign'=>$data['sign'],'ryxx_result'=>$ryxx_result,'ryxx_cc'=>$ryxx_cc]];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
        
    }

    // 获取场所码的sign信息
    public function resultWholeService($param)
    {
        $user = app()->make(UserDao::class)->get(['csmsb_sign' => $param['sign']]);
        if($user == null) {
            return ['status' => 0, 'msg' => 'sign过期不存在'];
        }
        // 行程卡
        if($user['xcm_gettime'] == null || (time() - strtotime($user['xcm_gettime']) > 86400)) { // 获取超过24小时
            $user['xcm_result'] = 0;
        }

        $data = [
            'real_name'=> $user['real_name'],
            'id_card'=> $user['id_card'],
            'phone'=> $user['phone'],
            'vaccination'=> $user['vaccination'],
            'vaccination_date'=> $user['vaccination_date'],
            'vaccination_times'=> $user['vaccination_times'],
            'xgymjz_get'=> 1,
            'jkm_time'=> $user['jkm_time'],
            'jkm_mzt'=> $user['jkm_mzt'],
            'jkm_get'=> 1,
            'hsjc_time'=> $user['hsjc_time'],
            'hsjc_jcjg'=> $user['hsjc_jcjg'],
            'hsjc_result'=> $user['hsjc_result'],
            'hsjc_get'=> 1,
            'xcm_result'=> $user['xcm_result'],
            'ryxx_result'=> $user['ryxx_result'],
            'ryxx_cc'=> $user['ryxx_cc'],
        ];
        return ['status' => 1, 'msg' => '成功', 'data'=> $data];
    }

    // 
    public function ryxxService($param){
        $id_card = $param['id_card'];
        $ryxx_res = Db::name('yw_rygk')->where('idcard', '=', $id_card)->find();
        if($ryxx_res){
            return ['status' => 1, 'msg' => '成功', 'data'=> ['ryxx_result'=>$ryxx_res['state'],'cc'=>$ryxx_res['cc']]];
        }else{
            return ['status' => 1, 'msg' => '成功', 'data'=> ['ryxx_result'=>'','cc'=>'绿']];
        }
    }


}
