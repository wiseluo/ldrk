<?php

namespace app\services\applet;

use crmeb\utils\AppletJwtAuth;
use think\facade\Route as Url;

/**
 * Class BaseServices
 * @package app\services
 */
abstract class BaseServices
{
    /**
     * 模型注入
     * @var object
     */
    protected $dao;

    /**
     * 创建token
     * @param int $id
     * @param $type
     * @return array
     */
    public function createToken(int $id, $type)
    {
        $jwtAuth = app()->make(AppletJwtAuth::class);
        return $jwtAuth->getToken($id, $type);
    }

    public function createTokenV2($user, $type)
    {
        $jwtAuth = app()->make(AppletJwtAuth::class);
        return $jwtAuth->getTokenV2($user, $type);
    }

    /**
     * 获取路由地址
     * @param string $path
     * @param array $params
     * @param bool $suffix
     * @return \think\route\Url
     */
    public function url(string $path, array $params = [], bool $suffix = false, bool $isDomain = false)
    {
        return Url::buildUrl($path, $params)->suffix($suffix)->domain($isDomain)->build();
    }

    /**
     * 密码hash加密
     * @param string $password
     * @return false|string|null
     */
    public function passwordHash(string $password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        return call_user_func_array([$this->dao, $name], $arguments);
    }

}
