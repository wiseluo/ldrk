<?php
declare (strict_types=1);

namespace app\services\admin;

use app\services\user\BaseServices;
use app\dao\CompanyStaffDao;
use app\dao\CompanyStaffClassifyDao;

class CompanyStaffClassifyServices extends BaseServices
{
    public function __construct(CompanyStaffClassifyDao $dao)
    {
        $this->dao = $dao;
    }

    public function getList($param)
    {
        return $this->dao->getList($param);
    }

    public function saveService($param)
    {
        if(isset($param['industry_code']) && $param['industry_code'] != '') {
            $classify = $this->dao->get(['industry_code'=> $param['industry_code']]);
            if($classify) {
                return ['status' => 0, 'msg' => '行业编码已存在'];
            }
            $industry_code = $param['industry_code'];
        }else{
            $industry_code = '';
        }

        $data = [
            'classify_id' => $param['classify_id'],
            'classify_name' => $param['classify_name'],
            'gov_name' => $param['gov_name'],
            'industry_code' => $industry_code,
            'jinhua_full_name' => $param['jinhua_full_name'],
            'jinhua_short_name' => $param['jinhua_short_name'],
            'yiwu_name' => $param['yiwu_name'],
            'check_frequency' => $param['check_frequency'],
            'check_frequency_text' => $param['check_frequency_text'],
            'sort' => $param['sort'],
            'remark' => isset($param['remark']) ? $param['remark'] : '',
        ];
        try {
            $this->dao->save($data);
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }
    
    public function updateService($param, $id)
    {
        $company_staff_classify = $this->dao->get($id);
        if($company_staff_classify == null) {
            return ['status' => 0, 'msg' => '员工类型不存在'];
        }
        // if(isset($param['industry_code']) && $param['industry_code'] != '') {
        //     $classify = $this->dao->get([['industry_code', '=', $param['industry_code']], ['id', '<>', $id]]);
        //     if($classify) {
        //         return ['status' => 0, 'msg' => '行业编码已存在'];
        //     }
        //     $industry_code = $param['industry_code'];
        // }else{
        //     $industry_code = '';
        // }

        $data = [
            'classify_id' => $param['classify_id'],
            'classify_name' => $param['classify_name'],
            'gov_name' => $param['gov_name'],
            'industry_code' => $param['industry_code'],
            'jinhua_full_name' => $param['jinhua_full_name'],
            'jinhua_short_name' => $param['jinhua_short_name'],
            'yiwu_name' => $param['yiwu_name'],
            'check_frequency' => $param['check_frequency'],
            'check_frequency_text' => $param['check_frequency_text'],
            'sort' => $param['sort'],
            'remark' => isset($param['remark']) ? $param['remark'] : '',
        ];
        try {
            $this->dao->update($id, $data);
            if($company_staff_classify['yiwu_name'] != $param['yiwu_name'] || $company_staff_classify['check_frequency'] != $param['check_frequency']) {
                app()->make(CompanyStaffDao::class)->update(['staff_classify_id'=> $id], ['staff_classify_name'=> $param['yiwu_name'], 'check_frequency'=> $param['check_frequency']]);
            }
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }

}
