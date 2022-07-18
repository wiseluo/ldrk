<?php

use app\http\middleware\AllowOriginMiddleware;
use \app\http\middleware\user\AuthMiddleware;
use think\facade\Route;

Route::group('userapi', function () {

    /**
     * 测试接口
     */
    Route::group(function () {
        //行程码短信接口（测试)
        Route::get('xcmdxjk', 'Index/xcmdxjk');
        Route::get('xcmjk', 'Index/xcmjk');
        
        //图片识别
        Route::get('img_ocr', 'Test/imgOcr');
        Route::get('idcardocr', 'Test/idcardOcr');

        Route::get('jkm_log', 'Index/jkm_log');
        Route::get('hsjc_log', 'Index/hsjc_log');
        Route::get('test_log', 'Index/test_log');
      
        Route::get('sccszh','Index/sccszh');

        // h5个人码帮助页面
        Route::get('wxconfig','Wechat/wxconfig');

    });

    /**
     * 无需授权的接口
     */
    Route::group(function () {
        //用户名密码登录
        Route::post('login', 'Login/login');

        // 发送统计信息给 何总和鲍主任
        Route::get('tongji', 'Index/tongji');
        Route::get('zuijin', 'Index/zuijin');
        Route::get('csmtongji', 'Index/csmtongji');
        Route::get('qymtongji', 'Index/qymtongji');
        
        
    });

    /**
     * 已授权的接口
     */
    Route::group(function () {
        // 四级区域选择
        Route::get('district', 'District/index');
        // 卡口区域选择
        Route::get('barrier_district', 'BarrierDistrict/index');
        //发送短信验证码
        Route::get('sms/code', 'Sms/smsCode');
        //上传文件base64
        Route::post('base64img/upload', 'SystemUpload/base64Img');
        //上传临时文件
        Route::post('tmp/upload', 'SystemUpload/tmp');
        //人口库查询
        Route::get('qgrkk', 'Sgzx/qgrkk');
        //
        Route::get('test_ff', 'Sgzx/test_ff');
        Route::get('phpinfo', 'Sgzx/phpinfo');
        //自主申报
        //外出申报
        Route::post('owndeclare/leave', 'OwnDeclare/ownDeclareLeave');
        //来返义申报
        Route::post('owndeclare/come', 'OwnDeclare/ownDeclareCome');
        //中高风险地区自主申报
        Route::post('owndeclare/riskarea', 'OwnDeclare/ownDeclareRiskarea');
        //卡口自主申报
        Route::post('owndeclare/barrier', 'OwnDeclare/ownDeclareBarrier');
        //防疫隔离申报
        Route::post('owndeclare/quarantine', 'OwnDeclare/ownDeclareQuarantine');
        Route::post('owndeclare/other', 'OwnDeclare/other');
        //场所码申报
        Route::post('placedeclare', 'PlaceDeclare/placeDeclare');
        //场所码申报结果
        Route::get('placedeclare/result', 'PlaceDeclare/result');
        //场所码申报详情
        Route::get('placedeclare/detail', 'PlaceDeclare/detail');
        //场所码申报扫码
        Route::post('placedeclare/scan_code', 'PlaceDeclare/scanCode');
        //场所码详情
        Route::get('place/:code', 'PlaceDeclare/placeRead');
        //行程码短信
        Route::get('sms/xcmdx', 'Sms/xcmdx');

        // 车辆申报
        Route::post('cardeclare', 'CarDeclare/post');
        Route::get('cardeclare/getTravel', 'CarDeclare/getTravel');

        //获取最新一次外出申报的目的地
        Route::get('owndeclare/last_leave', 'OwnDeclare/lastLeave');

        Route::get('owndeclare/getMoreInfoBySign', 'OwnDeclare/getMoreInfoBySign');

    })->middleware([
        AuthMiddleware::class,
    ]);

})->prefix('user.')->middleware([AllowOriginMiddleware::class]);