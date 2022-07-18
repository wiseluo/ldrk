<?php
declare (strict_types=1);

namespace app\services\admin;

use app\services\user\BaseServices;
use app\dao\PlaceDao;
use app\dao\DistrictDao;
use app\dao\PlaceTypeDao;
use think\facade\Config;
use \behavior\WechatAppletTool;
use think\facade\Cache;
use app\services\SgzxServices;
use behavior\PubTool;
use crmeb\services\SwooleTaskService;

class PlaceServices extends BaseServices
{
    public function __construct(PlaceDao $dao)
    {
        $this->dao = $dao;
    }

    public function getList($param)
    {
        return $this->dao->getList($param);
    }

    public function deleteListService($param)
    {
        return $this->dao->getDeleteList($param);
    }

    public function readService($id)
    {
        $place = $this->dao->get($id);
        if($place) {
            $place->append(['place_classify_text','applet_qrcode_arr']);
            $place['applet_qrcode'] = str_replace('_qrcode.png', '_qrcode_v2.png', $place['applet_qrcode']);
            return ['status' => 1, 'msg'=> '成功', 'data'=> $place];
        }else{
            return ['status' => 0, 'msg'=> '不存在'];
        }
    }
    
    public function getTypeList($param)
    {
        return app()->make(PlaceTypeDao::class)->getList($param);
    }

    public function saveService($param)
    {
        $place_type_id = $param['place_type_id'];
        $place_type = $param['place_type'];
        if($param['place_classify'] == 'gov') {
            $place_type_id = 32;
            $place_type = '党政机关';
        }else if($param['place_classify'] == 'unit') {
            $place_type_id = 33;
            $place_type = '事业单位';
        }else if($param['place_classify'] == 'social') {
            $place_type_id = 34;
            $place_type = '社会组织';
        }
        $name = trim($param['name']);
        $short_name = trim($param['short_name']);
        if($param['place_classify'] == 'company') {
            $sgzx_res = app()->make(SgzxServices::class)->enterpriseInfo($param['credit_code']);
            if($sgzx_res['status'] == 0) {
                return ['status'=> 0, 'msg'=> '社会信用代码证错误'];
            }
            if($sgzx_res['data']['companyName'] != $name) {
                return ['status'=> 0, 'msg'=> '名称需与营业执照中的名称一致：'.$sgzx_res['data']['companyName']];
            }
        }
        $code = randomCode(12);
        $wechatAppletTool = new WechatAppletTool();
        $applet_qrcode = $wechatAppletTool->appletPlaceQrcode($code, $short_name);
        if($applet_qrcode['status'] == 0) {
            return ['status' => 0, 'msg' => '操作失败-'. $applet_qrcode['msg']];
        }
        $yw_street = app()->make(DistrictDao::class)->getNameById($param['yw_street_id']);
        $data = [
            'code' => $code,
            'applet_qrcode' => $applet_qrcode['data'],
            'name' => $name,
            'short_name' => $short_name,
            'yw_street_id' => $param['yw_street_id'],
            'yw_street' => $yw_street,
            'addr' => $param['addr'],
            'superior_gov' => $param['superior_gov'],
            'place_classify' => $param['place_classify'],
            'place_type_id' => $place_type_id,
            'place_type' => $place_type,
            'community' => isset($param['community']) ? $param['community'] : '',
            'link_man' => $param['link_man'],
            'link_phone' => $param['link_phone'],
            'credit_code' => isset($param['credit_code']) ? $param['credit_code'] : '',
            'source' => 'admin',
            'remark' => isset($param['remark']) ? $param['remark'] : '',
            'is_need_face' => isset($param['is_need_face']) ? $param['is_need_face'] : 0,
            'xcm_level' => isset($param['xcm_level']) ? $param['xcm_level'] : 1,
            'watermark_url' => isset($param['watermark_url']) ? $param['watermark_url'] : '',
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
        $place = $this->dao->get($id);
        if($place == false) {
            return ['status' => 0, 'msg' => '该场所不存在'];
        }
        $place_type_id = $param['place_type_id'];
        $place_type = $param['place_type'];
        if($param['place_classify'] == 'gov') {
            $place_type_id = 32;
            $place_type = '党政机关';
        }else if($param['place_classify'] == 'unit') {
            $place_type_id = 33;
            $place_type = '事业单位';
        }else if($param['place_classify'] == 'social') {
            $place_type_id = 34;
            $place_type = '社会组织';
        }
        if($param['place_classify'] == 'company') {
            $sgzx_res = app()->make(SgzxServices::class)->enterpriseInfo($param['credit_code']);
            if($sgzx_res['status'] == 0) {
                return ['status'=> 0, 'msg'=> '社会信用代码证错误'];
            }
            if($sgzx_res['data']['companyName'] != $param['name']) {
                return ['status'=> 0, 'msg'=> '名称需与营业执照中的名称一致：'.$sgzx_res['data']['companyName']];
            }
        }
        $districtDao = app()->make(DistrictDao::class);
        $data = [
            'name' => $param['name'],
            'short_name' => $param['short_name'],
            'yw_street_id' => $param['yw_street_id'],
            'yw_street' => $districtDao->getNameById($param['yw_street_id']),
            'addr' => $param['addr'],
            'superior_gov' => $param['superior_gov'],
            'community' => isset($param['community']) ? $param['community'] : '',
            'place_classify' => $param['place_classify'],
            'place_type_id' => $place_type_id,
            'place_type' => $place_type,
            'link_man' => $param['link_man'],
            'link_phone' => $param['link_phone'],
            'credit_code' => isset($param['credit_code']) ? $param['credit_code'] : '',
            'remark' => isset($param['remark']) ? $param['remark'] : '',
            'is_need_face' => isset($param['is_need_face']) ? $param['is_need_face'] : 0,
            'xcm_level' => isset($param['xcm_level']) ? $param['xcm_level'] : 1,
            'watermark_url' => isset($param['watermark_url']) ? $param['watermark_url'] : '',
        ];
        try {
            PubTool::publish('clearAllCache',[]);
            // Cache::delete('place_code_'.$place['code']);
            // test_log('清空：place_code_'.$place['code']);
            $this->dao->update($id, $data);
            if($param['short_name'] != $place['short_name']) {
                SwooleTaskService::place()->taskType('place')->data(['action'=>'placeQrcodeWatermarkService','param'=> ['file_path' => $place['applet_qrcode'], 'version' => 'v2', 'short_name'=> $place['short_name']]])->push();
            }
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }
    
    public function deleteService($id)
    {
        $place = $this->dao->get($id);
        if($place == false) {
            return ['status' => 0, 'msg' => '该场所不存在'];
        }
        try {
            $this->dao->softDelete($id);
            Cache::delete('place_code_'.$place['place_code']);
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }
    
    public function restoreService($id)
    {
        $place = $this->dao->getWithTrashed($id);
        if($place == false) {
            return ['status' => 0, 'msg' => '该场所不存在'];
        }else if($place['delete_time'] == null) {
            return ['status' => 0, 'msg' => '该场所未删除，不需修复'];
        }
        try {
            $this->dao->restore(['id'=> $id]);
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }

    public function batchUpdateXcmLevel($param){
        $xcm_level = $param['xcm_level'];
        $ids = $param['ids'];
        $ids_arr = explode(',',$ids);
        if(count($ids_arr) == 0){
            return ['status' => 0, 'msg' => '场所ids不能为空'];
        }
        try {
            $this->dao->update([['id','in',$ids_arr]],['xcm_level'=>$xcm_level]);
            PubTool::publish('clearAllCache',[]);
            return ['status' => 1, 'msg' => '成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }

    
    public function batchUpdateIsNeedFace($param){
        $is_need_face = $param['is_need_face'];
        $ids = $param['ids'];
        $ids_arr = explode(',',$ids);
        if(count($ids_arr) == 0){
            return ['status' => 0, 'msg' => '场所ids不能为空'];
        }
        try {
            $this->dao->update([['id','in',$ids_arr]],['is_need_face'=>$is_need_face]);
            PubTool::publish('clearAllCache',[]);
            return ['status' => 1, 'msg' => '成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }

}
