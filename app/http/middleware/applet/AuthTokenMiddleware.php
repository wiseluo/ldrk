<?php

namespace app\http\middleware\applet;


use app\Request;
use app\services\applet\UserAuthServices;
use crmeb\interfaces\MiddlewareInterface;
use think\facade\Config;
use app\dao\UserAuthDao;
use crmeb\exceptions\UserException;

class AuthTokenMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, \Closure $next, bool $force = true)
    {
        $token = trim(ltrim($request->header(Config::get('cookie.token_name', 'Authori-zation')), 'Bearer'));
        if (!$token) $token = trim(ltrim($request->header('Authorization'), 'Bearer'));

        $tokenUser = app()->make(UserAuthServices::class)->parseToken($token);
        Request::macro('tokenUser', function () use ($tokenUser) {
            if(!isset($tokenUser['id_card'])) {
                //test_log('tokenUser userInfo get');
                $userInfo = app()->make(UserAuthDao::class)->get($tokenUser['id']);
                if (!$userInfo || !$userInfo->id) {
                    throw new UserException('用户状态错误', 41000);
                }
                return $userInfo->hidden(['pwd', 'status'])->toArray();
            }
            //test_log('tokenUser get');
            return $tokenUser;
        });
        Request::macro('userInfo', function () use ($tokenUser) {
            //test_log('userInfo get');
            $userInfo = app()->make(UserAuthDao::class)->get($tokenUser['id']);
            if (!$userInfo || !$userInfo->id) {
                throw new UserException('用户状态错误', 41000);
            }
            return $userInfo->hidden(['pwd', 'status'])->toArray();
        });

        return $next($request);
    }
}
