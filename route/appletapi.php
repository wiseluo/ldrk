<?php

use app\http\middleware\AllowOriginMiddleware;
use app\http\middleware\applet\AuthTokenMiddleware;
use think\facade\Route;

//小程序接口
Route::group('appletapi', function () {


    Route::post('placedeclare/test_scan_code', 'PlaceDeclare/testScanCode');


    /**
     * 测试调试接口
     */
    Route::group(function () {
        //场所码人员信息视图
        Route::get('csm_ryxx', 'Test/csm_ryxx');
    });

    /**
     * 无需授权的接口
     */
    Route::group(function () {
        //微信小程序授权登录
        Route::post('login', 'Login/login');
        // //新版授权code获取手机号
        // Route::post('authphone', 'Login/authphone');
        // //旧版通过加密数据获取手机号
        // Route::post('encrypted_phone', 'Login/encryptedPhone');

        //新版授权code获取手机号
        Route::post('authphone/v2', 'Login/authphoneV2');
        //旧版通过加密数据获取手机号
        Route::post('encrypted_phone/v2', 'Login/encryptedPhoneV2');

        //微信小程序绑定注册
        Route::post('register', 'Login/register');
        //身份证识别注册
        Route::post('register/idcard_recogn', 'Login/registerIdcardRecogn');

        // 小程序帮助文档
        Route::get('help', 'Help/index');
        Route::get('help/detail', 'Help/detail');

        Route::get('companyClassify', 'Common/companyClassifyList');

        Route::get('login/test', 'Login/test');
    });

    /**
     * 小程序已授权的接口
     */
    Route::group(function () {
        //企业查询
        Route::get('enterprise/info', 'Common/enterpriseInfo');
        //社区列表
        Route::get('community/list', 'Common/communityList');
        // 四级区域选择
        Route::get('district', 'District/index');
        // 卡口区域选择
        Route::get('barrier_district', 'Common/barrierDistrictList');
        // 中高风险及关注地区
        Route::get('riskAndFollowDistrictList', 'Common/riskAndFollowDistrictList');
        //用户账号清除登出
        Route::post('user/clean', 'User/clean');
        //判断用户是否登录
        Route::get('user/info', 'User/info');
        //用户获取行程码短信验证结果
        Route::get('xcmdx/verify', 'User/xcmVerify');
        //用户代扫获取行程码短信验证结果
        Route::get('replace_xcmdx/verify', 'User/replaceXcmVerify');
        //获取用户子表信息
        Route::get('user/subInfo', 'User/subInfoRead');
        //用户编辑子表信息
        Route::post('user/subInfo', 'User/subInfo');
        // 人脸识别
        Route::post('user/faceCheck', 'User/faceCheck');


        //场所码申报扫码
        Route::post('placedeclare/scan_code', 'PlaceDeclare/scanCode');
        Route::get('placedeclare/scan_code', 'PlaceDeclare/scanCode');
        //场所码代扫
        Route::get('placedeclare/replace_scan', 'PlaceDeclare/replaceScan');
        //反扫场所码
        Route::get('placedeclare/reverse_scan', 'PlaceDeclare/reverseScan');
        //场所码申报结果
        //Route::get('placedeclare/result', 'PlaceDeclare/result');
        //场所码申报结果（融合）
        Route::get('placedeclare/result_whole', 'PlaceDeclare/resultWhole');
        //场所码申报详情
        Route::get('placedeclare/detail', 'PlaceDeclare/detail');
        //获取行程码短信
        Route::get('sms/xcmdx', 'Sms/xcmdx');
        //获取行程码结果
        Route::get('xcm/result', 'PlaceDeclare/xcmResult');
        //获取人员信息结果
        Route::get('ryxx/result', 'PlaceDeclare/ryxxResult');
        // 亮码结果
        Route::get('myQrcodeResult', 'PlaceDeclare/myQrcodeResult');
        // 获取行程码结果
        Route::get('xcm/resultForQrcode', 'PlaceDeclare/resultForQrcode');
        // 查看最近6次核酸检测结果
        Route::get('hsjc/result/log', 'PlaceDeclare/hsjcResultLog');

        //自主申请场所码
        Route::post('place/apply', 'Place/apply');
        //自主申请场所码
        Route::post('place/apply/v2', 'Place/applyV2');
        //修改场所码
        Route::post('place/update', 'Place/update');
        //场所码类型
        Route::get('place/type', 'Place/type');
        //获取我的场所码
        Route::get('place/read', 'Place/read');
        //删除我的场所码
        Route::delete('place/:id', 'Place/delete');

        //获取当前登录人的企业列表
        Route::get('company/index', 'Company/index');
        //员工删除企业列表中的某个企业
        Route::delete('company/:id', 'Company/delete');
        //申请企业码
        Route::post('company/save', 'Company/save');
        //企业码类型验证
        Route::get('company/classify_verify', 'Company/classifyVerify');
        //获取我的企业码
        Route::get('company/read', 'Company/read');
        //修改企业码
        Route::put('company/update', 'Company/update');
        //企业类型列表
        Route::get('company/classify', 'Company/classifyList');
        //企业员工类型列表
        Route::get('company/staff/classify', 'Company/staffClassifyList');
        //扫企业码获取企业的名称
        Route::get('companystaff/scan/get_name', 'CompanyStaff/scanGetName');
        //员工扫企业码
        Route::get('companystaff/scan_code', 'CompanyStaff/scanCode');
        //企业码下的员工列表
        Route::get('companystaff/index', 'CompanyStaff/index');
        //员工列表统计检测人数，检测率
        Route::get('companystaff/list_statistics', 'CompanyStaff/listStatistics');
        //联络员短信一键提醒
        Route::get('companystaff/oneclick_remind', 'CompanyStaff/oneclickRemind');

        //删除企业码下的员工
        Route::delete('companystaff/:id', 'CompanyStaff/delete');
        //批量删除企业码下的员工
        Route::post('companystaff/batch_delete', 'CompanyStaff/batchDelete');
        //联络员职务转移
        Route::post('companystaff/link_transfer', 'CompanyStaff/linkTransfer');
        //联络员修改企业码下的某些员工检测频率
        Route::post('companystaff/check_frequency', 'CompanyStaff/checkFrequency');
        //员工修改自己在某企业下的检测频率
        Route::post('companystaff/user/check_frequency', 'CompanyStaff/userCheckFrequency');

        //提交货车申报
        Route::post('cardeclare', 'CarDeclare/post');
        Route::get('cardeclare/getTravelBySign', 'CarDeclare/getTravelBySign');


        /* 自主申报接口 */

        //发送短信验证码
        Route::get('sms/code', 'Sms/smsCode');
        //上传文件base64
        Route::post('base64img/upload', 'SystemUpload/base64Img');
        //上传临时文件
        Route::post('tmp/upload', 'SystemUpload/tmp');

        //人口库查询
        Route::get('qgrkk', 'Sgzx/qgrkk');

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
        //获取最新一次外出申报的目的地
        Route::get('owndeclare/last_leave', 'OwnDeclare/lastLeave');
        //获取自主申报结果
        Route::get('owndeclare/result_whole', 'OwnDeclare/resultWhole');

        //个人防疫码
        //申领的个人防疫码列表
        Route::get('personal/code', 'PersonalCode/index');
        //获取某个个人防疫码详情
        Route::get('personal/code/:id', 'PersonalCode/read');
        //申领个人防疫码
        Route::post('personal/code', 'PersonalCode/save');
        Route::put('personal/code/:id', 'PersonalCode/update');


        /**
         * ～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～·
         * 校园码
         */
        //获取当前登录人的校园码信息
        Route::get('school/getSchoolInfo', 'School/getSchoolInfo');
        //申请校园码
        Route::post('school/create', 'School/create');
        // 校园白名单
        Route::get('school/whiteList', 'School/whiteList');
        //修改校园信息
        Route::post('school/update', 'School/update');
        // 获取班级列表 通用
        Route::get('schoolClass/list', 'SchoolClass/list');
        // 校园联系人添加班级
        Route::post('schoolClass/create', 'SchoolClass/create');
        // 校园联系人修改班级
        Route::post('schoolClass/update', 'SchoolClass/update');
        // 校园联系人删除班级
        Route::post('schoolClass/delete', 'SchoolClass/delete');
        // 老师绑定的班级列表
        Route::get('schoolClass/teachingToClass', 'schoolClass/teachingToClass');
        // 老师申请绑定班级
        Route::post('schoolClassTeacher/bindClass', 'SchoolClassTeacher/create');
        // 校园联系人获取审核老师列表
        Route::get('schoolClassTeacher/auditList', 'SchoolClassTeacher/list');
        // 校园联系人获取班级老师列表
        Route::get('schoolClassTeacher/teacherList', 'SchoolClassTeacher/teacherList');
        // 联系人处理审核
        Route::post('schoolClassTeacher/checkAudit', 'SchoolClassTeacher/checkAudit');
        // 联系人删除审核老师
        Route::post('schoolClassTeacher/delete', 'SchoolClassTeacher/delete');
        // 家长搜索学生
        Route::get('schoolStudent/findChild', 'SchoolStudent/findChild');
        // 家长添加学生
        Route::post('schoolStudent/addStudent', 'SchoolStudent/addStudent');
        // 删除学生
        Route::post('schoolStudent/delete', 'SchoolStudent/delete');
        // 家长修改学生
        Route::post('schoolStudent/updateStudent', 'SchoolStudent/update');
        // 学生家属列表
        Route::get('schoolStudent/familyList', 'SchoolStudentFamily/list');
        // 添加学生家属
        Route::post('schoolStudent/addfamily', 'SchoolStudentFamily/create');
        // 修改学生家属
        Route::post('schoolStudent/updateFamily', 'SchoolStudentFamily/update');
        // 删除学生家属
        Route::post('schoolStudent/deleteFamily', 'SchoolStudentFamily/delete');
        // 老师查看学生家属列表
        Route::get('schoolStudent/checkfamilyList', 'SchoolStudentFamily/checkFamily');
        // 老师查看学生列表
        Route::get('schoolStudent/studentList', 'schoolStudent/studentList');

        // 孩子列表
        Route::get('schoolStudent/childList', 'SchoolStudentFamily/childList');
    })->middleware([
        AuthTokenMiddleware::class,
    ]);
})->prefix('applet.')->middleware([AllowOriginMiddleware::class]);
