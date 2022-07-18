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
class AppletJwtAuth
{

    /**
     * token
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $app_key = '09a8ac61986fd42f4b7a0f854fe50f04'; //md5('ldrk_applet_user_jwt_app_key')

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
        $exp_time = strtotime('+7 days');
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

    public function getTokenV2($user, string $type, array $params = []): array
    {
        $host = app()->request->host();
        $time = time();
        $exp_time = strtotime('+7 days');
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
        $params['jti'] = [
            'id'=> $user['id'],
            'real_name'=> $user['real_name'],
            'id_card'=> $user['id_card'],
            'phone'=> $user['phone'],
            'type'=> $type,
        ];
        $token = JWT::encode($params, Env::get('app.app_key', $this->app_key));
        return compact('token', 'params');
    }

    /**
     * 验证token
     */
    public function verifyToken($token)
    {
        JWT::$leeway = 60;

        return JWT::decode($token, Env::get('app.app_key', $this->app_key), array('HS256'));
    }

}
