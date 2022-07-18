<?php

namespace app\services\applet;

use app\services\applet\BaseServices;
use app\dao\CompanyDao;
use app\dao\CompanyStaffDao;
use app\dao\CompanyStaffClassifyDao;
use app\dao\UserDao;
use \behavior\SmsTool;
use crmeb\services\SwooleTaskService;
use think\facade\Cache;
use app\services\MessageServices;
use behavior\TextTool;
use think\facade\Db;

class CompanyStaffServices extends BaseServices
{
    public function __construct(CompanyStaffDao $dao)
    {
        $this->dao = $dao;
    }

    public function getList($param, $user_id)
    {
        $company = app()->make(CompanyDao::class)->get(['link_id'=> $user_id]);
        if($company == null) {
            return ['status' => 0, 'msg' => '企业不存在'];
        }
        $param['company_id'] = $company['id'];
        $list = $this->dao->getList($param);
        $data = [];
        foreach($list['data'] as $v) {
            $item = $v;
            $item['hsjc_text'] = '正常';
            if($v['receive_time'] == null) {
                $item['hsjc_text'] = '超期';
            }else if(time() > strtotime($v['receive_time'] .' +'. $v['check_frequency'] .' days')) {
                $item['hsjc_text'] = '超期';
            }
            $data[] = $item;
        }
        $list['data'] = $data;
        return ['status' => 1, 'msg' => '成功', 'data'=> $list];
    }

    public function getListStatistics($user_id)
    {
        $company = app()->make(CompanyDao::class)->get(['link_id'=> $user_id]);
        if($company == null) {
            return ['status' => 0, 'msg' => '企业不存在'];
        }
        return ['status' => 1, 'msg' => '成功', 'data'=> ['company_id'=> $company['id'], 'classify_id'=> $company['classify_id'], 'hours_count'=> $company['two_count'], 'hours_rate'=> $company['two_rate'].'%', 'seven_count'=> $company['seven_count'], 'seven_rate'=> $company['seven_rate'].'%', 'seventy_count'=> $company['seventy_count'], 'seventy_rate'=> $company['seventy_rate'].'%']];
        // $res = $this->dao->getCompanyHsjcCount($company['id']);
        // $two_count = $res[0]['two_count'];
        // $seven_count = $res[0]['seven_count'];
        // $seventy_count = $res[0]['seventy_count'];
        // $hours_rate = bcmul(bcdiv($two_count, $company['user_count'], 4), 100, 2) .'%';
        // $seven_rate = bcmul(bcdiv($seven_count, $company['user_count'], 4), 100, 2) .'%';
        // $seventy_rate = bcmul(bcdiv($seventy_count, $company['user_count'], 4), 100, 2) .'%';
        // return ['status' => 1, 'msg' => '成功', 'data'=> ['hours_count'=> $two_count, 'hours_rate'=> $hours_rate, 'seven_count'=> $seven_count, 'seven_rate'=> $seven_rate, 'seventy_count'=> $seventy_count, 'seventy_rate'=> $seventy_rate]];
    }

    public function scanGetNameService($param, $user_id)
    {
        $company = app()->make(CompanyDao::class)->get(['code'=> $param['company_code']]);
        if($company == null) {
            return ['status' => 0, 'msg' => '该企业不存在'];
        }
        $user = $this->dao->get(['company_id'=> $company['id'], 'user_id'=> $user_id]);
        if($user) {
            return ['status' => 1, 'msg' => '成功', 'data'=> ['is_join'=>1,'create_time'=>$user['create_time'],'link_name'=>$company['link_name'],'link_phone'=>TextTool::textxx($company['link_phone'],3,4),'name'=> $company['name'], 'company_qrcode'=> $company['company_qrcode']]];
            // return ['status' => 0, 'msg' => '您已经是该企业的员工了，不用重复扫码'];
        }
        if($user['hsjc_gettime'] == null) { //空数据用户第一次获取
            SwooleTaskService::company()->taskType('company')->data(['action'=>'setUserFysjService','param'=> ['id' => $user_id]])->push();
        }
        return ['status' => 1, 'msg' => '成功', 'data'=> ['link_name'=>$company['link_name'],'link_phone'=>TextTool::textxx($company['link_phone'],3,4),'name'=> $company['name'], 'company_qrcode'=> $company['company_qrcode'], 'classify_id'=> $company['classify_id']]];
    }

    public function scanCodeService($param, $userInfo)
    {
        $company = app()->make(CompanyDao::class)->get(['code'=> $param['company_code']]);
        if($company == null) {
            return ['status' => 0, 'msg' => '企业不存在'];
        }
        $staff_classify = app()->make(CompanyStaffClassifyDao::class)->get($param['staff_classify_id']);
        if($staff_classify == null) {
            return ['status' => 0, 'msg' => '员工类型不存在'];
        }
        $staff = $this->dao->get(['company_id'=>$company['id'], 'user_phone'=> $userInfo['phone']]); //相同手机号切换账号，同一公司只留一个
        if($staff) {
            $this->dao->delete($staff['id']);
        }
        $data = [
            'company_id'=> $company['id'],
            'company_name'=> $company['name'],
            'user_id'=> $userInfo['id'],
            'user_name'=> $userInfo['real_name'],
            'user_idcard'=> $userInfo['id_card'],
            'user_phone'=> $userInfo['phone'],
            'yw_street_id'=> $company['yw_street_id'],
            'yw_street'=> $company['yw_street'],
            'check_frequency'=> $staff_classify['check_frequency'],
            'staff_classify_id'=> $param['staff_classify_id'],
        ];
        try {
            // 到省库查询最新的采样时间
            $has_db1 = Db::connect('mysql_shengku')->table('dsc_jh_dm_037_pt_patient_sampinfo_delta')->where('id_card','=',$userInfo['id_card'])->order('sampling_time','desc')->find();
            if($has_db1){
                $data['receive_time'] = $has_db1['sampling_time'];
            }
            $has_db2 = Db::connect('mysql_shengku')->table('frryk_sgxg_labreport')->where('KH','=',$userInfo['id_card'])->order('SEND_TIME','desc')->find();
            if($has_db2){
                if(strtotime($has_db2['SEND_TIME']) > strtotime($data['receive_time'])){
                    $data['receive_time'] = $has_db2['SEND_TIME'];
                }
            }
            $this->dao->save($data);
            // 重新计算企业各个频次的人数
            SwooleTaskService::company()->taskType('company')->data(['action'=>'taskCompanyCheckFrequencyCountByCompanyId','param'=> ['company_id' => $company['id']]])->push();

            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }

    public function deleteService($id, $user_id)
    {
        $staff = $this->dao->get($id);
        if($staff == null) {
            return ['status' => 0, 'msg' => '记录不存在'];
        }
        $company = app()->make(CompanyDao::class)->get($staff['company_id']);
        if($company == null) {
            return ['status' => 0, 'msg' => '企业不存在'];
        }
        if($user_id != $company['link_id']) {
            return ['status' => 0, 'msg' => '您不是该企业的联络员，不能删除员工'];
        }
        if($staff['user_id'] == $user_id) {
            return ['status' => 0, 'msg' => '您是该企业的联络员，不能删除'];
        }
        
        try {
            $this->dao->delete($id);
            // 重新计算企业人数数值
            SwooleTaskService::company()->taskType('company')->data(['action'=>'taskCompanyCheckFrequencyCountByCompanyId','param'=> ['company_id' => $company['id']]])->push();
            //发送短信
            $smsTool = new SmsTool();
            $res = $smsTool->sendSms($staff['user_phone'], $staff['user_name'] .'您好，您已经从'. $company['name'] .'离职。如果是误操作，请扫描公司的企业防疫码重新加入！');
            if($res['status'] == 0) {
                return ['status'=> 0, 'msg'=> '短信发送失败! 状态：' . $res['msg']];
            }
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }

    public function batchDeleteService($ids, $user_id)
    {
        $company = app()->make(CompanyDao::class)->get(['link_id'=> $user_id]);
        if($company == null) {
            return ['status' => 0, 'msg' => '企业不存在'];
        }
        
        try {
            $ids_arr = explode(',', $ids);
            $msg = '';
            foreach($ids_arr as $v) {
                $staff = $this->dao->get($v);
                if($staff['user_id'] == $company['link_id']) { //不能删除联络员
                    $msg .= '不能删除联络员；';
                    continue;
                }
                if($staff['company_id'] != $company['id']) {
                    $msg .= '员工'. $staff['user_name'] .'不属于您的企业；';
                    continue;
                }
                $this->dao->delete($v);
                //发送短信
                $smsTool = new SmsTool();
                $smsTool->sendSms($staff['user_phone'], $staff['user_name'] .'您好，您已经从'. $company['name'] .'离职。如果是误操作，请扫描公司的企业防疫码重新加入！');
            }
            // 重新计算企业人数数值
            SwooleTaskService::company()->taskType('company')->data(['action'=>'taskCompanyCheckFrequencyCountByCompanyId','param'=> ['company_id' => $company['id']]])->push();
            if($msg == '') {
                return ['status' => 1, 'msg' => '操作成功'];
            }else{
                return ['status' => 0, 'msg' => $msg];
            }
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }

    public function linkTransferService($id, $link_id)
    {
        $companyDao = app()->make(CompanyDao::class);
        $company = $companyDao->get(['link_id'=> $link_id]);
        if($company == null) {
            return ['status' => 0, 'msg' => '企业不存在'];
        }
        $staff = $this->dao->get($id);
        if($staff == null) {
            return ['status' => 0, 'msg' => '员工记录不存在'];
        }
        if($staff['user_id'] == $link_id) {
            return ['status' => 0, 'msg' => '不能转移给自己'];
        }
        $staff_company = $companyDao->get(['link_id'=> $staff['user_id']]);
        if($staff_company) {
            return ['status' => 0, 'msg' => '该员工已是别的企业的联络员，不能转移'];
        }
        $data = [
            'link_id'=> $staff['user_id'],
            'link_name'=> $staff['user_name'],
            'link_phone'=> $staff['user_phone'],
            'link_idcard'=> $staff['user_idcard'],
        ];
        try {
            $companyDao->update($company['id'], $data);
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }

    public function oneclickRemindService($param, $link_id)
    {
        $companyDao = app()->make(CompanyDao::class);
        $company = $companyDao->get(['link_id'=> $link_id]);
        if($company == null) {
            return ['status' => 0, 'msg' => '企业不存在'];
        }
        if(Cache::get('oneclickRemindLink-' .$company['id'])) {
            return ['status' => 0, 'msg' => '一小时内只能操作一次'];
        }
        $hsjc_time = '';
        switch($param['remind_type']) {
            case 'seven_day': 
                $hsjc_time = Date('Y-m-d', strtotime('-7 days')) .' 00:00:00';
                break;
            case 'seventy_day': 
                $hsjc_time = Date('Y-m-d', strtotime('-70 days')) .' 00:00:00';
                break;
        }
        $messageService = app()->make(MessageServices::class);
        $list = $this->dao->getStaffRemindList($company['id'], $hsjc_time);
        foreach($list as $v) {
            //发送短信
            $message_data = [
                'receive_id'=> $v['user_id'],
                'receive'=> $v['user_name'],
                'phone'=> $v['user_phone'],
                'source_id'=> $v['id'],
            ];
            $param_data = [
                'real_name'=> $v['user_name'],
            ];
            $messageService->asyncMessage('template004', $message_data, $param_data);
        }
        Cache::set('oneclickRemindLink-' .$company['id'], 1, 3600);
        return ['status' => 1, 'msg' => '发送成功'];
    }

    public function checkFrequencyService($param, $link_id)
    {
        $company = app()->make(CompanyDao::class)->get(['link_id'=> $link_id]);
        if($company == null) {
            return ['status' => 0, 'msg' => '企业不存在'];
        }
        $staff_classify = app()->make(CompanyStaffClassifyDao::class)->get($param['staff_classify_id']);
        if($staff_classify == null) {
            return ['status' => 0, 'msg' => '员工类型不存在'];
        }
        try {
            $ids_arr = explode(',', $param['ids']);
            //$userDao = app()->make(UserDao::class);
            foreach($ids_arr as $k => $v) {
                $staff[$k] = $this->dao->get($v);
                if($staff[$k] == null) {
                    continue;
                }
                $data[$k] = [
                    'check_frequency'=> $staff_classify['check_frequency'],
                    'staff_classify_name'=> $staff_classify['yiwu_name'],
                    'staff_classify_id'=> $param['staff_classify_id'],
                ];
                $this->dao->update($v, $data[$k]);
            }
            // 重新计算企业人数数值
            SwooleTaskService::company()->taskType('company')->data(['action'=>'taskCompanyCheckFrequencyCountByCompanyId','param'=> ['company_id' => $company['id']]])->push();
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }
    
    public function userCheckFrequencyService($param, $user_id)
    {
        $company = app()->make(CompanyDao::class)->get($param['company_id']);
        if($company == null) {
            return ['status' => 0, 'msg' => '企业不存在'];
        }
        $staff_classify = app()->make(CompanyStaffClassifyDao::class)->get($param['staff_classify_id']);
        if($staff_classify == null) {
            return ['status' => 0, 'msg' => '员工类型不存在'];
        }
        $staff = $this->dao->get(['company_id'=> $param['company_id'], 'user_id'=> $user_id]);
        if($staff == null) {
            return ['status' => 0, 'msg' => '您不是该公司的员工'];
        }
        try {
            $data = [
                'check_frequency'=> $staff_classify['check_frequency'],
                'staff_classify_name'=> $staff_classify['yiwu_name'],
                'staff_classify_id'=> $param['staff_classify_id'],
            ];
            $this->dao->update($staff['id'], $data);
            // 重新计算企业人数数值
            SwooleTaskService::company()->taskType('company')->data(['action'=>'taskCompanyCheckFrequencyCountByCompanyId','param'=> ['company_id' => $company['id']]])->push();
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }
}
