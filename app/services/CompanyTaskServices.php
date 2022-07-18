<?php

namespace app\services;

use app\dao\CompanyDao;
use app\dao\CompanyStaffDao;
use app\dao\UserDao;
use app\dao\MessageRecordDao;
use app\dao\slave\CompanyStaffSlaveDao;
use Curl\Curl;
use think\facade\Config;
use app\services\FysjServices;
use app\services\MessageServices;
use think\facade\Db;
use \behavior\SmsTool;

//企业码异步任务服务
class CompanyTaskServices
{

    //员工加入企业时空数据用户第一次获取防疫数据
    public function setUserFysjService($param)
    {
        try{
            $user = app()->make(UserDao::class)->get($param['id']);
            $user_data = [];
            $fysjService = app()->make(FysjServices::class);
            if($user['jkm_gettime'] == null) {
                // $jkm_res = $fysjService->getJkmService($user['id_card']);
                $jkm_res = $fysjService->getJkmActualService($user['id_card'],$user['phone']);
                $user_data['jkm_time'] = $jkm_res['jkm_time'];
                $user_data['jkm_mzt'] = $jkm_res['jkm_mzt'];
                $user_data['jkm_gettime'] = date('Y-m-d H:i:s');
            }
            if($user['hsjc_gettime'] == null) {
                $shsjc_res = $fysjService->getShsjcService($user['real_name'], $user['id_card']);
                $user_data['hsjc_time'] = date('Y-m-d H:i:s', strtotime($shsjc_res['hsjc_time']));
                $user_data['hsjc_result'] = $shsjc_res['hsjc_result'];
                $user_data['hsjc_jcjg'] = $shsjc_res['hsjc_jcjg'];
                $user_data['hsjc_gettime'] = date('Y-m-d H:i:s');
                if($user_data['hsjc_time'] != $user['hsjc_time']) { //先前一次核酸检测时间
                    $user_data['hsjc_previous_time'] = $user['hsjc_time'];
                }
            }
            if($user['vaccination_gettime'] == null) {
                $ymjz = $fysjService->getXgymyfjzService($user['id_card']);
                if($ymjz['vaccination'] == 1) {
                    $user_data['vaccination'] = $ymjz['vaccination'];
                    $user_data['vaccination_date'] = $ymjz['vaccination_date'];
                    $user_data['vaccination_times'] = $ymjz['vaccination_times'];
                }
                $user_data['vaccination_gettime'] = date('Y-m-d H:i:s');
            }
            if($user['ryxx_gettime'] == null) {
                $ryxx_res = $fysjService->getRyxxService($user['id_card']);
                $user_data['ryxx_result'] = $ryxx_res['ryxx_result'];
                $user_data['ryxx_gettime'] = date('Y-m-d H:i:s');
            }

            $user->save($user_data);
        }catch(\Exception $e) {
            test_log('setUserFysjService error:'. $e->getMessage());
        }
    }

    public function taskCompanyCheckFrequencyCountByUserId($param){
        try{
            $user_id = $param['user_id'];
            $data = Db::name('company_staff')->where('user_id','=',$user_id)->select();
            foreach($data as $key => $value){
                $this->taskCompanyCheckFrequencyCountByCompanyId(['company_id'=>$value['company_id']]);
            }
        }catch(\Exception $e) {
            test_log('taskCompanyCheckFrequencyCountByUserId error:'. $e->getMessage());
        }
    }


    // 重新计算某个公司的各个频次的人数
    public function taskCompanyCheckFrequencyCountByCompanyId($param){
        try{
            $company_id = $param['company_id'];
            $data = Db::name('company_staff')->alias('cs')
                    ->field('
                        SUM(if(u.check_frequency=2,1,0)) as user_2_count,
                        SUM(if(u.check_frequency=7,1,0)) as user_7_count,
                        SUM(if(u.check_frequency=14,1,0)) as user_14_count,
                        SUM(if(u.check_frequency=28,1,0)) as user_28_count,
                        SUM(if(u.check_frequency=70,1,0)) as user_70_count,
                        count(cs.id) as user_count
                    ')
                    ->leftJoin('user u', 'u.id=cs.user_id')
                    ->where('cs.company_id','=',$company_id)
                    ->select();
            
            Db::name('company')->where('id','=',$company_id)->update(
                [
                    'user_2_count' => $data[0]['user_2_count'],
                    'user_7_count' => $data[0]['user_7_count'],
                    'user_14_count' => $data[0]['user_14_count'],
                    'user_28_count' => $data[0]['user_28_count'],
                    'user_70_count' => $data[0]['user_70_count'],
                    'user_count' => $data[0]['user_count'],
                ]
            );
        }catch(\Exception $e) {
            test_log('taskCompanyCheckFrequencyCountByCompanyId error:'. $e->getMessage());
        }
    }

    public function taskCompanyHsjcCount($param)
    {
        try{
            $companyDao = app()->make(CompanyDao::class);
            $CompanyStaffSlaveDao = app()->make(CompanyStaffSlaveDao::class);
            $index = 1;
            $from_id = 0;
            while($index == 1) {
                $list = Db::connect('mysql_slave')->name('company')
                            ->field('id,user_count,two_count as old_two_count,seven_count as old_seven_count,seventy_count as old_seventy_count')
                            ->where('id','>',$from_id)
                            ->limit(200)->select()->toArray();
                if(count($list) == 0) {
                    $index = 0;
                }else{
                    $num = count($list);
                    foreach($list as $k => $v) {
                        //$user_count[$k] = $companyStaffDao->companyStaffCount($v['id']);
                        if($v['user_count'] == 0) {
                            continue;
                        }
                        $res[$k] = $CompanyStaffSlaveDao->getCompanyHsjcCount($v['id']);
                        $two_count[$k] = $res[$k][0]['two_count'];
                        $seven_count[$k] = $res[$k][0]['seven_count'];
                        $seventy_count[$k] = $res[$k][0]['seventy_count'];
                        if($two_count[$k] != $v['old_two_count'] || $seven_count[$k] != $v['old_seven_count'] || $seventy_count[$k] != $v['old_seventy_count']){
                            // 其中有一个不同就重新计算
                            $two_rate[$k] = bcmul(bcdiv($two_count[$k], $v['user_count'], 4), 100, 2) .'%';
                            $seven_rate[$k] = bcmul(bcdiv($seven_count[$k], $v['user_count'], 4), 100, 2) .'%';
                            $seventy_rate[$k] = bcmul(bcdiv($seventy_count[$k], $v['user_count'], 4), 100, 2) .'%';
                            $companyDao->update($v['id'], [ 'two_count'=> $two_count[$k], 'two_rate'=> $two_rate[$k], 'seven_count'=> $seven_count[$k], 'seven_rate'=> $seven_rate[$k], 'seventy_count'=> $seventy_count[$k], 'seventy_rate'=> $seventy_rate[$k]]);
                        }

                        if($num == $k + 1){
                            // test_log('本次最后一个id为:'.$v['id']);
                            $from_id = $v['id']; // 下一批的开始id
                        }

                    }
                }
            }
        }catch(\Exception $e) {
            test_log('taskCompanyHsjcCount error:'. $e->getMessage());
        }
    }

    public function taskSendSmsToLinkTwoDaysHsjc($param)
    {
        try{
            $companyDao = app()->make(CompanyDao::class);
            $messageService = app()->make(MessageServices::class);
            $list = $companyDao->twoDaysHsjcByStreet();
            foreach($list as $v) {
                $street_company = $companyDao->get(['credit_code'=> '镇街-'. $v['yw_street']]);
                if($street_company) {
                    //发送短信
                    $message_data = [
                        'receive_id'=> $street_company['link_id'],
                        'receive'=> $street_company['link_name'],
                        'phone'=> $street_company['link_phone'],
                        'source_id'=> 0,
                    ];
                    $param_data = [
                        'real_name'=> $street_company['link_name'],
                        'yw_street'=> $v['yw_street'],
                    ];
                    $messageService->asyncMessage('template005', $message_data, $param_data);
                }
            }
        }catch(\Exception $e) {
            test_log('taskSendSmsToLinkTwoDaysHsjc error:'. $e->getMessage());
        }
    }

    //发送短信给不合格企业联络员
    public function sendSmsToUnqualifiedCompany($param)
    {
        try{
            $messageService = app()->make(MessageServices::class);
            $list = app()->make(CompanyDao::class)->unqualifiedCompanyListToSendSms($param);
            foreach($list as $v) {
                if($v['seven_count'] == 0) { //至少要检1人
                    $seven_lack = 1;
                }else{
                    $seven_lack = bcsub(bcdiv((string)$v['user_count'], '10'), (string)$v['seven_count']); //7天缺检人数
                }
                //发送短信
                $message_data = [
                    'receive_id'=> $v['link_id'],
                    'receive'=> $v['link_name'],
                    'phone'=> $v['link_phone'],
                    'source_id'=> 0,
                ];
                $param_data = [
                    'company_name'=> $v['name'],
                    'seven_lack'=> $seven_lack,
                ];
                $messageService->syncMessage('template006', $message_data, $param_data);
            }
        }catch(\Exception $e) {
            test_log('sendSmsToUnqualifiedCompany error:'. $e->getMessage());
        }
    }

    // 
    public function taskUpdateCompanyStaffReceiveTime($param){
        // test_log('taskUpdateCompanyStaffReceiveTime');
        try{
            // 先从从库中取出 从id 0 开始
            $companyStaffDao = app()->make(CompanyStaffDao::class);
            // $userDao = app()->make(UserDao::class);
            $index = 1;
            $from_id = 0;
            while($index == 1) {
                $pageSize = 200;
                // $list = $companyStaffDao->getNeedUpdateHsjcFromLocalYwData($pageSize);
                $list = Db::connect('mysql_slave')->name('company_staff')->alias('cs')
                        ->field('cs.id,cs.user_id,cs.user_idcard as id_card,cs.receive_time')
                        // ->leftJoin('user u', 'u.id=cs.user_id')
                        ->where('cs.id','>',$from_id)
                        ->order('cs.id','asc')
                        ->limit($pageSize)->select();
                if(count($list) == 0) {
                    $index = 0;
                }else{
                    // $ids_arr = [];
                    $num = count($list);
                    foreach($list as $k => $v) {
                        $save[$k] = [];
                        // array_push($ids_arr,$v['id']);
                        // 从省库中获取最新的那条
                        $has_db1[$k] = Db::connect('mysql_shengku')->table('dsc_jh_dm_037_pt_patient_sampinfo_delta')->where('id_card','=',$v['id_card'])->order('sampling_time','desc')->find();
                        if($has_db1[$k]){
                            $save[$k]['receive_time'] = $has_db1[$k]['sampling_time'];
                        }
                        $has_db2[$k] = Db::connect('mysql_shengku')->table('frryk_sgxg_labreport')->where('KH','=',$v['id_card'])->order('SEND_TIME','desc')->find();
                        if($has_db2[$k]){
                            if(isset($save[$k]['receive_time'])){
                                if(strtotime($has_db2[$k]['SEND_TIME']) > strtotime($save[$k]['receive_time'])){
                                    $save[$k]['receive_time'] = $has_db2[$k]['SEND_TIME'];
                                }
                            }else{
                                $save[$k]['receive_time'] = $has_db2[$k]['SEND_TIME'];
                            }
                        }
                        if( isset($save[$k]['receive_time'])  && $v['receive_time'] != $save[$k]['receive_time'] && strtotime($save[$k]['receive_time']) > strtotime((string)$v['receive_time']) ){
                            // 需要进行更新
                            $companyStaffDao->update($v['id'],$save[$k]);
                        }

                        if($num == $k + 1){
                            // test_log('本次最后一个id为:'.$v['id']);
                            $from_id = $v['id']; // 下一批的开始id
                        }
                    }
                    // // 更新
                    // Db::name('company_staff')->where('id','in',$ids_arr)->update(['is_update_to_user'=>1]);
                }
            }
        }catch(\Exception $e) {
            test_log('taskUpdateCompanyStaffReceiveTime error:'. $e->getMessage());
        }

    }


    // 更新,老的准备不用了
    public function taskUpdateCompanyStaffHsjc($param)
    {
        try{

            // 先将全部置为0 
            Db::name('company_staff')->where('id','>',0)->update(['is_update_to_user'=>0]);
            sleep(1);

            $userDao = app()->make(UserDao::class);
            $hsjc_gettime = Date('Y-m-d H:i:s');
            $index = 1;
            while($index == 1) {
                $pageSize = 100;
                // $list = $companyStaffDao->getNeedUpdateHsjcFromLocalYwData($pageSize);
                $list = Db::name('company_staff')->alias('cs')
                        ->field('cs.id,cs.user_id,u.id_card,u.hsjc_time as old_hsjc_time')
                        ->leftJoin('user u', 'u.id=cs.user_id')
                        ->where('is_update_to_user','=',0)
                        ->order('cs.id','asc')
                        ->limit($pageSize)->select();
                if(count($list) == 0) {
                    $index = 0;
                }else{
                    $ids_arr = [];
                    foreach($list as $k => $v) {
                        array_push($ids_arr,$v['id']);
                        // 从省库中获取最新的那条
                        $has[$k] = Db::connect('mysql_shengku')->table('dsc_jh_dm_037_pt_patientinfo_sc_delta_new')->where('sfzh','=',$v['id_card'])->order('checktime','desc')->find();
                        if($has[$k]){
                            $data[$k] = [
                                'hsjc_time' => $has[$k]['checktime'] ? date('Y-m-d H:i:s', strtotime($has[$k]['checktime'])) : '1970-01-01 00:00:00',
                                'hsjc_result' => $has[$k]['result'],
                                'hsjc_jcjg' => $has[$k]['jgmc'],
                                // 'hsjc_explain' => '省库回流',
                            ];
                            if($v['old_hsjc_time'] != $data[$k]['hsjc_time'] && strtotime($data[$k]['hsjc_time']) > strtotime($v['old_hsjc_time'])   ){
                                $save[$k] = [];
                                $save[$k]['hsjc_time'] = $data[$k]['hsjc_time'];
                                $save[$k]['hsjc_previous_time'] = $v['old_hsjc_time'];
                                $save[$k]['hsjc_gettime'] = $hsjc_gettime;
                                $save[$k]['hsjc_result'] = $data[$k]['hsjc_result'];
                                $save[$k]['hsjc_jcjg'] = $data[$k]['hsjc_jcjg'];
                                // 需要进行更新
                                $userDao->update($v['user_id'],$save[$k]);
                            }
                        }
                    }
                    // 更新
                    Db::name('company_staff')->where('id','in',$ids_arr)->update(['is_update_to_user'=>1]);
                }
            }
        }catch(\Exception $e) {
            test_log('taskUpdateCompanyStaffHsjc error:'. $e->getMessage());
        }
    }
    // 更新企业员工的核酸信息从义乌本地库（已不用）
    // public function taskUpdateCompanyStaffHsjc_old($param)
    // {
    //     try{
    //         $companyStaffDao = app()->make(CompanyStaffDao::class);
    //         $userDao = app()->make(UserDao::class);

    //         $hsjc_gettime = Date('Y-m-d H:i:s');
    //         $index = 1;
    //         while($index == 1) {
    //             $pageSize = 1000;
    //             $list = $companyStaffDao->getNeedUpdateHsjcFromLocalYwData($pageSize);
    //             if(count($list) == 0) {
    //                 $index = 0;
    //             }else{
    //                 $ids_arr = [];
    //                 foreach($list as $k => $v) {
    //                     array_push($ids_arr,$v['yw_user_hsjc_id']);
    //                     if($v['old_hsjc_time'] != $v['hsjc_time'] && strtotime($v['hsjc_time']) > strtotime($v['old_hsjc_time'])   ){
    //                         $save[$k] = [];
    //                         $save[$k]['hsjc_time'] = $v['hsjc_time'];
    //                         $save[$k]['hsjc_previous_time'] = $v['old_hsjc_time'];
    //                         $save[$k]['hsjc_gettime'] = $hsjc_gettime;
    //                         if($v['hsjc_result'] != ''){
    //                             // 早先这2个字段是空值
    //                             $save[$k]['hsjc_result'] = $v['hsjc_result'];
    //                             $save[$k]['hsjc_jcjg'] = $v['hsjc_jcjg'];
    //                         }
    //                         // 需要进行更新
    //                         $userDao->update($v['user_id'],$save[$k]);
    //                     }
    //                 }
    //                 // 更新
    //                 Db::name('yw_user_hsjc')->where('id','in',$ids_arr)->update(['is_update_to_user'=>1]);
    //                 sleep(2);
    //             }
    //         }
    //     }catch(\Exception $e) {
    //         test_log('taskUpdateCompanyStaffHsjc error:'. $e->getMessage());
    //     }
    // }
    // public function taskUpdateCompanyStaffNoYwHsjc(){
    //     try{
    //         // $companyStaffDao = app()->make(CompanyStaffDao::class);
    //         $userDao = app()->make(UserDao::class);

    //         $hsjc_gettime = Date('Y-m-d H:i:s');
    //         $index = 1;
    //         while($index == 1) {
    //             $pageSize = 100; // 速度好像有点慢，这里先选100个
    //             // 条件太少，需要加id偏移
    //             $list = Db::view('view_company_user_no_yw_hsjc')->limit($pageSize)->select();
    //             if(count($list) == 0) {
    //                 $index = 0;
    //             }else{
    //                 $ids_arr = [];
    //                 foreach($list as $k => $v) {
    //                     array_push($ids_arr,$v['yw_user_hsjc_id']);
    //                     if($v['old_hsjc_time'] != $v['hsjc_time'] && strtotime($v['hsjc_time']) > strtotime($v['old_hsjc_time'])   ){
    //                         $save[$k] = [];
    //                         $save[$k]['hsjc_time'] = $v['hsjc_time'];
    //                         $save[$k]['hsjc_previous_time'] = $v['old_hsjc_time'];
    //                         $save[$k]['hsjc_gettime'] = $hsjc_gettime;
    //                         if($v['hsjc_result'] != ''){
    //                             // 早先这2个字段是空值
    //                             $save[$k]['hsjc_result'] = $v['hsjc_result'];
    //                             $save[$k]['hsjc_jcjg'] = $v['hsjc_jcjg'];
    //                         }
    //                         // 需要进行更新
    //                         // test_log($v['id_card'].'更新:'.json_encode($save[$k]));
    //                         $userDao->update(['id_card'=>$v['id_card']],$save[$k]);
    //                     }
    //                 }
    //                 // 更新
    //                 Db::name('yw_user_hsjc')->where('id','in',$ids_arr)->update(['is_update_to_user'=>1]);
    //                 sleep(2);
    //             }
    //         }
    //     }catch(\Exception $e) {
    //         test_log('taskUpdateCompanyStaffHsjc error:'. $e->getMessage());
    //     }
    // }



    //企业码加水印
    public function companyQrcodeWatermarkService($param)
    {
        $url = '';
        $at_server = Config::get('upload.at_server');
        if($at_server == 'dev') { //测试服务器
            $this->companyQrcodeWatermarkHandleService($param['file_path'], $param['version'], $param['name']);
            //$url = 'https://ldrk.jk-kj.com/wx_qrcode/watermark_company';
        }else{
            if(strstr($param['file_path'], 'server112')) {
                if($at_server == 'server112') { //本服务器
                    $this->companyQrcodeWatermarkHandleService($param['file_path'], $param['version'], $param['name']);
                }else{
                    $url = 'http://172.45.4.112:30399/wx_qrcode/watermark_company';
                }
            }else if(strstr($param['file_path'], 'server118')) {
                if($at_server == 'server118') { //本服务器
                    $this->companyQrcodeWatermarkHandleService($param['file_path'], $param['version'], $param['name']);
                }else{
                    $url = 'http://172.45.4.118:30399/wx_qrcode/watermark_company';
                }
            }else if(strstr($param['file_path'], 'server114')) {
                if($at_server == 'server114') { //本服务器
                    $this->companyQrcodeWatermarkHandleService($param['file_path'], $param['version'], $param['name']);
                }else{
                    $url = 'http://172.45.4.101:30399/wx_qrcode/watermark_company';
                }
            }else if(strstr($param['file_path'], 'server96')) {
                if($at_server == 'server96') { //本服务器
                    $this->companyQrcodeWatermarkHandleService($param['file_path'], $param['version'], $param['name']);
                }else{
                    $url = 'http://172.45.253.96:30399/wx_qrcode/watermark_company';
                }
            }else if(strstr($param['file_path'], 'server97')) {
                if($at_server == 'server97') { //本服务器
                    $this->companyQrcodeWatermarkHandleService($param['file_path'], $param['version'], $param['name']);
                }else{
                    $url = 'http://172.45.253.97:30399/wx_qrcode/watermark_company';
                }
            }else if(strstr($param['file_path'], 'server95')) {
                if($at_server == 'server95') { //本服务器
                    $this->companyQrcodeWatermarkHandleService($param['file_path'], $param['version'], $param['name']);
                }else{
                    $url = 'http://172.45.253.95:30399/wx_qrcode/watermark_company';
                }
            }
        }
        if($url == '') {
            return true;
        }
        $curl = new Curl();
        $data = [
            'file_path' => $param['file_path'],
            'version' => $param['version'],
            'name' => $param['name'],
        ];
        $curl->post($url, $data);
        if($curl->error) {
            test_log('跨服务器生成企业码失败-'. $curl->error .': '. $curl->error_message);
        }
        //test_log('生成微信小程序码-'. json_encode($curl->response));
        return true;
    }
    
    public function companyQrcodeWatermarkHandleService($file_path, $version, $name)
    {
        try{
            $file_path = app()->getRootPath() .'public'. $file_path;
            $watermark_file_path = str_replace('_qrcode.png', '_qrcode_qy_'. $version .'.png', $file_path);
            $dst_path = app()->getRootPath() .'public/file/image/wxqrcode_company_bg'. $version .'.png'; // 背景图
            if($version == 'v2') {
                $this->wxQrcodeMerge($file_path, $watermark_file_path, $dst_path, 160, 673);
                $this->wxQrcodeText($watermark_file_path, $watermark_file_path, $name, 120, 470);
            }
            
        } catch (\Exception $e) {
            test_log('企业码加水印失败-'. $e->getMessage());
        }
    }


    public function syncStaffHsjcFromLocalSKHL($param){
        $skhl_res = $param['item'];
        $data = [
            'hsjc_time' => $skhl_res['checktime'] ? date('Y-m-d H:i:s', strtotime($skhl_res['checktime'])) : '1970-01-01 00:00:00',
            'hsjc_result' => $skhl_res['result'],
            'hsjc_jcjg' => $skhl_res['jgmc'],
            'hsjc_gettime' => date('Y-m-d H:i:s'),
            // 'hsjc_explain' => '省库回流',
        ];
        try {
            $userDao = app()->make(UserDao::class);
            $user = $userDao->get(['id_card'=>$param['id_card']]);
            //获取的核酸时间比用户的核酸时间新，则更新
            if($user && $data['hsjc_time'] && strtotime($data['hsjc_time']) > strtotime($user['hsjc_time'])) {
                $userDao->update(['id_card'=>$param['id_card']], $data);
            }
        } catch (\Exception $e){
            test_log('CompanyTaskServices syncStaffHsjcFromLocalSKHL error:'.$e->getMessage());
        }
    }

    
    
    //合并图像
    public function wxQrcodeMerge($fromfile, $tofile, $dst_path, $dst_x=0, $dst_y=0)
    {
        $src_path = $fromfile; // 二维码图
        //创建图片的实例
        $dst = imagecreatefromstring(file_get_contents($dst_path));
        $src = imagecreatefromstring(file_get_contents($src_path));
        // 把二维码图片的白色背景设为透明
        imagecolortransparent($src, imagecolorallocate($src, 255, 255, 255));
        //获取水印图片的宽高
        list($src_w, $src_h) = getimagesize($src_path);
        //var_dump(getimagesize($src_path));
        //将水印图片复制到目标图片上
        imagecopymerge($dst, $src, $dst_x, $dst_y, 0, 0, $src_w, $src_h, 100);
        //生成图片
        imagepng($dst, $tofile);
        //销毁
        imagedestroy($dst);
        imagedestroy($src);
    }

    //向不同格式的图片画一个字符串（也是文字水印）
    public function wxQrcodeText($fromfile, $tofile, $string, $x=0, $y=0)
    {
        //获取图片的属性，第一个宽度，第二个高度，类型1=>gif，2=>jpeg,3=>png
        list($width,$height,$type) = getimagesize($fromfile);
        //可以处理的图片类型
        $types = array(1=>"gif",2=>"jpeg",3=>"png",);
        //通过图片类型去组合，可以创建对应图片格式的，创建图片资源的GD库函数
        $createfrom = "imagecreatefrom".$types[$type];
        //通过“变量函数”去打对应的函数去创建图片的资源
        $image = $createfrom($fromfile);

        //设置字体的颜色为黑色
        $textcolor = imagecolorallocate($image, 0, 0, 0);
        $font = app()->getRootPath() .'public/file/fonts/Songti.ttc'; //字体在服务器上的绝对路径
        //设置居中字体的X轴坐标位置
        //$x = ($width-imagefontwidth(5)*strlen($string))/2;
        //设置居中字体的Y轴坐标位置
        //$y = ($height-imagefontheight(5))/1.18;
        $strlen = mb_strlen($string, 'utf8');
        //120   470
        
        if($strlen <= 5) {
            $x = 280;
            imagefttext($image, 30, 0, $x, $y, $textcolor, $font, $string);
        }else if($strlen >= 6 && $strlen <= 8) {
            $x = 230;
            imagefttext($image, 30, 0, $x, $y, $textcolor, $font, $string);
        }else if($strlen >= 9 && $strlen <= 11) {
            $x = 180;
            imagefttext($image, 30, 0, $x, $y, $textcolor, $font, $string);
        }else if($strlen >= 12 && $strlen <= 14) {
            imagefttext($image, 30, 0, $x, $y, $textcolor, $font, $string);
        }
        if($strlen >= 14) {
            $x = 120;
            $front_string = mb_substr($string, 0, 14);
            imagefttext($image, 30, 0, $x, $y, $textcolor, $font, $front_string);
            $y = 530;
            $behind_string = mb_substr($string, 14, 28);
            imagefttext($image, 30, 0, $x, $y, $textcolor, $font, $behind_string);
        }

        //通过图片类型去组合保存对应格式的图片函数
        $output = "image".$types[$type];
        //通过变量函数去保存对应格式的图片
        $output($image,$tofile);
        imagedestroy($image);
    }
}
