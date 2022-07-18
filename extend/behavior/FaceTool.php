<?php

namespace behavior;

use Curl\Curl;
use think\facade\Config;
use app\dao\WxtokenDao;

// 人脸识别工具类
class FaceTool
{
    //义乌市疫情系统
    private $app_key = 'jzz558907ec64bb4b51d15ab8ec32f34';
    private $app_secret = 'e4813bf957b94867a68aaa7cc627c847';
    private $deskey = '047ffeb349c243e59163a2deaa2cd1ba';

    /**
     * 获取token1小时一次，用定时任务更新
     */
    public function timeToGetToken()
    {
        $wxtoken = app()->make(WxtokenDao::class);
        $token = $wxtoken->get(['type'=> 'face_token']);

        $new_token_res = $this->apiFaceGetToken();
        if($new_token_res['status'] == 0){
            return $new_token_res;
        }
        $data = [
            'type' => 'face_token',
            'access_token' => $new_token_res['data']['token'],
            'expires' => $new_token_res['data']['expressTime'],
        ];
        try {
            if($token) {
                $wxtoken->update($token['id'], $data);
            }else{
                $wxtoken->save($data);
            }
            return ['status'=> 1, 'msg'=> '成功', 'data'=> $data['access_token']];
        } catch (\Exception $e) {
            test_log('FaceTool timeToGetToken-'. $e->getMessage());
            return ['status' => 0, 'msg' => '保存失败-'. $e->getMessage()];
        }
    }
    //获取省库请求秘钥
    public function getFaceRequestToken()
    {
        if(Config::get('app.app_host') == 'dev') { //测试服务器
            $url = "http://localhost:8083/sk_sjpt/face_token";
            $curl = new Curl();
            $curl->get($url);
            
            if ($curl->error) {
                test_log('face_token-'. $curl->error_code .': '. $curl->error_message);
                return ['status'=> 0, 'msg'=> $curl->error_code .': '. $curl->error_message];
            } else {
                $res = $curl->response;
                test_log('face_token-'. $res);
                $result = json_decode($res, true);
            }
            $curl->close();
            if(isset($result['code']) && $result['code'] == "200") {
                return ['status'=> 1, 'msg'=> '成功', 'data'=> $result['data']];
            }else{
                test_log('face_token error-'. $res);
                return ['status'=> 0, 'msg'=> $result['msg']];
            }
        }
        $token = app()->make(WxtokenDao::class)->get(['type'=> 'face_token']);
        if($token) {
            return ['status'=> 1, 'msg'=> '成功', 'data'=> $token['access_token']];
        }else{
            test_log('face_token-秘钥不存在，定时任务未设置');
            return ['status'=> 0, 'msg'=> '秘钥不存在'];
        }
    }
    public function apiFaceGetToken(){
        $curl = new Curl();
        $url = "http://175.24.254.149:8916/auth/api/token";
        $data = [
            'token' => '',
            'body' => [
                'appid' => $this->app_key,
                'secret' => $this->app_secret,
            ],
            'other' => ''
        ];
        $data_json = json_encode($data);
        $curl->setHeader('Content-Type', 'application/json; charset=UTF-8');
        $curl->post($url, $data_json);
        if ($curl->error) {
            test_log('apiFaceGetToken-'. $curl->error_code .': '. $curl->error_message);
            return ['status'=> 0, 'msg'=> $curl->error_code .': '. $curl->error_message];
        } else {
            $res = $curl->response;
            test_log('apiFaceGetToken-'. $res);
            $result = json_decode($res, true);
        }
        $curl->close();
        if($result['header']['code'] == '00') {
            return ['status'=> 1, 'msg'=> $result['header']['message'],'data'=>$result['body']];
        }else{
            test_log('apiFaceGetToken error-'.$res);
            return ['status'=> 0, 'msg'=> $result['header']['message']];
        }
    }


    // 实名三项比对接口
    public function faceCheck($real_name,$id_card,$face_img=''){

        $request_token_res = $this->getFaceRequestToken();
        if($request_token_res['status'] == 0) {
            return ['status'=> 0, 'msg'=> $request_token_res['msg']];
        }
        $curl = new Curl();
        $url = "http://175.24.254.149:8916/auth/api/ctidAuth";
        $data = [
            'token' => $request_token_res['data'],
            // 'body' => [
            //     'name' => $real_name,
            //     'idcard' => $id_card,
            // ],
            // 'body' => "kfCs/O0tRBd7lIZyV/5gsj39yY+dXtcYX2Au81hfo6GZs+MU5m547jL8pSJXazxL7I8=",
            // 'body' => $this->encrypt(json_encode(['name'=>$real_name,'idcard'=>$id_card]),$this->deskey),

            // 'body' =>  $this->des3Encryption($this->deskey,json_encode(['name'=>$real_name,'idcard'=>$id_card])),
            'body' => self::encrypt(json_encode(['name'=>$real_name,'idcard'=>$id_card]),$this->deskey),
            'other' => [
                'faceImg' => $face_img
            ]
        ];
        $data_json = json_encode($data);
        // test_log($data_json);
        $curl->setHeader('Content-Type', 'application/json; charset=UTF-8');
        $curl->post($url, $data_json);
        if ($curl->error) {
            test_log('ctidAuth-'. $curl->error_code .': '. $curl->error_message);
            return ['status'=> 0, 'msg'=> $curl->error_code .': '. $curl->error_message];
        } else {
            $res = $curl->response;
            test_log('ctidAuth-'. $res);
            $result = json_decode($res, true);
        }
        $curl->close();
        if($result['header']['code'] == '00') {
            return ['status'=> 1, 'msg'=> $result['header']['message'],'data'=>$result['body']];
        }else{
            test_log('ctidAuth error-'.$res);
            return ['status'=> 0, 'msg'=> $result['header']['message']];
        }
    }
    // class TripleDES{
        public static function encrypt($str,$key){
            $str = self::pkcs5_pad($str, 8);
            if (strlen($str) % 8) {
                $str = str_pad($str, strlen($str) + 8 - strlen($str) % 8, "\0");
            }
            $sign = openssl_encrypt($str, 'DES-EDE3', $key, OPENSSL_RAW_DATA | OPENSSL_NO_PADDING, '');
            return base64_encode($sign);
        }
     
        private static function pkcs5_pad($text, $blocksize) {
            $pad = $blocksize - (strlen($text) % $blocksize);
            return $text . str_repeat(chr($pad), $pad);
        }
     
    // }

}
