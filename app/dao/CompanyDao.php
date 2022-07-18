<?php

namespace app\dao;

use app\dao\BaseDao;
use app\model\Company;

class CompanyDao extends BaseDao
{
    /**
     * 设置模型
     * @return string
     */
    protected function setModel(): string
    {
        return Company::class;
    }

    // //用于定时任务:计算企业核酸检测数
    // public function taskUnfixCompanyListByLimit()
    // {
    //     return $this->getModel()
    //         ->field('id,user_count')
    //         ->where('fix_tag','=',0)
    //         ->limit(100)
    //         ->select()
    //         ->toArray();
    // }

    //后台企业码列表
    public function getList($param,$adminInfo=[])
    {
        $where = [];
        //分段导出
        if(isset($param['_where_id_lg']) && $param['_where_id_lg'] > 0) {
            $where[] = ['id', '>', $param['_where_id_lg']];
        }
        if(isset($param['yw_street_id']) && $param['yw_street_id'] > 0) {
            $where[] = ['yw_street_id', '=', $param['yw_street_id']];
        }
        if(isset($param['community_id']) && $param['community_id'] != '') {
            $where[] = ['community_id', '=', $param['community_id']];
        }
        if(isset($param['classify_name']) && $param['classify_name'] != '') {
            $where[] = ['classify_name', '=', $param['classify_name']];
        }
        if(isset($param['name']) && $param['name'] != '') {
            $where[] = ['name', 'LIKE', '%'. $param['name'] .'%'];
        }
        if(isset($param['credit_code']) && $param['credit_code'] != '') {
            $where[] = ['credit_code', 'LIKE', '%'. $param['credit_code'] .'%'];
        }
        if(isset($param['link_man']) && $param['link_man'] != '') {
            $where[] = ['link_man', 'LIKE', '%'. $param['link_man'] .'%'];
        }
        if(isset($param['link_phone']) && $param['link_phone'] != '') {
            $where[] = ['link_phone', 'LIKE', '%'. $param['link_phone'] .'%'];
        }
        if(isset($param['link_idcard']) && $param['link_idcard'] != '') {
            $where[] = ['link_idcard', 'LIKE', '%'. $param['link_idcard'] .'%'];
        }
        if(isset($param['qualified']) && $param['qualified'] != 1) {
            $where[] = ['seven_rate', '>=', 10];
            $where[] = ['seventy_rate', '=', 100];
        }

        // 企业类型：company=工业企业,lawoffice=律师事务所,logistics=快递物流企业,business=经营性场所,build=建筑工地,education=教育培训机构,other=其他,gov=机关、事业单位和国有企业
        // 超级管理员可以查看全部
        // 其他管理员只能查看 company lawoffice logistics business build education other
        // 组织部（zuzhibu）能查看 gov
        // 镇街（zhenjie）能查看 他自己镇街下面的
        $this->_admin_where($where,$adminInfo);

        $query = $this->getModel()::where($where);
        //分段导出
        if(isset($param['_where_id_lg'])) {
            $query->order('id', 'asc');
        }else{
            $query->order('user_count', 'desc');
        }
        return $query->paginate($param['size'])->toArray();
    }

    protected function _unqualified_where($param,$adminInfo=[])
    {
        $where = [];
        //分段导出
        if(isset($param['_where_id_lg']) && $param['_where_id_lg'] > 0) {
            $where[] = ['id', '>', $param['_where_id_lg']];
        }
        if(isset($param['yw_street_id']) && $param['yw_street_id'] > 0) {
            $where[] = ['yw_street_id', '=', $param['yw_street_id']];
        }
        if(isset($param['community_id']) && $param['community_id'] != '') {
            $where[] = ['community_id', '=', $param['community_id']];
        }
        if(isset($param['classify_name']) && $param['classify_name'] != '') {
            $where[] = ['classify_name', '=', $param['classify_name']];
        }
        if(isset($param['name']) && $param['name'] != '') {
            $where[] = ['name', '=', $param['name']];
        }
        if(isset($param['credit_code']) && $param['credit_code'] != '') {
            $where[] = ['credit_code', '=', $param['credit_code']];
        }
        if(isset($param['link_man']) && $param['link_man'] != '') {
            $where[] = ['link_man', '=', $param['link_man']];
        }
        if(isset($param['link_phone']) && $param['link_phone'] != '') {
            $where[] = ['link_phone', '=', $param['link_phone']];
        }
        if(isset($param['link_idcard']) && $param['link_idcard'] != '') {
            $where[] = ['link_idcard', '=', $param['link_idcard']];
        }
        if(isset($param['user_count']) && $param['user_count'] != '') {
            $where[] = ['user_count', '>', $param['user_count']];
        }

        // 超级管理员可以查看全部
        // 其他管理员不能查看 机关企事业单位
        // 组织部（zuzhibu）能查看 机关企事业单位
        // 镇街（zhenjie）能查看 他自己镇街下面的
        $this->_admin_where($where,$adminInfo);

        return $where;
    }

    private function _admin_where(&$where, $adminInfo)
    {
        if(isset($adminInfo['role_code']) && $adminInfo['role_code'] != ''){
            switch($adminInfo['role_code']){
                case 'zuzhibu': // 组织部
                    $where[] = ['classify_id', '=', 18];
                    //$where[] = ['cc.classify_code', '=', 'jgqsydw'];
                    break;
                case 'zhenjie': // 镇街
                    // 可能看当前管理员所在镇街的数据
                    if($adminInfo['yw_street_id'] > 0) {
                        $where[] = ['yw_street_id','=',$adminInfo['yw_street_id']];
                    }else{
                        // 不能查看任何
                        $where[] = ['id','=','-1'];// 不成立的条件
                    }
                    $where[] = ['classify_id', '<>', 18];
                    //$where[] = ['cc.classify_code', '<>', 'jgqsydw'];
                    break;
                default :
                    $where[] = ['classify_id', '<>', 18];
                    //$where[] = ['cc.classify_code', '<>', 'jgqsydw'];
                    break;
            }
        }
    }

    public function getUnqualifiedList($param,$adminInfo=[])
    {
        $where = $this->_unqualified_where($param,$adminInfo);
        
        $query = $this->getModel()::where($where)
            ->where(function ($query) {
                $query->where('seven_rate', '<', 10)
                    ->whereOr('seventy_rate', '<', 100);
            });
        //分段导出
        if(isset($param['_where_id_lg'])) {
            $query->order('id', 'asc');
        }else{
            $query->order('user_count', 'desc');
        }
        return $query->paginate($param['size'])->toArray();
    }

    public function unqualifiedCompanyListToSendSms($param)
    {
        $where = $this->_unqualified_where($param);
        $where[] = ['seven_rate', '<', 10];
        return $this->getModel()::field('id,name,link_id,link_name,link_phone,user_count,seven_count')
            ->where($where)
            ->select()
            ->toArray();
    }

    //街道48小时内核酸检测人数不足5人
    public function twoDaysHsjcByStreet()
    {
        return $this->getModel()
            ->field('sum(two_count) sum,yw_street_id,yw_street')
            ->group('yw_street_id')
            ->having('sum<5')
            ->select()
            ->toArray();
    }
}
