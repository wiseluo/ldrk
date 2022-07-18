<?php

namespace app\http\middleware\admin;

use app\Request;
use crmeb\exceptions\AdminException;
use crmeb\interfaces\MiddlewareInterface;
use think\facade\Config;

/**
 * 大屏数据 验证中间件
 * Class AdminAuthTokenMiddleware
 * @package app\http\middleware\admin
 */
class AdminScreenTokenMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, \Closure $next)
    {
        $token = trim(ltrim($request->header('Authori-zation-screen'), 'Bearer'));
        $timestamp = $request->header('timestamp', 0);
        if( (time() - $timestamp) > 300) { //秒 10位
            throw new AdminException('token过期', 300);
        }
        $encrypt_type = isset($param['encrypt_type']) ? $param['encrypt_type'] : 'md5';

        $secret = 'ldrk_adminapi_'. $timestamp;
        if($encrypt_type == 'md5') {
            if(md5($secret) != $token) {
                throw new AdminException('token验证失败', 400);
            }
        }else{ //bcrypt加密
            //var_dump(password_hash($secret, PASSWORD_DEFAULT));
            if(!password_verify($secret, $token)) {
                throw new AdminException('token验证失败!', 400);
            }
        }

        return $next($request);
    }
}
