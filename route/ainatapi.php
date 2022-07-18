<?php

use app\http\middleware\AllowOriginMiddleware;
use app\http\middleware\ainat\AdminAuthTokenMiddleware;
use \app\http\middleware\ainat\AdminCkeckRoleMiddleware;
use think\facade\Route;

//AI核酸对比系统接口
Route::group('ainatapi', function () {

    /**
     * 无需授权的接口
     */
    Route::group(function () {
        // 验证码登录
        Route::post('smslogin', 'Login/smslogin');
        //发送短信验证码
        Route::get('sms/code', 'Sms/smsCode');
        //后台登录页面数据
        Route::get('login/info', 'Login/info');
    });

    Route::group(function () {
        //获取当前登录人信息
        Route::get('admin/info', 'Admin/info');
        //退出登陆
        Route::get('admin/logout', 'Admin/logout');
        //上传临时文件
        Route::post('file/tmp/upload', 'SystemUpload/tmp');

        //AI核酸对比导入
        Route::post('import/nat_compare', 'ImportExcel/natCompare');
        //AI核酸对比导出
        Route::get('export/nat_compare', 'ExportExcel/natCompare');
        //AI核酸对比列表
        Route::get('nat_compare', 'NatCompare/index');
        //AI核酸对比
        Route::post('nat_compare', 'NatCompare/compare');
        //AI核酸对比进度
        Route::get('nat_compare_progress', 'NatCompare/compareProgress');
        //实时查询单个核酸采样数据
        Route::get('single_compare/actual_hsjc', 'NatCompare/actualHsjc');
        //保存历史任务
        Route::post('compare_task/save', 'CompareTask/save');
        //历史任务列表
        Route::get('compare_task/list', 'CompareTask/index');
        //历史任务详情
        Route::get('compare_task/read/:id', 'CompareTask/read');
        //删除历史任务
        Route::delete('compare_task/:id', 'CompareTask/delete');

        //判断要下载的excel文件是否存在
        Route::get('phpExcel/download', 'PublicController/phpExcelDownload');
    })->middleware([
        AdminAuthTokenMiddleware::class,
    ]);

})->prefix('ainat.')->middleware([AllowOriginMiddleware::class]);