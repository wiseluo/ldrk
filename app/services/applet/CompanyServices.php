<?php

namespace app\services\applet;

use app\services\applet\BaseServices;
use app\dao\CompanyDao;
use app\dao\CompanyStaffDao;
use app\dao\CompanyClassifyDao;
use app\dao\CompanyStaffClassifyDao;
use app\dao\UserDao;
use \behavior\WechatAppletTool;
use \behavior\SmsTool;
use \behavior\SsjptTool;
use app\services\SgzxServices;
use think\facade\Db;
use crmeb\services\SwooleTaskService;

class CompanyServices extends BaseServices
{
    public function __construct(CompanyDao $dao)
    {
        $this->dao = $dao;
    }

    public function getUserCompanyList($user_id)
    {
        return app()->make(CompanyStaffDao::class)->getUserCompanyList($user_id);
    }

    public function readService($user_id)
    {
        $company = $this->dao->get(['link_id'=> $user_id]);
        if($company) {
            $company->append(['company_qrcode_arr','classify_name']);
            return ['status' => 1, 'msg'=> '成功', 'data'=> $company];
        }else{
            return ['status' => 0, 'msg'=> '不存在'];
        }
    }
    
    public function saveService($param, $userInfo)
    {
        if($param['classify_id'] == 18) {
            return ['status' => 0, 'msg' => '机关企事业单位不能申请'];
        }
        $company = $this->dao->get(['link_id'=> $userInfo['id']]);
        if($company) {
            return ['status' => 0, 'msg' => '您已申请过企业信息码，不能重复申请'];
        }
        $company = $this->dao->get(['credit_code'=> $param['credit_code']]);
        if($company) {
            return ['status' => 0, 'msg' => '该企业已申请了信息码，不能重复申请'];
        }
        $name = trim($param['name']);
        $code = randomCode(12);
        $wechatAppletTool = new WechatAppletTool();
        $applet_qrcode = $wechatAppletTool->appletCompanyQrcode($code, $name);
        if($applet_qrcode['status'] == 0) {
            return ['status' => 0, 'msg' => '操作失败-'. $applet_qrcode['msg']];
        }
        $data = [
            'code' => $code,
            'company_qrcode' => $applet_qrcode['data'],
            'classify_id' => $param['classify_id'],
            'credit_code' => $param['credit_code'],
            'name' => $name,
            'yw_street_id' => $param['yw_street_id'],
            'yw_street' => $param['yw_street'],
            'community_id' => $param['community_id'],
            'community' => $param['community'],
            'addr' => $param['addr'],
            'link_id' => $userInfo['id'],
            'link_name' => $userInfo['real_name'],
            'link_phone' => $userInfo['phone'],
            'link_idcard' => $userInfo['id_card'],
            'user_count' => 1,
        ];
        try {
            $company = $this->dao->save($data);
            //联络员默认添加为企业下的员工
            $staff_classify = app()->make(CompanyStaffClassifyDao::class)->get(97);
            $staff_data = [
                'company_id'=> $company->id,
                'company_name'=> $data['name'],
                'user_id'=> $data['link_id'],
                'user_name'=> $data['link_name'],
                'user_idcard'=> $data['link_idcard'],
                'user_phone'=> $data['link_phone'],
                'yw_street_id' => $param['yw_street_id'],
                'yw_street' => $param['yw_street'],
                'staff_classify_id' => 97, //默认人员类型
                'check_frequency' => $staff_classify['check_frequency'],
            ];
            app()->make(CompanyStaffDao::class)->save($staff_data);

            // 重新计算企业人数数值
            SwooleTaskService::company()->taskType('company')->data(['action'=>'taskCompanyCheckFrequencyCountByCompanyId','param'=> ['company_id' =>$company->id]])->push();


            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }

    public function updateService($param, $userInfo)
    { 
        $company = $this->dao->get(['link_id'=> $userInfo['id']]);
        if($company == null) {
            return ['status' => 0, 'msg' => '您未申请过企业信息码'];
        }
        if($company['classify_id'] == 18 && $param['classify_id'] != $company['classify_id']) {
            return ['status' => 0, 'msg' => '机关企事业单位不能修改为其它类型'];
        }
        if($company['classify_id'] == 20 && $param['classify_id'] != $company['classify_id']) {
            return ['status' => 0, 'msg' => '专有类型不能修改为其它类型'];
        }
        if($company['link_update_time'] != null && (time() - strtotime($company['link_update_time']) < 2592000)) {
            return ['status' => 0, 'msg' => '一个月内只能修改一次'];
        }

        $data = [
            'classify_id' => $param['classify_id'],
            'yw_street_id' => $param['yw_street_id'],
            'yw_street' => $param['yw_street'],
            'community_id' => $param['community_id'],
            'community' => $param['community'],
            'addr' => $param['addr'],
            'link_update_time' => date('Y-m-d H:i:s'),
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
    
    //员工从企业离职
    public function deleteService($id, $user_id)
    {
        $companyStaffDao = app()->make(CompanyStaffDao::class);
        $staff = $companyStaffDao->get($id);
        if($staff == null) {
            return ['status' => 0, 'msg' => '记录不存在'];
        }
        $company = $this->dao->get($staff['company_id']);
        if($company == null) {
            return ['status' => 0, 'msg' => '企业不存在'];
        }
        if($user_id != $staff['user_id']) {
            return ['status' => 0, 'msg' => '记录错误，请联系企业联络员'];
        }
        if($company['link_id'] == $user_id) {
            return ['status' => 0, 'msg' => '您是该企业的联络员，不能删除'];
        }
        
        try {
            $companyStaffDao->delete($id);
            // 重新计算企业人数数值
            SwooleTaskService::company()->taskType('company')->data(['action'=>'taskCompanyCheckFrequencyCountByCompanyId','param'=> ['company_id' => $company['id']]])->push();

            //发送短信
            $smsTool = new SmsTool();
            $res = $smsTool->sendSms($company['link_phone'], $company['link_name'] .'您好，您公司的员工'. $staff['user_name'] .'已经离职！');
            if($res['status'] == 1) {
                return ['status'=> 1, 'msg'=> '操作成功'];
            }else{
                return ['status'=> 0, 'msg'=> '短信发送失败! 状态：' . $res['msg']];
            }
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }

    public function classifyVerifyService($param)
    {
        $name = '';
        $ssjptTool = new SsjptTool();
        //这几个目前先不做验证：机关企事业单位、教育培训行业、专用
        if(in_array($param['classify_id'], [18, 7, 20])) {
            return ['status' => 1, 'msg' => '操作成功', 'data'=> ['name'=> '']];
        }
        if($param['classify_id'] == 17) { //律师事务所
            if(isset($param['name']) && $param['name'] != '') {
                $res =  $ssjptTool->sklssws($param['name'], $param['credit_code']);
                if($res['status'] == 0) {
                    return ['status' => 0, 'msg' => $res['msg']];
                }
                $name = $param['name'];
            }else{
                return ['status' => 0, 'msg' => '律师事务所信用代码证错误'];
            }
        }else if($param['classify_id'] == 9) { //养老机构
            $has = Db::name('yw_company_library')->where([['classify_id','=', 9], ['credit_code'=> $param['credit_code']]])->find();
            if($has){
                $name = $has['name'];
            }else{
                return ['status' => 0, 'msg' => '养老机构信用代码证错误'];
            }
        }else{
            // 如果credit_code的开头不是9，则先到eb_yw_regno_uniscid查询一下是否是注册码过来的查询
            //$has = Db::name('yw_regno_uniscid')->where('regno','=',$param['credit_code'])->find();
            $res = app()->make(SgzxServices::class)->enterpriseInfo($param['credit_code']);
            if($res['status'] == 0) {
                return ['status' => 0, 'msg' => '社会信用代码证错误'];
            }
            $name = $res['data']['companyName'];
        }
        return ['status' => 1, 'msg' => '操作成功', 'data'=> ['name'=> $name]];
    }

    public function classifyListService()
    {
        return app()->make(CompanyClassifyDao::class)->getListForApp();
    }

    public function staffClassifyListService($param)
    {
        return app()->make(CompanyStaffClassifyDao::class)->getListSelect($param);
    }
}
