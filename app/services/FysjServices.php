<?php

namespace app\services;

use think\facade\Db;
use \behavior\SsjptTool;
use \behavior\SgzxTool;
use behavior\SsjptActualTool;

//防疫数据服务
class FysjServices
{
    public function getXgymyfjzService($id_card)
    {
        try{
            $vaccination = 0;
            $vaccination_date = null;
            $vaccination_times = 0;
            //省新冠疫苗预防接种信息查询
            $ssjptTool = new SsjptTool();
            $xgym_res =  $ssjptTool->skxgymyfjzxxcx($id_card);
            if($xgym_res['status'] == 1) {
                foreach($xgym_res['data'] as $n) {
                    if($n['vaccinationTime'] > $vaccination_times) {
                        $vaccination = 1;
                        $vaccination_date = $n['vaccinationDateTime'];
                        $vaccination_times = $n['vaccinationTime'];
                    }
                }
            }
            return ['vaccination'=> $vaccination, 'vaccination_date'=> $vaccination_date, 'vaccination_times'=> $vaccination_times];
        } catch (\Exception $e){
            test_log('FysjTaskServices getXgymyfjzService error:'. $e->getMessage());
            return [];
        }
    }

    // 省核酸检测接口
    public function getShsjcService($real_name, $id_card)
    {
        try{
            $hsjc_result = '查询失败';
            $hsjc_time = null;
            $receive_time = null;
            $hsjc_jcjg = '';
            $ssjptTool = new SsjptTool();
            $hsjc_res =  $ssjptTool->skhsjcjk($real_name, $id_card);
            if($hsjc_res['status'] == 1) {
                if(isset($hsjc_res['data']['data'])){
                    // if(strstr($hsjc_res['data']['data']['examinaim'], 'RNA') || strstr($hsjc_res['data']['data']['examinaim'], '核酸')){
                        if(isset($hsjc_res['data']['data']['receivetime'])){
                            $receive_time = $hsjc_res['data']['data']['receivetime'];
                        }else{
                            $receive_time = $hsjc_res['data']['data']['checktime'];
                        }
                        // 2022.05.01 // 重新取检测时间
                        $hsjc_time = $hsjc_res['data']['data']['checktime'];
                        $hsjc_result = $hsjc_res['data']['data']['result'];
                        $hsjc_jcjg = $hsjc_res['data']['data']['jgmc'];
                    // }else{
                    //     // 省库有一定小概率会返回不是核酸的结果
                    //     $hsjc_result = '查询成功-数据为空';
                    //     $hsjc_time = null;
                    //     $hsjc_jcjg = '';
                    // }
                }else{
                    $hsjc_result = '查询成功-数据为空';
                    $hsjc_time = null;
                    $hsjc_jcjg = '';
                }
            }
            return ['hsjc_result'=> $hsjc_result, 'hsjc_time'=> $hsjc_time, 'hsjc_jcjg'=> $hsjc_jcjg, 'receive_time'=>$receive_time];
        } catch (\Exception $e){
            test_log('FysjTaskServices getShsjcService error:'. $e->getMessage());
            //test_log($hsjc_res['data']['data']);
            return [];
        }
    }

    // 义乌核酸检测接口
    // public function getYwhsjcService($real_name, $id_card)
    // {
    //     try{
    //         $hsjc_result = '查询失败';
    //         $hsjc_time = null;
    //         $hsjc_jcjg = '';
    //         $szxTool = new SgzxTool();
    //         // $hsjc_res = $szxTool->getByywhsjcjk($real_name, $id_card);
    //         $hsjc_res = $szxTool->getByshsjcjk($real_name, $id_card);
    //         if($hsjc_res['status'] == 1) {
    //             if(isset($hsjc_res['data']) && count($hsjc_res['data']) > 0){
    //                 foreach($hsjc_res['data'] as $n) {
    //                     if(strstr($n['Exam_Name'], 'RNA') || strstr($n['Exam_Name'], '核酸')){
    //                         if($n['Exam_Time'] > $hsjc_time) {
    //                             $hsjc_result = $n['Exam_Result'];
    //                             $hsjc_time = Date('Y-m-d H:i:s',strtotime($n['Exam_Time']));
    //                             $hsjc_jcjg = $n['Exam_Org'];
    //                         }
    //                     }
    //                 }
    //             }else{
    //                 $hsjc_result = '查询成功-数据为空';
    //             }
    //         }
    //         return ['hsjc_result'=> $hsjc_result, 'hsjc_time'=> $hsjc_time, 'hsjc_jcjg'=> $hsjc_jcjg];
    //     } catch (\Exception $e){
    //         test_log('FysjTaskServices getYwhsjcService error:'. $e->getMessage());
    //     }
    // }

    public function getJkmService($id_card)
    {
        try{
            $jkm_time = null;
            $jkm_mzt = '查询失败';
            //全省健康码基本信息查询
            $ssjptTool = new SsjptTool();
            $jkm_res =  $ssjptTool->skIdcardToJkm($id_card);
            if($jkm_res['status'] == 1) {
                $jkm_time = isset($jkm_res['data']['zjgxsj']) ? date('Y-m-d H:i:s', strtotime($jkm_res['data']['zjgxsj'])) : null;
                $jkm_mzt = isset($jkm_res['data']['mzt']) ? $jkm_res['data']['mzt'] : '查询成功-数据为空' ;

                // 2022.01.31 按照码发放地：金华市，更新时间为近10天的条件来判断为有效的红黄码，其他的红黄码都设置为绿码
                if( isset($jkm_res['data']['mffd']) && $jkm_res['data']['mffd'] == '金华市') {
                    if($jkm_mzt == '黄码') {
                        // 查看，是不是超过10天，如果是目前先改为绿色
                        if( time() - strtotime($jkm_time) > 10*24*3600) {
                            system_error_log(__METHOD__,$id_card.':'.$jkm_mzt.'--超过10天-->绿码',$jkm_mzt.'原因:'.$jkm_res['data']['hmcmyy'].',健康码时间:'.$jkm_time);
                            $jkm_mzt = '绿码';
                        }
                    }else if($jkm_mzt == '红码') {
                        // 查看，是不是超过10天，如果是目前先改为绿色
                        if( time() - strtotime($jkm_time) > 10*24*3600) {
                            system_error_log(__METHOD__,$id_card.':'.$jkm_mzt.'--超过10天-->绿码',$jkm_mzt.'原因:'.$jkm_res['data']['hmcmyy'].',健康码时间:'.$jkm_time);
                            $jkm_mzt = '绿码';
                        }
                    }
                    // 如果是命中表单，也先转绿色
                    // "hmcmyy": "命中表单问题：当前所在地命中疫区",
                    if(isset($jkm_res['data']['hmcmyy']) && strstr($jkm_res['data']['hmcmyy'],'命中表单问题') ){
                        system_error_log(__METHOD__,$id_card.':'.$jkm_mzt.'--表单命中-->绿码',$jkm_mzt.'原因:'.$jkm_res['data']['hmcmyy'].',健康码时间:'.$jkm_time);
                        $jkm_mzt = '绿码';
                    }
                
                }else{
                    // // 其他地区
                    // if($jkm_mzt == '黄码') {
                    //     system_error_log(__METHOD__,$id_card.':'.$jkm_mzt.'--其他地区-->绿码',$jkm_mzt.'原因:'.$jkm_res['data']['hmcmyy'].',健康码时间:'.$jkm_time);
                    // }else if($jkm_mzt == '红码') {
                    //     system_error_log(__METHOD__,$id_card.':'.$jkm_mzt.'--其他地区-->绿码',$jkm_mzt.'原因:'.$jkm_res['data']['hmcmyy'].',健康码时间:'.$jkm_time);
                    // }
                    // $jkm_mzt = '绿码';
                }
            }
            return ['jkm_time'=> $jkm_time, 'jkm_mzt'=> $jkm_mzt];
        } catch (\Exception $e){
            test_log('FysjTaskServices getJkmService error:'. $e->getMessage());
            return [];
        }
    }
    // 获取实时的健康码服务
    public function getJkmActualService($id_card,$phone)
    {
        try{
            $jkm_time = null;
            $jkm_mzt = '查询失败';
            //全省健康码基本信息查询
            $ssjptTool = new SsjptActualTool();
            $jkm_res =  $ssjptTool->skIdcardAndPhoneToJkm($id_card,$phone);
            if($jkm_res['status'] == 1) {
                $jkm_time = isset($jkm_res['data']['zjgxsj']) ? date('Y-m-d H:i:s', strtotime($jkm_res['data']['zjgxsj'])) : null;
                $jkm_mzt = $this->_jkmss_to_mzt($jkm_res['data']);
            }else{
                // 去老接口查
                return $this->getJkmService($id_card);
            }
            return ['jkm_time'=> $jkm_time, 'jkm_mzt'=> $jkm_mzt];
        } catch (\Exception $e){
            test_log('FysjTaskServices getJkmActualService error:'. $e->getMessage());
            return [];
        }
    }
    //人员管控信息
    public function getRyxxService($id_card)
    {
        //$ryxx_res = app()->make(ZzsbViewServices::class)->csm_ryxx($id_card);
        $ryxx_res = Db::name('csm_ryxx')->where('idcard', '=', $id_card)->find();
        try {
            if($ryxx_res == null) {
                $ryxx_type = ''; // #无需管控
            }else{
                $ryxx_type = $ryxx_res['type'];
            }
            return ['ryxx_result' => $ryxx_type];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }
    public function getRyxxServiceV2($id_card,$user=[])
    {
        //$ryxx_res = app()->make(ZzsbViewServices::class)->csm_ryxx($id_card);
        $ryxx_res = Db::name('yw_rygk')->where('idcard', '=', $id_card)->find();
        try {
            $ryxx_cc = '绿';
            if($ryxx_res == null) {
                if(isset($user['ryxx_result']) && $user['ryxx_result'] != ''){ // #无需管控
                    // 可能yw_rygk 正在同步，为了保证正确，去yw_rygk2表再查询一次
                    $ryxx_res2 = Db::name('yw_rygk2')->where('idcard', '=', $id_card)->find();
                    if($ryxx_res2 == null){
                        $ryxx_type = ''; // #无需管控
                    }else{
                        $ryxx_type = $ryxx_res2['state'];
                        $ryxx_cc = $ryxx_res2['cc'];
                    }
                }else{
                    $ryxx_type = ''; // #无需管控
                }
            }else{
                $ryxx_type = $ryxx_res['state'];
                $ryxx_cc = $ryxx_res['cc'];
            }
            if($ryxx_type == '' && $user['xcm_result'] == 2 &&  (time() - strtotime($user['xcm_gettime']) < 24*3600)  ){
                // #无需管控
                $ryxx_type = '行程卡带星人员';
                $ryxx_cc = '黄';
            }
            return ['status' => 1, 'msg'=> '获取成功', 'ryxx_result' => $ryxx_type,'ryxx_cc'=>$ryxx_cc,'rygk'=>$ryxx_res];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }
    //查询企业信息
    public function enterpriseInfo($credit_code)
    {
        $szxTool = new SgzxTool();
        return $szxTool->enterpriseInfo($credit_code);
    }

    private function _jkmss_to_mzt($jkmss){
        $jkm_mzt = '';
        if($jkmss){
            if(isset($jkmss['level'])){
                $jkmss['level'] = strtolower($jkmss['level']);
                switch($jkmss['level']){
                    case 'green':
                        $jkm_mzt = '绿码';
                        break;
                    case 'yellow':
                        $jkm_mzt = '黄码';
                        break;
                    case 'red':
                        $jkm_mzt = '红码';
                        break;
                    default :
                        $jkm_mzt = '未知';
                        test_log('未知的码level:'.$jkmss['level']);
                        break;
                }
                return $jkm_mzt;
            }else{
                return '查询失败';
            }
        }else{
            return '查询成功-数据为空';
        }
    }
}
