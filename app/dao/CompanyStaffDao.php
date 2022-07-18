<?php

namespace app\dao;

use app\dao\BaseDao;
use app\model\CompanyStaff;
use think\facade\Db;

class CompanyStaffDao extends BaseDao
{
    /**
     * 设置模型
     * @return string
     */
    protected function setModel(): string
    {
        return CompanyStaff::class;
    }

    //小程序联络人所属公司下的员工列表
    public function getList($param)
    {
        $where = [];
        $where[] = ['cs.company_id', '=', $param['company_id']];
        if(isset($param['user_name']) && $param['user_name'] != '') {
            $where[] = ['cs.user_name', 'like', '%'. $param['user_name'] .'%'];
        }
        if(isset($param['user_idcard']) && $param['user_idcard'] != '') {
            $where[] = ['cs.user_idcard', '=', $param['user_idcard']];
        }
        if(isset($param['user_phone']) && $param['user_phone'] != '') {
            $where[] = ['cs.user_phone', '=', $param['user_phone']];
        }
        if(isset($param['yiwu_name']) && $param['yiwu_name'] != '') {
            $where[] = ['csc.yiwu_name', 'like', '%'. $param['yiwu_name'] .'%'];
        }
        if(isset($param['gov_name']) && $param['gov_name'] != '') {
            $where[] = ['csc.gov_name', 'like', '%'. $param['gov_name'] .'%'];
        }
        if(isset($param['staff_classify_id']) && $param['staff_classify_id'] != '') {
            $where[] = ['cs.staff_classify_id', '=', $param['staff_classify_id']];
        }
        if(isset($param['classify_id']) && $param['classify_id'] != '') {
            $where[] = ['c.classify_id', '=', $param['classify_id']];
        }
        if(isset($param['yw_street_id']) && $param['yw_street_id'] != '') {
            $where[] = ['cs.yw_street_id', '=', $param['yw_street_id']];
        }
        if(isset($param['community_id']) && $param['community_id'] != '') {
            $where[] = ['c.community_id', '=', $param['community_id']];
        }
        if(isset($param['community']) && $param['community'] != '') {
            $where[] = ['c.community', '=', $param['community']];
        }
        if(isset($param['remind_type']) && $param['remind_type'] != '') {
            switch($param['remind_type']) {
                case 'seven_day': 
                    $where[] = ['cs.receive_time', '>', Date('Y-m-d', strtotime('-7 days')) .' 00:00:00'];
                    break;
                case 'seventy_day': 
                    $where[] = ['cs.receive_time', '>', Date('Y-m-d', strtotime('-70 days')) .' 00:00:00'];
                    break;
            }
        }

        return $this->getModel()::alias('cs')
            ->leftJoin('company c', 'c.id=cs.company_id')
            ->leftJoin('company_staff_classify csc', 'csc.id=cs.staff_classify_id')
            // ->leftJoin('user u', 'u.id=cs.user_id')
            ->field('cs.*,csc.check_frequency_text')
            ->where($where)
            ->order('cs.receive_time', 'asc')
            ->paginate($param['size'])
            ->toArray();
    }

    //企业员工核酸数统计
    public function getCompanyHsjcCount($company_id)
    {
        $where = [];
        $where[] = ['cs.company_id', '=', $company_id];
        $two_datetime = Date('Y-m-d', strtotime('-2 days')) .' 00:00:00';
        $seven_datetime = Date('Y-m-d', strtotime('-7 days')) .' 00:00:00';
        $seventy_datetime = Date('Y-m-d', strtotime('-70 days')) .' 00:00:00';

        return $this->getModel()::alias('cs')
            ->field('
                    count(if(cs.receive_time > \''. $two_datetime .'\',true,null)) as two_count,
                    count(if(cs.receive_time > \''. $seven_datetime .'\',true,null)) as seven_count,
                    count(if(cs.receive_time > \''. $seventy_datetime .'\',true,null)) as seventy_count
            ')
            // ->leftJoin('user u', 'u.id=cs.user_id')
            ->where($where)
            ->select()
            ->toArray();
    }

    //当前某员工加入的企业列表
    public function getUserCompanyList($user_id)
    {
        $where = [];
        $where[] = ['cs.user_id', '=', $user_id];
        return $this->getModel()::alias('cs')
            ->leftJoin('company c', 'c.id=cs.company_id')
            // ->leftJoin('company_classify cc', 'cc.id=c.classify_id')
            // ->leftJoin('company_staff_classify csc', 'csc.id=cs.staff_classify_id')
            ->field('cs.id,cs.company_id,cs.company_name,c.link_name,c.link_phone,cs.create_time,c.classify_id,cs.staff_classify_name,cs.check_frequency,c.classify_name')
            ->where($where)
            ->select()
            ->toArray();
    }

    //计算企业的员工数量
    public function companyStaffCount($company_id)
    {
        return $this->getModel()
            ->where('company_id', $company_id)
            ->count('id');
    }

    //后台企业员工信息列表
    public function getStaffList($param,$adminInfo=[])
    {
        $where = [];
        if(isset($param['user_name']) && $param['user_name'] != '') {
            $where[] = ['cs.user_name', '=', $param['user_name']];
        }
        if(isset($param['user_idcard']) && $param['user_idcard'] != '') {
            $where[] = ['cs.user_idcard', '=', $param['user_idcard']];
        }
        if(isset($param['user_phone']) && $param['user_phone'] != '') {
            $where[] = ['cs.user_phone', '=', $param['user_phone']];
        }
        if(isset($param['company_name']) && $param['company_name'] != '') {
            $where[] = ['cs.company_name', '=', $param['company_name']];
        }
        if(isset($param['yw_street_id']) && $param['yw_street_id'] != '') {
            $where[] = ['cs.yw_street_id', '=', $param['yw_street_id']];
        }
        if(isset($param['community_id']) && $param['community_id'] != '') {
            $where[] = ['c.community_id', '=', $param['community_id']];
        }
        if(isset($param['community']) && $param['community'] != '') {
            $where[] = ['c.community', '=', $param['community']];
        }
        if(isset($param['classify_name']) && $param['classify_name'] != '') {
            $where[] = ['c.classify_name', '=', $param['classify_name']];
        }
        if(isset($param['classify_id']) && $param['classify_id'] > 0) {
            $where[] = ['c.classify_id', '=', $param['classify_id']];
        }
        if(isset($param['staff_classify_id']) && $param['staff_classify_id'] > 0) {
            $where[] = ['cs.staff_classify_id', '=', $param['staff_classify_id']];
        }
        // 超级管理员可以查看全部
        // 其他管理员不能查看 机关企事业单位
        // 组织部（zuzhibu）能查看 机关企事业单位
        // 镇街（zhenjie）能查看 他自己镇街下面的
        $this->_admin_where($where,$adminInfo);

        return $this->getModel()::alias('cs')
            ->leftJoin('company c', 'c.id=cs.company_id')
            ->field('cs.id,cs.company_id,cs.company_name,cs.user_id,cs.user_name,cs.user_idcard,cs.user_phone,cs.yw_street_id,cs.yw_street,cs.create_time,
                c.credit_code,c.link_name,c.link_phone,c.addr,c.community_id,c.community,cs.receive_time as hsjc_time,"" as hsjc_previous_time,cs.check_frequency,cs.staff_classify_name,c.classify_name,c.classify_id')
            ->where($where)
            ->paginate($param['size'])
            ->toArray();
    }

    private function _admin_where(&$where, $adminInfo)
    {
        if(isset($adminInfo['role_code']) && $adminInfo['role_code'] != ''){
            switch($adminInfo['role_code']){
                case 'zuzhibu': // 组织部
                    $where[] = ['c.classify_id', '=', 18];
                    //$where[] = ['cc.classify_code', '=', 'jgqsydw'];
                    break;
                case 'zhenjie': // 镇街
                    // 可能看当前管理员所在镇街的数据
                    if($adminInfo['yw_street_id'] > 0) {
                        $where[] = ['cs.yw_street_id','=',$adminInfo['yw_street_id']];
                    }else{
                        // 不能查看任何
                        $where[] = ['cs.id','=','-1'];// 不成立的条件
                    }
                    $where[] = ['c.classify_id', '<>', 18];
                    //$where[] = ['cc.classify_code', '<>', 'jgqsydw'];
                    break;
                default :
                    $where[] = ['c.classify_id', '<>', 18];
                    //$where[] = ['cc.classify_code', '<>', 'jgqsydw'];
                    break;
            }
        }
    }

    //联络人短信一键提醒列表，70天内未检测（即无检测时间或检测时间小于相应时间点）
    public function getStaffRemindList($company_id, $hsjc_time='')
    {
        $where = [];
        $where[] = ['cs.company_id', '=', $company_id];
        
        return $this->getModel()::alias('cs')
            // ->leftJoin('user u', 'u.id=cs.user_id')
            ->field('cs.*')
            ->where($where)
            ->where(function ($query) use($hsjc_time) {
                $query->whereNull('cs.receive_time')
                    ->whereOr('cs.receive_time', '<', $hsjc_time);
            })
            ->select()
            ->toArray();
    }

}
