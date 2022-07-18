<?php
declare (strict_types=1);

namespace app\services\admin;

use app\services\user\BaseServices;
use app\dao\PlaceDeclareDao;
use app\dao\slave\PlaceDeclareSlaveDao;
use app\dao\PlaceDeclareLogDao;
use app\dao\UserHsjcLogDao;
use think\facade\Db;

class PlaceDeclareServices extends BaseServices
{
    public $list_type = [
        'index' => '场所码扫码记录',
        'abnormal' => '行程及状态异常清册记录',
        'code' => '红黄码人员清册',
        'mail' => '国际邮件收件扫码记录',
        'high_speed' => '高速入口扫码记录',
        'hs_test' => '核酸检测登记扫码记录',
    ];

    public function __construct(PlaceDeclareDao $dao)
    {
        $this->dao = $dao;
    }

    public function indexService($param, $admin)
    {
        $this->savePlaceDeclareLog($param, $admin, '查询');
        
        return app()->make(PlaceDeclareSlaveDao::class)->getList($param);
    }

    public function abnormalService($param)
    {
        return app()->make(PlaceDeclareSlaveDao::class)->getAbnormalList($param);
    }

    public function codeService($param)
    {
        return app()->make(PlaceDeclareSlaveDao::class)->getCodeList($param);
    }

    public function readService($id)
    {
        $data = $this->dao->get($id);
        if($data) {
            return ['status' => 1, 'msg'=> '成功', 'data'=> $data];
        }else{
            return ['status' => 0, 'msg'=> '不存在'];
        }
    }
    
    public function deleteService($id)
    {
        $own_declare = $this->dao->get($id);
        if($own_declare == null) {
            return ['status' => 0, 'msg' => '申报不存在'];
        }
        try {
            $this->dao->softDelete($id);
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }

    public function savePlaceDeclareLog($param, $admin, $action)
    {
        $search = [];
        if(isset($param['real_name']) && $param['real_name'] != '') {
            $search[] = '姓名：'. $param['real_name'];
        }
        if(isset($param['id_card']) && $param['id_card'] != '') {
            $search[] = '身份证号：'. $param['id_card'];
        }
        if(isset($param['phone']) && $param['phone'] != '') {
            $search[] = '手机号：'. $param['phone'];
        }
        if( isset($param['yw_street_id']) && $param['yw_street_id']  > 0) {
            $search[] = '义乌街道：'. $param['yw_street_id'];
        }
        if( isset($param['place_name']) && $param['place_name'] != '') {
            $search[] = '场所名称：'. $param['place_name'];
        }
        if( isset($param['vaccination']) && $param['vaccination'] != '') {
            $search[] = '是否接种疫苗：'. $param['vaccination'];
        }
        if( isset($param['jkm_mzt']) && $param['jkm_mzt'] != '') {
            $search[] = '健康码状态：'. $param['jkm_mzt'];
        }
        if( isset($param['hsjc_result']) && $param['hsjc_result'] != '') {
            $search[] = '核酸结果：'. $param['hsjc_result'];
        }
        if( isset($param['xcm_result']) && $param['xcm_result'] != '') {
            $search[] = '行程码查询：'. $param['xcm_result'];
        }
        if( isset($param['ryxx_result']) && $param['ryxx_result'] != '') {
            $search[] = '管控状态：'. $param['ryxx_result'];
        }
        if( isset($param['ryxx_result']) && $param['ryxx_result'] != '') {
            $search[] = '管控状态：'. $param['ryxx_result'];
        }
        if( isset($param['start_date']) && $param['start_date'] != '' ) {
            $search[] = '扫码日期：'. $param['start_date'] .'-'. $param['end_date'];
        }
        if( isset($param['start_datetime']) && $param['start_datetime'] != '' ) {
            $search[] = '扫码时间段：'. $param['start_datetime'] .'-'. $param['end_datetime'];
        }
        if( isset($param['link_man']) && $param['link_man'] != '') {
            $search[] = '联络人：'. $param['link_man'];
        }
        if( isset($param['place_code']) && $param['place_code'] != '') {
            $search[] = '场所码：'. $param['place_code'];
        }
        if( isset($param['node_id']) && $param['node_id'] != '') {
            $search[] = '所在节点：'. $param['node_id'];
        }
        if(count($search) > 0) {
            $data = [
                'create_date' => date('Y-m-d'),
                'action' => $action,
                'admin_id' => $admin['id'],
                'admin_name' => $admin['real_name'],
                'admin_phone' => $admin['phone'],
                'ip' => app()->request->ip(),
                'condition' => implode(',', $search),
                'menu' => $this->list_type[$param['list_type']],
            ];
            try {
                app()->make(PlaceDeclareLogDao::class)->save($data);
                return true;
            } catch (\Exception $e) {
                return false;
            }
        }
        return true;
    }

    public function hsjcResultLogService($id)
    {
        $place_declare = $this->dao->get($id);
        $userHsjcLogDao = app()->make(UserHsjcLogDao::class);
        $hsjc_list = $userHsjcLogDao->getSixRecentList($place_declare['id_card']);
        if(count($hsjc_list) <= 3) {
            $skhl_list = Db::connect('mysql_shengku')->table('dsc_jh_dm_037_pt_patientinfo_sc_delta_new')->where('sfzh','=',$place_declare['id_card'])->order('checktime', 'desc')->limit(6)->select()->toArray();
            foreach($skhl_list as $v) {
                $has = 0;
                foreach($hsjc_list as $n) {
                    if($n['hsjc_time'] == $v['checktime']) {
                        $has = 1;
                    }
                }
                if($has == 1) {
                    continue;
                }
                $hsjc_log_data = [
                    'card_type' => 'id',
                    'id_card' => $place_declare['id_card'],
                    'real_name' => $place_declare['real_name'],
                    'hsjc_time' => $v['checktime'],
                    'hsjc_result' => $v['result'],
                    'hsjc_jcjg' => $v['jgmc'],
                    'hsjc_date' => date('Y-m-d', strtotime($v['checktime'])),
                    'create_datetime' => date('Y-m-d H:i:s'),
                ];
                $userHsjcLogDao->save($hsjc_log_data);
            }
            $hsjc_list = $userHsjcLogDao->getSixRecentList($place_declare['id_card']);
        }
        return ['status'=> 1, 'msg'=> '成功', 'data'=> $hsjc_list];
    }
}
