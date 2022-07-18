<?php

namespace behavior;

use Curl\Curl;
use think\facade\Cache;
use think\facade\Config;
use app\dao\WxtokenDao;
use \behavior\WxBizDataCrypt;
use crmeb\services\SwooleTaskService;

class WechatAppletTool
{
    private $appid = ''; //小程序唯一标识
    private $app_secret = ''; //小程序密钥

    public function __construct()
    {
        $this->appid = config('wechat.applet_app_id');
        $this->app_secret = config('wechat.applet_app_secret');
    }

    public function getAccessToken()
    {
        $wxtoken = app()->make(WxtokenDao::class);
        $access_token = $wxtoken->get(['type' => 'applet']);
        if (Config::get('app.app_host') == 'dev') { //测试服务器
            if ($access_token == null) {
                $token_res = $this->setAccessToken();
                if ($token_res['status']) {
                    return ['status' => 1, 'msg' => '成功', 'data' => $token_res['data']];
                }
            } else if (time() > $access_token['expires']) {
                $token_res = $this->setAccessToken();
                if ($token_res['status']) {
                    return ['status' => 1, 'msg' => '成功', 'data' => $token_res['data']];
                }
            } else {
                return ['status' => 1, 'msg' => '成功', 'data' => $access_token['access_token']];
            }
            return ['status' => 0, 'msg' => $token_res['msg']];
        }
        if ($access_token) {
            return ['status' => 1, 'msg' => '成功', 'data' => $access_token['access_token']];
        } else {
            test_log('微信access_token不存在，定时任务未设置');
            return ['status' => 0, 'msg' => 'access_token不存在'];
        }
    }

    //多服务器不能缓存到本服务器，使用数据库保存，用定时任务更新
    public function setAccessToken()
    {
        $wxtoken = app()->make(WxtokenDao::class);
        $access_token = $wxtoken->get(['type' => 'applet']);
        $new_token_res = $this->appletAccessToken();
        if ($new_token_res['status'] == 0) {
            return ['status' => 0, 'msg' => $new_token_res['msg']];
        }
        //提前5分钟刷新token
        $expires = time() + $new_token_res['data']['expires_in'] - 300;
        $data = [
            'type' => 'applet',
            'access_token' => $new_token_res['data']['access_token'],
            'expires' => $expires,
        ];
        try {
            if ($access_token) {
                $wxtoken->update($access_token['id'], $data);
            } else {
                $wxtoken->save($data);
            }
            return ['status' => 1, 'msg' => '成功', 'data' => $data['access_token']];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => 'access_token缓存失败-' . $e->getMessage()];
        }
    }

    protected function appletAccessToken()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $this->appid . '&secret=' . $this->app_secret;
        $curl = new Curl();
        $curl->get($url);
        if ($curl->error) {
            return ['status' => 0, 'msg' => '微信获取access_token失败'];
        } else {
            $result = json_decode($curl->response, true);
        }
        if (isset($result['errcode'])) {
            return ['status' => 0, 'msg' => '微信接口获取access_token失败-' . $result['errmsg']];
        } else {
            return ['status' => 1, 'msg' => '成功', 'data' => $result];
        }
    }

    /**
     * return jsonObj: openid session_key unionid
     **/
    public function getJscode2sessionWxApi($code)
    {
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid=' . $this->appid . '&secret=' . $this->app_secret . '&js_code=' . $code . '&grant_type=authorization_code';
        $curl = new Curl();
        $curl->get($url);
        if ($curl->error) {
            test_log(Config::get('upload.at_server') . '微信获取授权失败-' . $curl->error . ': ' . $curl->error_message);
            return ['status' => 0, 'msg' => '微信获取授权失败'];
        } else {
            //test_log('微信获取授权-'. $curl->response);
            $result = json_decode($curl->response, true);
        }
        if (isset($result['openid']) && $result['openid'] != '') {
            return ['status' => 1, 'msg' => '成功', 'data' => $result];
        } else {
            test_log(Config::get('upload.at_server') . '微信获取授权失败-' . json_encode($result));
            return ['status' => 0, 'msg' => '微信获取授权失败-' . $result['errmsg']];
        }
    }

    //code换取用户手机号
    public function getUserPhone($code)
    {
        $access_token_res = $this->getAccessToken();
        if ($access_token_res['status'] == 0) {
            return ['status' => 0, 'msg' => $access_token_res['msg']];
        }
        $url = 'https://api.weixin.qq.com/wxa/business/getuserphonenumber?access_token=' . $access_token_res['data'];
        $curl = new Curl();
        $data = [
            'code' => $code,
        ];
        $data_json = json_encode($data);
        $curl->post($url, $data_json);
        if ($curl->error) {
            test_log(Config::get('upload.at_server') . '微信获取用户手机号失败-' . $curl->error . ': ' . $curl->error_message);
            return ['status' => 0, 'msg' => '微信获取用户手机号失败'];
        } else {
            $result = json_decode($curl->response, true);
        }
        //test_log('微信获取用户手机号-'.$code.'-'.$this->appid.'-'.$access_token_res['data'].'-'.Config::get('app.app_host').'-'.$curl->response);
        if (isset($result['errcode']) && $result['errcode'] == 0) {
            return ['status' => 1, 'msg' => '成功', 'data' => $result['phone_info']];
        } else {
            test_log('微信获取用户手机号失败-' . $curl->response);
            return ['status' => 0, 'msg' => '微信获取用户手机号失败-' . $result['errmsg']];
        }
    }

    //加密数据解密算法获取手机号
    public function decryptData($session_key, $encryptedData, $iv)
    {
        $pc = new WxBizDataCrypt($this->appid, $session_key);
        $err_code = $pc->decryptData($encryptedData, $iv, $data);
        if ($err_code == 0) {
            $data_arr = json_decode($data, true);
            return ['status' => 1, 'msg' => '成功', 'data' => $data_arr];
        } else {
            return ['status' => 0, 'msg' => '解密数据失败-' . $err_code];
        }
    }

    //获取实名信息
    public function getrealnameinfo($code)
    {
        $access_token_res = $this->getAccessToken();
        if ($access_token_res['status'] == 0) {
            return ['status' => 0, 'msg' => $access_token_res['msg']];
        }
        $url = 'https://api.weixin.qq.com/cgi-bin/wxopen/getrealnameinfo?access_token=' . $access_token_res['data'];
        $curl = new Curl();
        $cert_serialno = config('wechat.cert_serialno');
        $timestamp = time();
        $ori_content = 'cert_serialno=' . $cert_serialno . '&timestamp=' . $timestamp;

        $private_key_file = '1900006511_rsa_private_key.pem';
        $mch_private_key = openssl_get_privatekey(\file_get_contents($private_key_file));
        if (!openssl_sign($ori_content, $raw_sign, $mch_private_key, 'sha256WithRSAEncryption')) {
            return ['status' => 0, 'msg' => '签名验证过程发生了错误'];
        }
        $sign = base64_encode($raw_sign);
        $data = [
            'auth_token' => $code,
            'mch_id' => config('wechat.mch_id'),
            'cert_serialno' => $cert_serialno,
            'timestamp' => $timestamp,
            'sign' => $sign,
        ];
        $data_json = json_encode($data);
        $curl->post($url, $data_json);
        if ($curl->error) {
            return ['status' => 0, 'msg' => '微信获取实名信息失败'];
        } else {
            var_dump($curl->response);
            $result = json_decode($curl->response, true);

            $public_key_file = '1900006511_rsa_private_key.pem';
            $mch_public_key = openssl_get_publickey(\file_get_contents($public_key_file));
            if (openssl_public_encrypt($result, $encrypted, $mch_public_key, OPENSSL_PKCS1_OAEP_PADDING)) {
                //base64编码 
                $sign = base64_encode($encrypted);
            } else {
                return ['status' => 0, 'msg' => '微信获取实名信息失败-encrypt failed'];
            }
        }
        if (isset($result['errcode']) && $result['errcode'] == 0) {
            return ['status' => 1, 'msg' => '成功', 'data' => $result['phone_info']];
        } else {
            return ['status' => 0, 'msg' => '微信获取实名信息失败-' . $result['errmsg']];
        }
    }

    //生成小程序场所二维码
    public function appletPlaceQrcode($place_code, $name)
    {
        $filename = $place_code . '_qrcode.png';
        $path = make_path(Config::get('upload.at_server') . '/applet_qrcode', 3, true);
        $src = '/uploads' . $path . '/' . $filename;
        $file_path = app()->getRootPath() . 'public' . $src;
        if (!file_exists($file_path)) {
            if (Config::get('app.app_host') == 'dev') { //测试环境
                $optional = ['page' => 'pages/csmXq/index', 'check_path' => false, 'env_version' => 'trial'];
            } else {
                $optional = ['page' => 'pages/csmXq/index', 'check_path' => true, 'env_version' => 'release'];
            }
            $qrcode_res = $this->getwxacodeunlimit($place_code, $optional);
            if ($qrcode_res['status']) {
                $qrcode_res_data = json_decode($qrcode_res['data'], true);
                if (is_null($qrcode_res_data)) {
                    file_put_contents($file_path, $qrcode_res['data']);

                    SwooleTaskService::place()->taskType('place')->data(['action' => 'placeQrcodeWatermarkService', 'param' => ['file_path' => $src, 'version' => 'v2', 'short_name' => $name]])->push();
                    return ['status' => 1, 'msg' => '成功', 'data' => $src];
                } else {
                    return ['status' => 0, 'msg' => '生成二维码图片失败-' . $qrcode_res_data['errmsg']];
                }
            } else {
                return ['status' => 0, 'msg' => $qrcode_res['msg']];
            }
        }
        return ['status' => 0, 'msg' => '二维码图片已存在'];
    }

    //生成小程序企业信息二维码
    public function appletCompanyQrcode($company_code, $name)
    {
        $filename = $company_code . '_qrcode.png';
        $path = make_path(Config::get('upload.at_server') . '/applet_qrcode', 3, true);
        $src = '/uploads' . $path . '/' . $filename;
        $file_path = app()->getRootPath() . 'public' . $src;
        if (!file_exists($file_path)) {
            if (Config::get('app.app_host') == 'dev') { //测试环境
                $optional = ['page' => 'pages/home/companySaoma', 'check_path' => false, 'env_version' => 'trial'];
            } else {
                $optional = ['page' => 'pages/home/companySaoma', 'check_path' => true, 'env_version' => 'release'];
            }
            $scene = 'company' . $company_code;
            $qrcode_res = $this->getwxacodeunlimit($scene, $optional);
            if ($qrcode_res['status']) {
                $qrcode_res_data = json_decode($qrcode_res['data'], true);
                if (is_null($qrcode_res_data)) {
                    file_put_contents($file_path, $qrcode_res['data']);

                    SwooleTaskService::company()->taskType('company')->data(['action' => 'companyQrcodeWatermarkService', 'param' => ['file_path' => $src, 'version' => 'v2', 'name' => $name]])->push();
                    return ['status' => 1, 'msg' => '成功', 'data' => $src];
                } else {
                    return ['status' => 0, 'msg' => '生成二维码图片失败-' . $qrcode_res_data['errmsg']];
                }
            } else {
                return ['status' => 0, 'msg' => $qrcode_res['msg']];
            }
        }
        return ['status' => 0, 'msg' => '二维码图片已存在'];
    }

    // 生成小程序学校信息二维码
    public function appletSchoolQrcode($school_code, $name)
    {
        $filename = $school_code . '_qrcode.png';
        $path = make_path(Config::get('upload.at_server') . '/applet_qrcode', 3, true);
        $src = '/uploads' . $path . '/' . $filename;
        $file_path = app()->getRootPath() . 'public' . $src;
        if (!file_exists($file_path)) {
            if (Config::get('app.app_host') == 'dev') { //测试环境
                $optional = ['page' => 'pages/schoolCode/schoolSaoma/index', 'check_path' => false, 'env_version' => 'trial'];
            } else {
                $optional = ['page' => 'pages/schoolCode/schoolSaoma/index', 'check_path' => true, 'env_version' => 'release'];
            }
            $scene = 'school' . $school_code;
            $qrcode_res = $this->getwxacodeunlimit($scene, $optional);
            if ($qrcode_res['status']) {
                $qrcode_res_data = json_decode($qrcode_res['data'], true);
                if (is_null($qrcode_res_data)) {
                    file_put_contents($file_path, $qrcode_res['data']);

                    SwooleTaskService::school()->taskType('school')->data(['action' => 'schoolQrcodeWatermarkService', 'param' => ['file_path' => $src, 'version' => 'v2', 'name' => $name]])->push();
                    return ['status' => 1, 'msg' => '成功', 'data' => $src];
                } else {
                    return ['status' => 0, 'msg' => '生成二维码图片失败-' . $qrcode_res_data['errmsg']];
                }
            } else {
                return ['status' => 0, 'msg' => $qrcode_res['msg']];
            }
        }
        return ['status' => 0, 'msg' => '二维码图片已存在'];
    }

    /*
     * scene 最大32个可见字符，只支持数字，大小写英文以及部分特殊字符：!#$&'()*+,/:;=?@-._~，其它字符请自行编码为合法字符（因不支持%，中文无法使用 urlencode 处理，请使用其他编码方式）
     * page 页面 page，例如 pages/index/index，根路径前不要填加 /，不能携带参数（参数请放在scene字段里），如果不填写这个字段，默认跳主页面
     * check_path 检查page 是否存在，为 true 时 page 必须是已经发布的小程序存在的页面（否则报错）；为 false 时允许小程序未发布或者 page 不存在， 但page 有数量上限（60000个）请勿滥用
     * env_version 要打开的小程序版本。正式版为 "release"，体验版为 "trial"，开发版为 "develop"
    */
    public function getwxacodeunlimit(string $scene, array $optional = [])
    {
        $access_token_res = $this->getAccessToken();
        if ($access_token_res['status'] == 0) {
            return ['status' => 0, 'msg' => $access_token_res['msg']];
        }
        $url = 'https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=' . $access_token_res['data'];
        $curl = new Curl();
        $data = array_merge([
            'scene' => $scene,
        ], $optional);
        $data_json = json_encode($data);
        $curl->post($url, $data_json);
        if ($curl->error) {
            test_log('微信获取小程序码失败-' . $curl->error . ': ' . $curl->error_message);
            return ['status' => 0, 'msg' => '微信获取小程序码失败'];
        } else {
            //test_log('微信获取小程序码成功-'.$curl->response);
            return ['status' => 1, 'msg' => '成功', 'data' => $curl->response];
        }
    }
}
