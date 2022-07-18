<?php

namespace app\http\middleware\user;

use app\Request;
use crmeb\exceptions\UserException;
use crmeb\interfaces\MiddlewareInterface;
use think\facade\Config;

//基础授权中间件 未登录接口
class AuthMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, \Closure $next, bool $force = true)
    {
        $token = trim(ltrim($request->header(Config::get('cookie.token_name', 'Authori-zation')), 'Bearer'));
        if (!$token) $token = trim(ltrim($request->header('Authorization'), 'Bearer'));
        $timestamp = $request->header('timestamp', 0);

        if( (time() - $timestamp) > 180) { //秒 10位
            if(Config::get('app.app_host') == 'dev') { //测试环境
                throw new UserException('token过期'. time(), 400);
            }else{
                throw new UserException('token过期', 400);
            }
        }
        $encrypt_type = isset($param['encrypt_type']) ? $param['encrypt_type'] : 'md5';

        $secret = 'ldrk_userapi_'. $timestamp;
        if($encrypt_type == 'md5') {
            if(md5($secret) != $token) {
                if(Config::get('app.app_host') == 'dev') { //测试环境
                    throw new UserException('token验证失败'. md5($secret), 400);
                }else{
                    throw new UserException('token验证失败', 400);
                }
            }
        }else{ //bcrypt加密
            //var_dump(password_hash($secret, PASSWORD_DEFAULT));
            if(!password_verify($secret, $token)) {
                throw new UserException('token验证失败!', 400);
            }
        }

        return $next($request);
    }
}
