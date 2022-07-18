<?php

namespace app\services\admin;

use app\services\user\BaseServices;
use app\dao\PersonalCodeDao;
use crmeb\services\SwooleTaskService;

class PersonalCodeServices extends BaseServices
{
    public function __construct(PersonalCodeDao $dao)
    {
        $this->dao = $dao;
    }

    public function getListService($param)
    {
        return $this->dao->getList($param);
    }

    public function updateService($param, $id)
    {
        $code = $this->dao->get($id);
        if($code == false) {
            return ['status' => 0, 'msg' => '该个人码不存在'];
        }
        $data = [
            'real_name' => $param['real_name'],
            'phone' => $param['phone'],
            'urgent_phone' => $param['urgent_phone'],
            'agent_name' => $param['agent_name'],
            'agent_idcard' => $param['agent_idcard'],
            'agent_phone' => $param['agent_phone'],
        ];
        try {
            $this->dao->update($id, $data);
            $name = $param['real_name'] .'-'. $param['urgent_phone'];
            SwooleTaskService::company()->taskType('personalcode')->data(['action'=>'personalQrcodeWatermarkService','param'=> ['file_path' => $code['qrcode'], 'version' => 'v2', 'name'=> $name]])->push();
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }

    public function deleteService($id)
    {
        $code = $this->dao->get($id);
        if($code == null) {
            return ['status' => 0, 'msg' => '个人码不存在'];
        }
        try {
            $this->dao->delete($id);
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }

}
