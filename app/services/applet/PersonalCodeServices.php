<?php

namespace app\services\applet;

use app\services\applet\BaseServices;
use app\dao\PersonalCodeDao;
use app\dao\UserDao;
use \behavior\IdentityCardTool;
use \behavior\QrcodeTool;
use think\facade\Config;
use crmeb\services\SwooleTaskService;
use \behavior\SmsVerifyTool;
use think\facade\Db;

class PersonalCodeServices extends BaseServices
{
    public function __construct(PersonalCodeDao $dao)
    {
        $this->dao = $dao;
    }

    public function getListService($param, $id_card)
    {
        return $this->dao->getUserPersonalCodeList($param, $id_card);
    }

    public function readService($id)
    {
        $code = $this->dao->get($id);
        if($code) {
            $code->append(['personal_qrcode_arr']);
            return ['status' => 1, 'msg'=> '成功', 'data'=> $code];
        }else{
            return ['status' => 0, 'msg'=> '不存在'];
        }
    }
    
    public function saveService($param, $userInfo)
    {
        if($param['phone'] != '') {
            if(!isset($param['sms_code']) || $param['sms_code'] == '') {
                return ['status' => 0, 'msg' => '手机验证码必填'];
            }
            $smsVerifyTool = new SmsVerifyTool();
            $sms_res = $smsVerifyTool->verifySmsCode('personal_code', $param['phone'], $param['sms_code']);
            if($sms_res['status'] == 0) {
                return ['status' => 0, 'msg' => $sms_res['msg']];
            }
        }
        if($param['id_card'] == $userInfo['id_card']) {
            return ['status' => 0, 'msg' => '不能代领自己的个人码'];
        }
        if($param['phone'] == $userInfo['phone']) {
            return ['status' => 0, 'msg' => '不能填代领人自己的手机号'];
        }
        $code = $this->dao->get(['id_card'=> $param['id_card']]);
        if($code) {
            return ['status' => 0, 'msg' => '该用户已申请过个人防疫码，不能重复申请'];
        }
        $userDao = app()->make(UserDao::class);
        $user = $userDao->get(['id_card'=> $param['id_card']]);
        if($user) {
            $uniqid = $user['uniqid'];
        }else{
            $uniqid = randomCode(12);
        }
        $qrcodeTool = new QrcodeTool();
        $filename = $uniqid .'_personal_qrcode.png';
        $path = make_path(Config::get('upload.at_server') . '/personal_qrcode', 3, true);
        $src = '/uploads'. $path .'/'. $filename;
        $file_path = app()->getRootPath() .'public'. $src;
        $qrcodeTool->saveToFile(Config::get('app.app_domain'). '/personal_code?uniqid='. $uniqid, '', $file_path);
        $code_data = [
            'uniqid' => $uniqid,
            'phone' => $param['phone'],
            'urgent_phone' => $param['urgent_phone'],
            'real_name' => $param['real_name'],
            'id_card' => $param['id_card'],
            'gender' => IdentityCardTool::getSex($param['id_card']),
            'agent_name' => $userInfo['real_name'],
            'agent_idcard' => $userInfo['id_card'],
            'agent_phone' => $userInfo['phone'],
            'qrcode' => $src,
        ];
        Db::startTrans();
        try {
            if($user == null) {
                $user_data = [
                    'uniqid' => $uniqid,
                    'phone' => $param['phone'],
                    'real_name' => $param['real_name'],
                    'id_card' => $param['id_card'],
                    'gender' => IdentityCardTool::getSex($param['id_card']),
                    'card_type' => 'id',
                ];
                $userDao->save($user_data);
            }
            $code_res = $this->dao->save($code_data);
            Db::commit();
            $name = $param['real_name'] .'-'. $param['urgent_phone'];
            SwooleTaskService::company()->taskType('personalcode')->data(['action'=>'personalQrcodeWatermarkService','param'=> ['file_path' => $src, 'version' => 'v2', 'name'=> $name]])->push();
            return ['status' => 1, 'msg' => '操作成功', 'data'=> ['id'=> $code_res->id]];
        } catch (\Exception $e) {
            Db::rollback();
            return ['status' => 0, 'msg' => '服务器异常，请再次提交'];
            //return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }

    public function updateService($id, $param, $user)
    {
        $code = $this->dao->get($id);
        if($code == false) {
            return ['status' => 0, 'msg' => '该个人码不存在'];
        }
        if($code['agent_idcard'] != $user['id_card']) {
            return ['status' => 0, 'msg' => '非本人代领的个人码，不能修改'];
        }
        if($param['phone'] == $user['phone']) {
            return ['status' => 0, 'msg' => '不能填代领人自己的手机号'];
        }
        if($param['phone'] != '') {
            if(!isset($param['sms_code']) || $param['sms_code'] == '') {
                return ['status' => 0, 'msg' => '手机验证码必填'];
            }
            $smsVerifyTool = new SmsVerifyTool();
            $sms_res = $smsVerifyTool->verifySmsCode('personal_code', $param['phone'], $param['sms_code']);
            if($sms_res['status'] == 0) {
                return ['status' => 0, 'msg' => $sms_res['msg']];
            }
        }
        $data = [
            'real_name' => $param['real_name'],
            'phone' => $param['phone'],
            'urgent_phone' => $param['urgent_phone'],
        ];
        try {
            $this->dao->update($id, $data);
            $name = $param['real_name'] .'-'. $param['urgent_phone'];
            SwooleTaskService::company()->taskType('personalcode')->data(['action'=>'personalQrcodeWatermarkService','param'=> ['file_path' => $code['qrcode'], 'version' => 'v2', 'name'=> $name]])->push();
            return ['status' => 1, 'msg' => '操作成功', 'data'=> ['id'=> $id]];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }
    
}
