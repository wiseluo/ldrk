<?php
declare (strict_types=1);

namespace app\services\admin;

use app\services\user\BaseServices;
use app\dao\GateFactoryDao;
// use app\dao\DistrictDao;
// use app\dao\PlaceTypeDao;
// use think\facade\Config;
// use \behavior\WechatAppletTool;
// use think\facade\Cache;
// use app\services\SgzxServices;
use crmeb\services\SwooleTaskService;

class GateFactoryServices extends BaseServices
{
    public function __construct(GateFactoryDao $dao)
    {
        $this->dao = $dao;
    }

    public function getList($param)
    {
        return $this->dao->getList($param);
    }


    public function saveService($param)
    {
        $code = randomCode(12);
        $key =  'yw'.substr(md5('key'.$code),3,16);
        $secret = md5('secret'.$code);

        $data = [
            'code' => $code,
            'name' => $param['name'],
            'key' => $key,
            'secret' => $secret,
            'link_man' => $param['link_man'],
            'link_phone' => $param['link_phone'],
            'white_ips' => $param['white_ips'],
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
        $data = [
            'name' => $param['name'],
            // 'key' => $param['key'], // 不提供修改,需另开接口,同时发送给联系人
            // 'secret' => $param['secret'], // 不提供修改,需另开接口,同时发送给联系人
            'link_man' => $param['link_man'],
            'link_phone' => $param['link_phone'],
            'white_ips' => $param['white_ips'],
        ];
        try {
            $this->dao->update($id, $data);
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }
    
    public function deleteService($id)
    {
        $place = $this->dao->get($id);
        if($place == false) {
            return ['status' => 0, 'msg' => '不存在'];
        }
        try {
            $this->dao->softDelete($id);
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }

}
