<?php

namespace crmeb\utils;


use crmeb\exceptions\UserException;
use Firebase\JWT\JWT;
use think\facade\Env;
use think\facade\Cache;

/**
 * Jwt
 * Class JwtAuth
 * @package crmeb\utils
 */
class UserJwtAuth
{

    /**
     * token
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $app_key = 'ab4e65548ad5565616f0d9d1f027bb7d'; //md5('ldrk_user_jwt_app_key')

    /**
     * 获取token
     * @param int $id
     * @param string $type
     * @param array $params
     * @return array
     */
    public function getToken(int $id, string $type, array $params = []): array
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
    public function createToken(int $id, string $type, array $params = [])
    {
        $tokenInfo = $this->getToken($id, $type, $params);
        $exp = $tokenInfo['params']['exp'] - $tokenInfo['params']['iat'] + 60;
        $res = Cache::set(md5($tokenInfo['token']), ['uid' => $id, 'type' => $type, 'token' => $tokenInfo['token'], 'exp' => $exp], (int)$exp);
        if (!$res) {
            throw new UserException(ApiErrorCode::ERR_SAVE_TOKEN);
        }
        return $tokenInfo;
    }
}
