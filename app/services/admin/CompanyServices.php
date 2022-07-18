<?php
declare (strict_types=1);

namespace app\services\admin;

use app\services\user\BaseServices;
use app\dao\UserDao;
use app\dao\CompanyDao;
use app\dao\CompanyClassifyDao;
use app\dao\CompanyStaffClassifyDao;
use app\dao\CompanyStaffDao;
use app\dao\slave\CompanySlaveDao;
use app\dao\slave\CompanyStaffSlaveDao;
use crmeb\services\SwooleTaskService;

class CompanyServices extends BaseServices
{
    public function __construct(CompanyDao $dao)
    {
        $this->dao = $dao;
    }

    public function getList($param,$adminInfo=[])
    {
        return app()->make(CompanySlaveDao::class)->getList($param, $adminInfo);
    }

    public function getListFromSlaveForExport($param,$adminInfo=[])
    {
        $CompanySlaveDao = app()->make(CompanySlaveDao::class);
        return $CompanySlaveDao->getList($param,$adminInfo);
    }

    public function getUnqualifiedListFromSlaveForExport($param,$adminInfo=[]){
        $CompanySlaveDao = app()->make(CompanySlaveDao::class);
        $list = $CompanySlaveDao->getUnqualifiedList($param,$adminInfo);
        $data = [];
        foreach($list['data'] as $v) {
            $item = $v;
            if($v['seven_rate'] > 10) {
                $item['seven_lack'] = 0;
            }else{
                if($v['user_count'] < 10 && $v['seven_count'] == 0) { //小于10人且7天检测人数为0，则至少检测1人
                    $item['seven_lack'] = 1;
                }else{
                    $item['seven_lack'] = bcsub(bcdiv((string)$v['user_count'], '10'), (string)$v['seven_count']); //7天缺检人数
                }
            }
            $item['seventy_lack'] = bcsub((string)$v['user_count'], (string)$v['seventy_count']); //70天缺检人数
            $item['two_rate'] = $v['two_rate'] .'%';
            $item['seven_rate'] = $v['seven_rate'] .'%';
            $item['seventy_rate'] = $v['seventy_rate'] .'%';
            $data[] = $item;
        }
        $list['data'] = $data;
        return $list;
    }

    public function getUnqualifiedList($param,$adminInfo=[])
    {
        $list = $this->dao->getUnqualifiedList($param,$adminInfo);
        $data = [];
        foreach($list['data'] as $v) {
            $item = $v;
            if($v['seven_rate'] > 10) {
                $item['seven_lack'] = 0;
            }else{
                if($v['user_count'] < 10 && $v['seven_count'] == 0) { //小于10人且7天检测人数为0，则至少检测1人
                    $item['seven_lack'] = 1;
                }else{
                    $item['seven_lack'] = bcsub(bcdiv((string)$v['user_count'], '10'), (string)$v['seven_count']); //7天缺检人数
                }
            }
            $item['seventy_lack'] = bcsub((string)$v['user_count'], (string)$v['seventy_count']); //70天缺检人数
            $item['two_rate'] = $v['two_rate'] .'%';
            $item['seven_rate'] = $v['seven_rate'] .'%';
            $item['seventy_rate'] = $v['seventy_rate'] .'%';
            $data[] = $item;
        }
        $list['data'] = $data;
        return $list;
    }

    public function unqualifiedSmsService($param)
    {
        SwooleTaskService::company()->taskType('company')->data(['action'=>'sendSmsToUnqualifiedCompany', 'param'=> $param])->push();
        return ['status' => 1, 'msg'=> '已发送'];
    }

    public function readService($id)
    {
        $company = $this->dao->get($id);
        if($company) {
            return ['status' => 1, 'msg'=> '成功', 'data'=> $company];
        }else{
            return ['status' => 0, 'msg'=> '不存在'];
        }
    }
    
    public function updateService($param, $id)
    { 
        $company = $this->dao->get($id);
        if($company == null) {
            return ['status' => 0, 'msg' => '该企业不存在'];
        }
        $staff_classify = app()->make(CompanyStaffClassifyDao::class)->get($param['classify_id']);
        $data = [
            'classify_id' => $param['classify_id'],
            'classify_name' => $staff_classify['classify_name'],
            'yw_street_id' => $param['yw_street_id'],
            'yw_street' => $param['yw_street'],
            'community_id' => $param['community_id'],
            'community' => $param['community'],
            'addr' => $param['addr'],
        ];
        try {
            $this->dao->update($company['id'], $data);
            if($param['classify_id'] != $company['classify_id']) {
                $staff_classify = app()->make(CompanyStaffClassifyDao::class)->get(97);
                app()->make(CompanyStaffDao::class)->update(['company_id'=> $company['id']], ['staff_classify_id'=> 97, 'check_frequency' => $staff_classify['check_frequency']]);
            }
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }
    
    public function deleteService($id)
    {
        $company = $this->dao->get($id);
        if($company == null) {
            return ['status' => 0, 'msg' => '该企业不存在'];
        }
        try {
            $this->dao->softDelete($id);
            app()->make(CompanyStaffDao::class)->delete(['company_id'=> $id]);
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }
    
    public function staffService($param)
    {
        $company = $this->dao->get($param['company_id']);
        if($company == null) {
            return ['status' => 0, 'msg'=> '企业不存在'];
        }
        $list = app()->make(CompanyStaffSlaveDao::class)->getList($param);
        $list['link'] = ['link_name'=> $company['link_name'], 'link_phone'=> $company['link_phone'], 'link_idcard'=> $company['link_idcard']];
        return ['status' => 1, 'msg'=> '成功', 'data'=> $list];
    }
    
    public function staffCompanyListService($id)
    {
        return app()->make(CompanyStaffSlaveDao::class)->getUserCompanyList($id);
    }

    public function staffListService($param,$adminInfo=[])
    {
        return app()->make(CompanyStaffSlaveDao::class)->getStaffList($param,$adminInfo);
    }
    
    public function staffDeleteService($id)
    {
        $staff = app()->make(CompanyStaffDao::class)->get($id);
        if($staff == null) {
            return ['status' => 0, 'msg' => '该员工不存在'];
        }
        try {
            app()->make(CompanyStaffDao::class)->delete($id);
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }
    
    public function checkFrequencyService($param)
    {
        try {
            $ids_arr = explode(',', $param['ids']);
            $staffDao = app()->make(CompanyStaffDao::class);
            $userDao = app()->make(UserDao::class);
            $company_id_arr = [];
            foreach($ids_arr as $v) {
                $staff = $staffDao->get($v);
                if($staff == null) {
                    continue;
                }
                array_push($company_id_arr,$staff['company_id']);
                $userDao->update($staff['user_id'], ['check_frequency'=> $param['check_frequency']]);
            }
            $company_id_arr = array_unique($company_id_arr);
            foreach($company_id_arr as $key => $company_id){
                // 重新计算数值
                SwooleTaskService::company()->taskType('company')->data(['action'=>'taskCompanyCheckFrequencyCountByCompanyId','param'=> ['company_id' => $company_id]])->push();
            }
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }

    public function transferLinkService($param)
    {
        $company = $this->dao->get($param['company_id']);
        if($company == null) {
            return ['status' => 0, 'msg' => '企业不存在'];
        }
        $staffDao = app()->make(CompanyStaffDao::class);
        $staff = $staffDao->get($param['staff_id']);
        if($staff == null) {
            return ['status' => 0, 'msg' => '员工不存在'];
        }
        if($staff['company_id'] != $company['id']) {
            return ['status' => 0, 'msg' => '员工不在该企业内'];
        }
        $data = [
            'link_id' => $staff['user_id'],
            'link_name' => $staff['user_name'],
            'link_phone' => $staff['user_phone'],
            'link_idcard' => $staff['user_idcard'],
        ];
        try {
            $this->dao->update($company['id'], $data);
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }

    public function batchUpdateCompanyClassify($param){
        $classify_id = $param['classify_id'];
        $company_id_arr = array_filter(explode(',',$param['company_ids']));
        if(count($company_id_arr) == 0){
            return ['status' => 0, 'msg' => '至少选择一个企业'];
        }
        $company_classify = app()->make(CompanyClassifyDao::class)->get($classify_id);
        $default_staff_classify = app()->make(CompanyStaffClassifyDao::class)->get(97);
        try {
            foreach($company_id_arr as $key => $value){
                if($value == '') {
                    continue;
                }
                $company[$key] = $this->dao->get($value);
                if($classify_id != $company[$key]['classify_id']) {
                    // 如果不一致就修改为默认
                    app()->make(CompanyStaffDao::class)->update(['company_id'=> $company[$key]['id']], ['staff_classify_id'=> 97, 'staff_classify_name' => $default_staff_classify['yiwu_name'], 'check_frequency' => $default_staff_classify['check_frequency']]);
                    // 并修改classify_id
                    $this->dao->update($company[$key]['id'], ['classify_id'=>$classify_id, 'classify_name'=> $company_classify['classify_name']]);
                    SwooleTaskService::company()->taskType('company')->data(['action'=>'taskCompanyCheckFrequencyCountByCompanyId','param'=> ['company_id' => $value]])->push();
                }
            }
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            test_log('batchUpdateCompanyClassify error:'.$e->getMessage());
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }


    public function batchUpdateCompanyStaffClassify($param){
        $staff_classify_id = $param['staff_classify_id'];
        $company_staff_id_arr = array_filter(explode(',',$param['company_staff_ids']));
        if(count($company_staff_id_arr) == 0){
            return ['status' => 0, 'msg' => '至少选择一名员工'];
        }
        $staff_classify = app()->make(CompanyStaffClassifyDao::class)->get($staff_classify_id);
        try {
            $companyStaffDao = app()->make(CompanyStaffDao::class);
            foreach($company_staff_id_arr as $key => $value) {
                if($value == '') {
                    continue;
                }
                $company_staff = $companyStaffDao->get($value);
                $companyStaffDao->update(['id'=> $value], ['staff_classify_id'=> $staff_classify_id, 'staff_classify_name' => $staff_classify['yiwu_name'], 'check_frequency' => $staff_classify['check_frequency']]);
                SwooleTaskService::company()->taskType('company')->data(['action'=>'taskCompanyCheckFrequencyCountByCompanyId','param'=> ['company_id' => $company_staff['company_id']]])->push();
            }
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            test_log('batchUpdateCompanyStaffClassify error:'.$e->getMessage());
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }

}
