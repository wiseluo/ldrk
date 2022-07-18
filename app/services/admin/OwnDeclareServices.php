<?php
declare (strict_types=1);

namespace app\services\admin;

use app\services\user\BaseServices;
use app\dao\OwnDeclareDao;
use think\facade\Db;

class OwnDeclareServices extends BaseServices
{
    public function __construct(OwnDeclareDao $dao)
    {
        $this->dao = $dao;
    }

    public function getList($param)
    {
        return $this->dao->getList($param);
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

    public function getDataError($param,$declare_type='leave'){
        return $this->dao->getDataError($param,$declare_type);
    }

    public function getDataWarning($param,$warning_type='backouttime'){
        return $this->dao->getDataWarning($param,$warning_type);
    }

    public function getStatistic($param,$statistic_type='fromwhere'){
        return $this->dao->getStatistic($param,$statistic_type);
    }
    public function getTodayMany($param){
        return $this->dao->getTodayMany($param);
    }

    public function travelAsteriskService($param)
    {
        return $this->dao->getTravelAsterisk($param);
    }
    
    public function jkmmztService($param)
    {
        return $this->dao->getJkmmzt($param);
    }

    // 归档前3天的数据
    public function archivePre3Day(){
        // 
        // $archivedao = app()->make();
        $data = $this->dao->getPre3DayDateNums();
        $total_new = 0;
        $create_time = time();
        foreach($data as $key => $value){
            $has[$key] = Db::name('declare_date_nums')->where('date','=',$value['date'])->where('province','=',$value['province'])->find();
            if(!$has[$key]){
                $value['create_time'] = $create_time;
                Db::name('declare_date_nums')->save($value);
                $total_new++;
            }
        }
        return ['status'=>1,'msg'=>'ok','data'=>['total_new'=>$total_new]];
    }

    public function recentComeList($param)
    {
        return $this->dao->getRecentComeList($param);
    }
}
