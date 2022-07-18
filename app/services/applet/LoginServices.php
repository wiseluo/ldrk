<?php

namespace app\services\applet;

use app\services\applet\BaseServices;
use \behavior\WechatAppletTool;
use app\dao\UserDao;
use app\services\SgzxServices;
use think\facade\Cache;
use crmeb\services\SwooleTaskService;
use \behavior\SsjptTool;

class LoginServices extends BaseServices
{
    public function __construct(UserDao $dao)
    {
        $this->dao = $dao;
    }

    public function loginService($param)
    {
        // $user = $this->dao->get(['phone'=> '18072697839']);
        // $token = $this->createTokenV2($user, 'applet');
        // return ['status'=> 1, 'msg'=> '登录成功', 'data'=> ['token'=> $token['token']]];

        $wechatAppletTool = new WechatAppletTool();
        $info_res = $wechatAppletTool->getJscode2sessionWxApi($param['code']);
        if ($info_res['status'] == 0) {
            return ['status' => 0, 'msg' => $info_res['msg']];
        }
        $openid = $info_res['data']['openid'];
        $user = $this->dao->get(['openid' => $openid]);
        if ($user) {
            $token = $this->createTokenV2($user, 'applet');
            return ['status' => 1, 'msg' => '登录成功', 'data' => ['openid' => $openid, 'token' => $token['token']]];
        } else {
            return ['status' => 2, 'msg' => '账号不存在， 请先注册', 'data' => ['openid' => $openid]];
        }
    }

    // public function test($uid)
    // {
    //     $user = $this->dao->get((int)$uid);
    //     $token = $this->createTokenV2($user, 'applet');
    //     return ['status'=> 1, 'msg'=> '登录成功', 'data'=> ['token'=> $token['token']]];
    // }

    public function codePhoneService($code)
    {
        $wechatAppletTool = new WechatAppletTool();
        $phone_res = $wechatAppletTool->getUserPhone($code);
        if ($phone_res['status'] == 0) {
            return ['status' => 0, 'msg' => $phone_res['msg']];
        }
        $phone = $phone_res['data']['phoneNumber'];
        return ['status' => 1, 'msg' => '成功', 'data' => $phone];
    }

    public function encryptedPhoneService($code, $encryptedData, $iv)
    {
        $wechatAppletTool = new WechatAppletTool();
        $info_res = $wechatAppletTool->getJscode2sessionWxApi($code);
        if ($info_res['status'] == 0) {
            return ['status' => 0, 'msg' => $info_res['msg']];
        }
        //$openid = $info_res['data']['openid'];
        $session_key = $info_res['data']['session_key'];
        $phone_res = $wechatAppletTool->decryptData($session_key, $encryptedData, $iv);
        if ($phone_res['status'] == 0) {
            return ['status' => 0, 'msg' => $phone_res['msg']];
        }
        $phone = $phone_res['data']['phoneNumber'];
        return ['status' => 1, 'msg' => '成功', 'data' => $phone];
    }

    public function autoRegisterV2Service($param, $type)
    {
        if ($type == 'authphone') {
            $phone_res = $this->codePhoneService($param['code']);
        } else if ($type == 'encryptedphone') {
            $phone_res = $this->encryptedPhoneService($param['code'], $param['encryptedData'], $param['iv']);
        } else {
            return ['status' => 0, 'msg' => '参数错误'];
        }
        if ($phone_res['status'] == 0) {
            return ['status' => 0, 'msg' => $phone_res['msg']];
        }
        $phone = $phone_res['data'];

        $ssjptTool = new SsjptTool();
        $sk_res =  $ssjptTool->skPhoneToJkm($phone);
        if ($sk_res['status'] == 0) {
            return ['status' => 2, 'msg' => '查询身份失败，请手动注册', 'data' => ['phone' => $phone, 'openid' => $param['openid']]];
        }
        // if(count($sk_res['data']) > 1) { //查询到多个人
        $card = [];
        foreach ($sk_res['data'] as $v) {
            $card[] = [
                'name' => $v['xm'],
                'id_card' => $v['sfzh'],
            ];
        }
        return ['status' => 3, 'msg' => '请选择一个身份进行注册', 'data' => ['phone' => $phone, 'openid' => $param['openid'], 'card' => $card]];
        // }
        //查询到一个人，自动注册用户
        $userDao = app()->make(UserDao::class);
        $user = $userDao->get(['id_card' => $sk_res['data'][0]['sfzh']]);
        $user_data = [
            'openid' => $param['openid'],
            'phone' => $phone,
            'real_name' => $sk_res['data'][0]['xm'],
            'id_card' => $sk_res['data'][0]['sfzh'],
            'card_type' => 'id',
            'id_verify_result' => 1,
        ];
        if ($user == null) {
            $uniqid = randomCode(12);
            $user_data['uniqid'] = $uniqid;
            $user = $userDao->save($user_data);
        } else {
            $uniqid = $user['uniqid'];
            $user->save($user_data);
        }
        //生成二维码图片
        // $qrcode_color = 'green';
        // if($user_data['jkm_mzt'] == '绿码') {
        //     $qrcode_color = 'green';
        // }else if($user_data['jkm_mzt'] == '黄码') {
        //     $qrcode_color = 'yellow';
        // }else if($user_data['jkm_mzt'] == '红码') {
        //     $qrcode_color = 'red';
        // }
        // SwooleTaskService::place()->taskType('place')->data(['action'=>'userQrcodeTaskService','param'=> ['uniqid'=> $uniqid, 'qrcode_color'=> $qrcode_color]])->push();
        $token = $this->createTokenV2($user, 'applet');
        return ['status' => 1, 'msg' => '登录成功', 'data' => ['token' => $token['token']]];
    }

    //手动注册用户
    public function registerService($param)
    {
        $user_data = [
            'openid' => $param['openid'],
            'phone' => $param['phone'],
            'real_name' => $param['real_name'],
            'id_card' => $param['id_card'],
            'card_type' => 'id',
        ];
        try {
            $user = $this->dao->get(['id_card' => $param['id_card']]);
            if ($user == null) {
                $user_data['uniqid'] = randomCode(12);
                $user = $this->dao->save($user_data);
            } else {
                $user->save($user_data);
            }
            $token = $this->createTokenV2($user, 'applet');
            return ['status' => 1, 'msg' => '登录成功', 'data' => ['token' => $token['token']]];
        } catch (\Exception $e) {
            test_log($e->getMessage());
            return ['status' => 0, 'msg' => '操作失败-' . $e->getMessage()];
        }
    }

    public function registerIdcardRecognService($param)
    {
        $user_data = [
            'real_name' => $param['real_name'],
            'id_card' => $param['id_card'],
            'card_type' => 'id',
        ];
        try {
            $user = $this->dao->get(['id_card' => $param['id_card']]);
            if ($user == null) {
                $uniqid = randomCode(12);
                $user_data['uniqid'] = $uniqid;
                $user = $this->dao->save($user_data);
                $xcm_get = 0;
            } else {
                $uniqid = $user['uniqid'];
                $user->save($user_data);
                if (time() - strtotime($user['xcm_gettime']) > 86400) { //超过一天
                    $xcm_get = 0;
                } else {
                    $xcm_get = 1;
                }
            }
            return ['status' => 1, 'msg' => '成功', 'data' => ['uniqid' => $uniqid, 'phone' => $user['phone'], 'xcm_get' => $xcm_get]];
        } catch (\Exception $e) {
            test_log('registerIdcardRecognService-' . $e->getMessage());
            return ['status' => 0, 'msg' => '操作失败-' . $e->getMessage()];
        }
    }
}
