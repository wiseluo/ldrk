<?php
declare (strict_types=1);

namespace app\services\admin;

use app\services\user\BaseServices;
use app\dao\GateDao;
use app\dao\GateFactoryDao;
use think\facade\Db;

class GateServices extends BaseServices
{
    public function __construct(GateDao $dao)
    {
        $this->dao = $dao;
    }

    public function getList($param)
    {
        return $this->dao->getList($param);
    }


    public function saveService($param)
    {
        $gate_fatcory = null;
        // if(isset($param['gate_factory_code'])){
        //     $gate_fatcory = app(GateFactoryDao::class)->get(['code'=>$param['gate_factory_code']]);
        // }
        if(isset($param['app_key'])){
            $gate_fatcory = app(GateFactoryDao::class)->get(['key'=>$param['app_key']]);
        }
        if(!$gate_fatcory){
            return ['status' => 0, 'msg' => '错误的厂商app_key'];
        }

        $code = randomCode(12);
        $data = [
            'code' => $code,
            'gate_factory_code' => $gate_fatcory['code'],
            'gate_factory_name' => $gate_fatcory['name'],
            //
            'name' => $param['name'],
            'link_man' => $param['link_man'],
            'link_phone' => $param['link_phone'],
            'addr' => $param['addr'],
            'center' => isset($param['center']) ? $param['center'] : '',
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
            'link_man' => $param['link_man'],
            'link_phone' => $param['link_phone'],
            'addr' => $param['addr'],
            'center' => isset($param['center']) ? $param['center'] : '',
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


    public function batchSaveService($param){
        $batch_data = $param['batch_data'];
        $link_man = isset($param['link_man'])? $param['link_man'] : '';
        $link_phone = isset($param['link_phone'])? $param['link_phone'] : '';
        $app_key = isset($param['app_key'])? $param['app_key'] : '';

        $batch_data = json_decode($batch_data,true);
        if($batch_data){
            Db::startTrans();
            try {
                foreach($batch_data as $key => $value){
                    $save_item[$key] = [];
                    
                    $save_item[$key]['name'] = $value['name'];
                    $save_item[$key]['addr'] = $value['addr'];
                    $save_item[$key]['link_man'] = isset($value['link_man']) ? $value['link_man'] : $link_man;
                    $save_item[$key]['link_phone'] = isset($value['link_phone']) ? $value['link_phone'] : $link_phone;
                    $save_item[$key]['center'] = isset($value['center']) ? $value['center'] : '';
                    $save_item[$key]['app_key'] = isset($value['app_key']) ? $value['app_key'] : $app_key;
        
                    $res[$key] = $this->saveService($save_item[$key]);
                    
                    if($res[$key]['status'] == 0){
                        Db::rollBack();
                        return ['status' => 0, 'msg' => $res[$key]['msg']];
                    }

                }
                Db::commit();
                return ['status' => 1, 'msg' => '成功'];
            } catch (\Exception $e) {
                Db::rollBack();
                return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
            }
        }else{
            return ['status' => 0, 'msg' => '无batch_data'];
        }
    }

}
