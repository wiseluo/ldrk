<?php

use app\http\middleware\AllowOriginMiddleware;
use \app\http\middleware\gate\AuthMiddleware;
use think\facade\Route;

Route::group('gateapi', function () {

    /**
     * 测试接口
     */
    Route::group(function () {

    });

    /**
     * 无需授权的接口
     */
    Route::group(function () {
        //用户名密码登录
        // Route::post('login', 'Login/login');
    });

    /**
     * 已授权的接口
     */
    Route::group(function () {
        Route::post('gatedeclare/addHistory', 'GateDeclare/addHistory');
        //场所码申报结果（融合）
        Route::get('gatedeclare/result_whole', 'GateDeclare/resultWhole');
        // 通过身份证查询管控状态
        Route::get('gatedeclare/ryxx', 'GateDeclare/ryxx');

    })->middleware([
        AuthMiddleware::class,
    ]);

})->prefix('gate.')->middleware([AllowOriginMiddleware::class]);