<?php
declare (strict_types=1);

namespace app\services;

use app\dao\OwnDeclareDao;
use app\dao\OwnDeclareOcrDao;
use app\dao\UserDao;
use app\services\SgzxServices;
use app\services\user\UserServices;
use app\services\user\AliyunServices;
use \behavior\IdentityCardTool;
use think\facade\Db;
use \behavior\SsjptTool;
use app\services\FysjServices;

class ProcessDeclareServices
{
    public function verifyIdCardService($data)
    {
        if( isset($data['key']) ){
            $batch_no = intval($data['key']/1 );
            sleep(1*$batch_no);
        }
        try{
            $ownDeclareDao = app()->make(OwnDeclareDao::class);
            if($data['card_type'] == 'id') {
                $user = app()->make(UserDao::class)->get(['id_card'=> $data['id_card'], 'id_verify_result'=> 1]);
                if($user) { //用户已验证，直接验证通过
                    $data['nation'] = $user['nation'];
                    $data['permanent_address'] = $user['permanent_address'];
                    $data['id_verify_result'] = 1;
                    $ownDeclareDao->update($data['id'], ['id_verify_result'=> 1, 'age'=> IdentityCardTool::getAge($data['id_card'])]);
                }else{
                    //人口库查询
                    $rkk_res = app()->make(SgzxServices::class)->rkkService($data['real_name'], $data['id_card']);
                    if($rkk_res['status'] == 1) {
                        $data['nation'] = $rkk_res['data']['nation'];
                        $data['permanent_address'] = $rkk_res['data']['permanent_address'];
                        $data['id_verify_result'] = 1;
                        $ownDeclareDao->update($data['id'], ['id_verify_result'=> 1, 'age'=> IdentityCardTool::getAge($data['id_card'])]);
                    }else{ //人口库验证失败，不保存更新用户信息
                        $data['id_verify_result'] = 2;
                        $ownDeclareDao->update($data['id'], ['id_verify_result'=> 2]);
                    }
                }
            }else{ //其他身份类型不验证
                $data['nation'] = '';
                $data['permanent_address'] = '';
                $data['id_verify_result'] = 1;
                $ownDeclareDao->update($data['id'], ['id_verify_result'=> 1]);
            }
            //来返义申报 修改最近离义申报为已返回
            if($data['declare_type'] == 'come') {
                $leave_declare = $ownDeclareDao->getLastLeaveDeclare(['id_card'=> $data['id_card']]);
                if($leave_declare) {
                    $leave_declare->save(['is_back'=> 1]);
                }
            }
            app()->make(UserServices::class)->updateUserByDeclareService($data);
            return true;
        }catch(\Exception $e) {
            test_log('verifyIdCardService error:'. $e->getMessage());
        }
    }
    public function handleJkm($data)
    {
        if( isset($data['key']) ){
            $batch_no = intval($data['key']/10 );
            sleep(1*$batch_no);
        }
        try{
            //全省健康码基本信息查询
            // $jkm_res = app()->make(FysjServices::class)->getJkmService($data['id_card']);
            $jkm_res = app()->make(FysjServices::class)->getJkmActualService($data['id_card'],$data['phone']);
            $declare_data['jkm_time'] = $jkm_res['jkm_time'];
            $declare_data['jkm_mzt'] = $jkm_res['jkm_mzt'];
            $declare_data['jkm_get'] = 1; // 已获取过
            app()->make(OwnDeclareDao::class)->update($data['id'], $declare_data);
            
            $user = app()->make(UserDao::class)->get(['id_card'=> $data['id_card']]);
            if($user) {
                $user->save(['jkm_time'=> $declare_data['jkm_time'], 'jkm_mzt'=> $declare_data['jkm_mzt'], 'jkm_gettime'=> date('Y-m-d H:i:s')]);
            }
            return true;
        } catch (\Exception $e) {
            test_log('handleJkm error:'. $e->getMessage());
        }
    }

    public function handleShsjc($data)
    {
        if( isset($data['key']) ){
            $batch_no = intval($data['key']/10 );
            sleep(1*$batch_no);
        }
        try{
            $ownDeclareDao = app()->make(OwnDeclareDao::class);
            // 省核酸检测接口
            $shsjc_res = app()->make(FysjServices::class)->getShsjcService($data['real_name'], $data['id_card']);
            
            $declare_data = [];
            $declare_data['hsjc_time'] = $shsjc_res['hsjc_time'];
            $declare_data['hsjc_result'] = $shsjc_res['hsjc_result'];
            $declare_data['hsjc_jcjg'] = $shsjc_res['hsjc_jcjg'];
            $declare_data['hsjc_get'] = 1; // 已获取过
            $ownDeclareDao->update($data['id'], $declare_data);
            
            $user = app()->make(UserDao::class)->get(['id_card'=> $data['id_card']]);
            if($user) {
                $user->save(['hsjc_time'=> $declare_data['hsjc_time'], 'hsjc_result'=> $declare_data['hsjc_result'], 'hsjc_jcjg'=> $declare_data['hsjc_jcjg']]);
            }
            return true;
        } catch (\Exception $e){
            test_log('handleHsjc error:'. $e->getMessage());
        }
    }

    // public function handleYwhsjc($data)
    // {
    //     if( isset($data['key']) ){
    //         $batch_no = intval($data['key']/10 );
    //         sleep(1*$batch_no);
    //     }
    //     try{
    //         $sgzxServices = app()->make(SgzxServices::class);
    //         $ownDeclareDao = app()->make(OwnDeclareDao::class);
    //         // 省核酸检测接口
    //         $hsjc_res = $sgzxServices->ywhsjcjk($data['real_name'], $data['id_card']);
            
    //         $hsjc_result = '查询失败';
    //         $hsjc_time = null;
    //         $hsjc_jcjg = '';
    //         if($hsjc_res['status'] == 1) {
    //             if(isset($hsjc_res['data']) && count($hsjc_res['data']) > 0) {
    //                 foreach($hsjc_res['data'] as $n) {
    //                     if(strstr($n['Exam_Name'],'RNA') || strstr($n['Exam_Name'],'核酸')){
    //                         if($n['Exam_Time'] > $hsjc_time) {
    //                             $hsjc_result = $n['Exam_Result'];
    //                             $hsjc_time = Date('Y-m-d H:i:s',strtotime($n['Exam_Time']));
    //                             $hsjc_jcjg = $n['Exam_Org'];
    //                         }
    //                     }
    //                 }
    //             }else{
    //                 $hsjc_result = '义乌查询失败-数据为空';
    //                 $hsjc_time = null;
    //                 $hsjc_jcjg = '';
    //             }
    //         }

    //         $declare_data = [];
    //         $declare_data['hsjc_time'] = $hsjc_time;
    //         $declare_data['hsjc_result'] = $hsjc_result;
    //         $declare_data['hsjc_jcjg'] = $hsjc_jcjg;
    //         $declare_data['hsjc_get'] = 2; // 已获取过
    //         $ownDeclareDao->update($data['id'], $declare_data);
            
    //         $user = app()->make(UserDao::class)->get(['id_card'=> $data['id_card']]);
    //         if($user) {
    //             $user->save(['hsjc_time'=> $declare_data['hsjc_time'], 'hsjc_result'=> $declare_data['hsjc_result'], 'hsjc_jcjg'=> $declare_data['hsjc_jcjg']]);
    //         }
    //         return true;
    //     } catch (\Exception $e){
    //         test_log('handleHsjc error:'. $e->getMessage());
    //     }
    // }

    public function handleXgymyfjzService($data)
    {
        if( isset($data['key']) ){
            $batch_no = intval($data['key']/10 );
            sleep(1*$batch_no);
        }
        try{
            $declare_data = [];
            $user = app()->make(UserDao::class)->get(['id_card'=> $data['id_card'], 'card_type'=> 'id']);
            if($user && $user['vaccination_times'] == 3) {
                $declare_data['vaccination'] = $user['vaccination'];
                $declare_data['vaccination_date'] = $user['vaccination_date'];
                $declare_data['vaccination_times'] = $user['vaccination_times'];
            }else{
                //省新冠疫苗预防接种信息查询
                $ymjz = app()->make(FysjServices::class)->getXgymyfjzService($data['id_card']);
                $declare_data['vaccination'] = $ymjz['vaccination'];
                $declare_data['vaccination_date'] = $ymjz['vaccination_date'];
                $declare_data['vaccination_times'] = $ymjz['vaccination_times'];
                if($ymjz['vaccination'] == 1) {
                    app()->make(UserDao::class)->update(['id_card'=> $data['id_card']], ['vaccination'=> 1, 'vaccination_date'=> $declare_data['vaccination_date'], 'vaccination_times'=> $declare_data['vaccination_times']]);
                }
            }

            $declare_data['xgymjz_get'] = 1;
            app()->make(OwnDeclareDao::class)->update($data['id'], $declare_data);
            return true;
        } catch (\Exception $e){
            test_log('handleDeclareService error:'. $e->getMessage());
        }
    }

    public function ocrDeclareService($data)
    {
        if( isset($data['key']) ){
            $batch_no = intval($data['key']/1 );
            sleep(1*$batch_no);
        }
        app()->make(AliyunServices::class)->imgOcrGeneral(['declare_id'=> $data['id'], 'id_card'=> $data['id_card'], 'travel_img'=> $data['travel_img']]);
    }
    
    public function handleUnmatchOcrService($data)
    {
        try{
            app()->make(AliyunServices::class)->matchOcrContent($data['declare_id'], $data['travel_content']);
        }catch(\Exception $e) {
            test_log('handleUnmatchOrcService error:'. $e->getMessage());
        }
    }

    // 配置中高风险地区后设置系统预警申报记录
    public function setDeclareSysWarning($param)
    {
        $where = [];
        $where[] = ['create_time', '>=', strtotime('-14 day')];
        $where[] = ['declare_type', '=', 'come'];
        $where[] = ['is_warning', '=', 0];
        if(strstr($param['province'], '香港')) { //travel_route中是中国香港，province中是香港特别行政区
            $where[] = ['travel_route', 'like', '%香港%'];
        }else if(strstr($param['province'], '澳门')) {
            $where[] = ['travel_route', 'like', '%澳门%'];
        }else{
            $where[] = ['travel_route', 'like', '%'. $param['city'] .'%'];
        }

        if($param['type'] == 'add') {
            $where[] = ['create_time', '>=', strtotime($param['start_date'])];
            app()->make(OwnDeclareDao::class)->update($where, ['sys_warning'=> 1, 'is_warning'=> 1]);
        }else if($param['type'] == 'update') {
            $where[] = ['create_time', '>=', strtotime($param['new_start_date'])];
            $where[] = ['create_time', '<=', strtotime($param['old_start_date'])];
            app()->make(OwnDeclareDao::class)->update($where, ['sys_warning'=> 1, 'is_warning'=> 1]);
        }
        return true;
    }

}
