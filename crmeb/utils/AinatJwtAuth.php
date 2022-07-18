<?php

namespace crmeb\utils;


use crmeb\exceptions\AdminException;
use crmeb\services\CacheService;
use Firebase\JWT\JWT;
use think\facade\Env;

class AinatJwtAuth
{

    /**
     * token
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $app_key = 'fb9f5883b964ffcb1a6c84647e5769b6'; //md5('ainat_admin_jwt_app_key')

    /**
     * 获取token
     * @param int $id
     * @param string $type
     * @param array $params
     * @return array
     */
    public function getToken(int $id, string $type, array $params = [],$login_by=''): array
    {
        $host = app()->request->host();
        $time = time();
        $exp_time = strtotime('+ 10hour');
        // if (app()->request->isApp()) {
        //     $exp_time = strtotime('+ 30day');
        // }
        $params += [
            'iss' => $host,
            'aud' => $host,
            'iat' => $time,
            'nbf' => $time,
            'exp' => $exp_time,
        ];
        $params['jti'] = compact('id', 'type');
        $params['login_by'] = $login_by; // 此token的登录方式
        $token = JWT::encode($params, Env::get('app.app_key', $this->app_key));

        return compact('token', 'params');
    }

    /**
     * 解析token
     * @param string $jwt
     * @return array
     */
    public function parseToken(string $jwt): array
    {
        $this->token = $jwt;
        list($headb64, $bodyb64, $cryptob64) = explode('.', $this->token);
        $payload = JWT::jsonDecode(JWT::urlsafeB64Decode($bodyb64));
        return [$payload->jti->id, $payload->jti->type];
    }

    /**
     * 验证token
     */
    public function verifyToken()
    {
        JWT::$leeway = 60;

        JWT::decode($this->token, Env::get('app.app_key', $this->app_key), array('HS256'));

        $this->token = null;
    }

    /**
     * 获取token并放入令牌桶
     * @param int $id
     * @param string $type
     * @param array $params
     * @return array
     */
    public function createToken(int $id, string $type, array $params = [],$login_by='')
    {
        $tokenInfo = $this->getToken($id, $type, $params,$login_by);
        $exp = $tokenInfo['params']['exp'] - $tokenInfo['params']['iat'] + 60;
        $res = CacheService::setTokenBucket(md5($tokenInfo['token']), ['uid' => $id, 'type' => $type, 'token' => $tokenInfo['token'], 'exp' => $exp], (int)$exp);
        if (!$res) {
            throw new AdminException(ApiErrorCode::ERR_SAVE_TOKEN);
        }
        return $tokenInfo;
    }
}
