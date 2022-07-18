<?php

use app\http\middleware\AllowOriginMiddleware;
use app\http\middleware\api\AuthTokenMiddleware;
use app\http\middleware\api\CheckRoleMiddleware;
use think\facade\Route;

Route::group('api', function () {

    /**
     * 无需授权的接口
     */
    Route::group(function () {
        //用户名密码登录
        Route::post('login', 'Login/login');

    });

    // // 微信公众号的
    Route::get('wxconfig','Wechat/wxconfig');


})->prefix('api.')->middleware(AllowOriginMiddleware::class);

