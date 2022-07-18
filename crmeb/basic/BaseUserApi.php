<?php

declare (strict_types=1);

namespace crmeb\basic;

use think\facade\App;
use crmeb\exceptions\UserException;

/**
 * 用户端控制器基础类
 */
abstract class BaseUserApi
{
    /**
     * Request实例
     * @var \app\Request
     */
    protected $request;

    /**
     * 应用实例
     * @var \think\App
     */
    protected $app;

    /**
     * 控制器中间件
     * @var array
     */
    protected $middleware = [];

    /**
     * @var
     */
    protected $services;

    private $secret_key = 'ldrk_userapi';

    /**
     * 构造方法
     * @access public
     * @param App $app 应用对象
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->request = app('request');
        $this->init();
    }

    /**
     * 初始化
     */
    final private function init()
    {
        $param = $this->request->param();
        $token = $this->request->param('token');
        $timestamp = $this->request->param('timestamp', 0);
        if( (time() - $timestamp) > 60) { //秒 10位
            throw new UserException('token过期' .'-time:'. time() .'-timestamp:'. $timestamp, 400);
        }
        $encrypt_type = isset($param['encrypt_type']) ? $param['encrypt_type'] : 'md5';

        $secret = $this->secret_key .'_'. $timestamp;
        if($encrypt_type == 'md5') {
            if(md5($secret) != $token) {
                throw new UserException('token验证失败', 400);
            }
        }else{ //bcrypt加密
            //var_dump(password_hash($secret, PASSWORD_DEFAULT));
            if(!password_verify($secret, $token)) {
                throw new UserException('token验证失败!', 400);
            }
        }
        $this->initialize();
    }

    /**
     * @return mixed
     */
    protected function initialize()
    {

    }

}
