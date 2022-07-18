<?php

namespace crmeb\services;

use Exception;
use think\facade\Cache as CacheStatic;

/**
 * crmeb 缓存类
 * Class CacheService
 * @package crmeb\services
 * @mixin \Redis
 */
class RedisCacheService
{
    /**
     * Redis缓存句柄
     *
     * @return \think\cache\TagSet|CacheStatic
     */
    public static function redisHandler()
    {
        try {
            $res =  CacheStatic::store('redis')->handler();
            if ($res instanceof \Redis) {
                test_log('Redis');
                return $res;
            }else{
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }
    /**
     * 魔术方法
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        $obj = self::redisHandler();
        if($obj){
            return $obj->{$name}(...$arguments);
        }
        return false;
    }

    /**
     * 魔术方法
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        $obj = self::redisHandler();
        if($obj){
            return $obj->{$name}(...$arguments);
        }
        return false;
    }

}
