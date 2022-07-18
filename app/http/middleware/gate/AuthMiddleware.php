<?php

namespace app\http\middleware\gate;

use app\Request;
use app\services\applet\UserAuthServices;
use crmeb\exceptions\GateException;
use crmeb\interfaces\MiddlewareInterface;
use think\facade\Config;
use think\facade\Db;
use think\facade\Cache;
use crmeb\services\RedisCacheService;

//基础授权中间件 未登录接口
class AuthMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, \Closure $next, bool $force = true)
    {
        // $token = trim(ltrim($request->header(Config::get('cookie.token_name', 'Authori-zation')), 'Bearer'));
        // if (!$token) $token = trim(ltrim($request->header('Authorization'), 'Bearer'));

        // $userInfo = app()->make(UserAuthServices::class)->parseToken($token);

        // Request::macro('userInfo', function () use (&$userInfo) {
        //     return $userInfo;
        // });

        // 
        // $gate_key = $request->param('gate_key');
        $app_key = $request->param('app_key');
        $gate_sign = $request->param('gate_sign');
        $timestamp = $request->param('timestamp');
        // if($gate_key != '' && $app_key == ''){
        //     $app_key = $gate_key;
        // }
            
        $cachePlace = Cache::get('gate_factory_'.$app_key);
        if($cachePlace){
            // test_log('来自缓存');
            $gate_factory =  $cachePlace;
        }else{
            // test_log('来自数据库');
            $gate_factory = Db::name('gate_factory')->where('key',$app_key)->find();
            if($gate_factory){
                $cachePlace = Cache::set('gate_factory_'.$app_key,$gate_factory,7200);
            }else{
                throw new GateException('错误的app_key', 400);
            }
        }

        if(Config::get('app.app_host') != 'dev') { //测试环境不验证
            if( abs(time() - $timestamp ) > 60 ){
                throw new GateException('签名中的时间戳偏差大于60秒', 400);
            }
        }
        // 是否在白名单内
        $ip = $request->ip();
        $white_ips_arr = explode(',',$gate_factory['white_ips']);
        if(!in_array($ip,$white_ips_arr)){
            system_error_log(__METHOD__,$app_key.'的ip:'.$ip.'不在白名单内','需确认白名单情况');
        }
        
        $right_sign = md5($app_key.'|'.$gate_factory['secret'].'|'.$timestamp);
        if($right_sign != $gate_sign){
            if(Config::get('app.app_host') == 'dev') {
                // 测试环境不验证签名
            }else{
                throw new GateException('签名计算错误', 400);
            }
        }

        return $next($request);

    }
}
