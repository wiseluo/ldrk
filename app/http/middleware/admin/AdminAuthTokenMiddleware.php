<?php

namespace app\http\middleware\admin;


use app\Request;
use app\services\admin\system\admin\AdminAuthServices;
use crmeb\interfaces\MiddlewareInterface;
use think\facade\Config;

/**
 * 后台登陆验证中间件
 * Class AdminAuthTokenMiddleware
 * @package app\http\middleware\admin
 */
class AdminAuthTokenMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, \Closure $next)
    {
        $authInfo = null;
        // var_dump(1);
        // var_dump($request->header());
        $token = trim(ltrim($request->header(Config::get('cookie.token_name', 'Authori-zation')), 'Bearer'));

        if (!$token) $token = trim(ltrim($request->header('Authorization'), 'Bearer'));

        /** @var AdminAuthServices $service */
        $service = app()->make(AdminAuthServices::class);
        $adminInfo = $service->parseToken($token);

        Request::macro('isAdminLogin', function () use (&$adminInfo) {
            return !is_null($adminInfo);
        });
        Request::macro('adminId', function () use (&$adminInfo) {
            return $adminInfo['id'];
        });

        Request::macro('adminInfo', function () use (&$adminInfo) {
            return $adminInfo;
        });

        return $next($request);
    }
}
