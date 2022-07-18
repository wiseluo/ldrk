<?php

use think\facade\Route;

if(!defined('DS')){
    define('DS', DIRECTORY_SEPARATOR);
}
Route::get('test_tmp_log_test','TestController/test_tmp_log_test');
Route::get('test_tmp_log_all','TestController/test_tmp_log_all');
Route::get('test_tmp_log_clear','TestController/test_tmp_log_clear');

Route::get('date_nums_fix_classify','FixController/date_nums_fix_classify');
Route::get('chrome_auto_cli_build_place_qrcode','FixController/chrome_auto_cli_build_place_qrcode');
Route::get('auto_cli_build_place_qrcode','FixController/auto_cli_build_place_qrcode');
Route::get('xiaoshangpincheng_short_name','FixController/xiaoshangpincheng_short_name');
Route::get('xiaoshangpincheng_to_place','FixController/xiaoshangpincheng_to_place');
Route::get('guojiyoujian_to_place','FixController/guojiyoujian_to_place');
Route::get('send_company_sms_by_templete','FixController/send_company_sms_by_templete');
Route::get('send_delay_sms','FixController/send_delay_sms');
Route::get('reset_user_hsjc_data_by_api','FixController/reset_user_hsjc_data_by_api');
Route::get('reset_user_hsjc_data','FixController/reset_user_hsjc_data');
Route::get('company_qrcode_v2', 'FixController/company_qrcode_v2');
Route::get('place_info_update_yw_street_sms', 'FixController/place_info_update_yw_street_sms');
Route::get('send_sms_yanshi', 'FixController/send_sms_yanshi');
Route::get('readExcelFile', 'FixController/readExcelFile');
Route::get('cli_build_place_qrcode', 'FixController/cli_build_place_qrcode');
Route::get('set_company_staff_street', 'FixController/companyStaffStreet');

Route::get('test_set_redis_cache', 'TestController/test_set_redis_cache');
Route::get('test_get_redis_cache', 'TestController/test_get_redis_cache');
Route::get('test_build_applet_code', 'TestController/test_build_applet_code');
Route::get('tmpBuildAppletCodeCmd', 'IndexController/tmpBuildAppletCodeCmd');
Route::get('test', 'IndexController/test');
Route::get('xcxQrcode', 'IndexController/xcxQrcode');
Route::get('testcar', 'IndexController/testcar');
Route::get('findHsjc', 'IndexController/findHsjc');
Route::get('dddddd', 'IndexController/dddddd');
// 
Route::get('test/get_json', 'TestController/get_json');
// Route::get('tongji', 'IndexController/tongji');
Route::get('test/test_phone_jkm', 'TestController/test_phone_jkm'); //手机号查询健康码接口调试
Route::get('test/qsjkmxxcx', 'TestController/qsjkmxxcx'); //省健康码基本信息查询接口调试
Route::get('test/jkmss', 'TestController/jkmss'); //省健康码基本信息查询接口调试
Route::get('test/sxgymyfjzxxcx', 'TestController/sxgymyfjzxxcx'); //省新冠疫苗预防接种信息查询接口调试
Route::get('test/shsjcjk', 'TestController/shsjcjk'); //省核酸检测接口调试
Route::get('test/ywhsjcjk', 'TestController/ywhsjcjk'); //义乌核酸检测接口调试
Route::get('test/test', 'TestController/test');
Route::post('test/test', 'TestController/test');
Route::get('test/test_log', 'TestController/test_log');
Route::get('test/enterprise_info', 'TestController/enterpriseInfo'); //查询企业信息
Route::get('test/sktest', 'TestController/sktest'); //省库测试
Route::get('test/skxcmdx', 'TestController/skxcmdx'); //省库测试
Route::get('test/skxcm', 'TestController/skxcm'); //省库测试
Route::get('test/sklssws', 'TestController/sklssws'); //省库测试
Route::get('test/hsjccysj', 'TestController/hsjccysj'); // 获取省库测试核酸检测采样时间接口

Route::get('test/ywhjrk', 'TestController/ywhjrk');
Route::get('test/snrk', 'TestController/snrk');
Route::get('test/qgrkk', 'TestController/qgrkk');
Route::post('test/facecheck', 'TestController/facecheck');
// 测试环境的代理
Route::get('proxy/skPhoneToJkm', 'ProxyController/skPhoneToJkm');
Route::get('proxy/skIdcardToJkm', 'ProxyController/skIdcardToJkm'); //查询企业信息
Route::get('proxy/skxcmdxjk', 'ProxyController/skxcmdxjk'); //省库测试
Route::get('proxy/skxcmjk', 'ProxyController/skxcmjk'); //省库测试
Route::get('proxy/skhsjcjk', 'ProxyController/skhsjcjk'); //省库测试
Route::get('proxy/skxgymyfjzxxcx', 'ProxyController/skxgymyfjzxxcx'); //省库测试
Route::get('proxy/sklssws', 'ProxyController/sklssws'); //省库测试
Route::get('proxy/skIdcardAndPhoneToJkm', 'ProxyController/skIdcardAndPhoneToJkm'); //省库测试
Route::get('proxy/skGetHsjcCollectTime', 'ProxyController/skGetHsjcCollectTime'); //省库测试

// 消息定时任务
Route::get('task/timeToSendOverdueReturnTime', 'TaskController/timeToSendOverdueReturnTime');
Route::get('task/timeToSendUnSendMessage', 'TaskController/timeToSendUnSendMessage');
//Route::get('task/timeToSetDeclareTemp', 'TaskController/timeToSetDeclareTemp');
Route::get('task/timeToVerifyIdCard', 'TaskController/timeToVerifyIdCard');
Route::get('task/timeDeclareOcr', 'TaskController/timeDeclareOcr');
Route::get('task/timeToSetControlState', 'TaskController/timeToSetControlState');
Route::get('task/handleUnmatchOcr', 'TaskController/handleUnmatchOcr');
Route::get('task/archivePre3Day', 'TaskController/archivePre3Day');
Route::get('task/timeToXgymyfjz', 'TaskController/timeToXgymyfjz');
Route::get('task/timeToHsjc', 'TaskController/timeToHsjc');
Route::get('task/timeToYwhsjc', 'TaskController/timeToYwhsjc');
Route::get('task/timeToJkm', 'TaskController/timeToJkm');
Route::get('task/timeToRefreshSkRequestToken', 'TaskController/timeToRefreshSkRequestToken');
Route::get('task/timeToRefreshWxAccessToken', 'TaskController/timeToRefreshWxAccessToken');
Route::get('task/timeToRefreshFaceRequestToken', 'TaskController/timeToRefreshFaceRequestToken');
Route::get('task/timeToRefreshSkActualRequestToken', 'TaskController/timeToRefreshSkActualRequestToken');
// Route::get('task/timeToUpdateCompanyStaffResult', 'TaskController/timeToUpdateCompanyStaffResult');
Route::get('task/timeToUpdateCompanyStaffReceiveTime', 'TaskController/timeToUpdateCompanyStaffReceiveTime');
// Route::get('task/timeToUpdateCompanyStaffNoYwResult', 'TaskController/timeToUpdateCompanyStaffNoYwResult');
Route::get('task/timeToCompanyHsjcCount', 'TaskController/timeToCompanyHsjcCount');
Route::get('task/timeToPlaceDeclareDateNums', 'TaskController/timeToPlaceDeclareDateNums');
Route::get('task/timeToFeedDateNums', 'TaskController/timeToFeedDateNums');
Route::get('task/timeToGroupDateNums', 'TaskController/timeToGroupDateNums');
Route::get('task/timeToPlaceDeclareStreetHourNums', 'TaskController/timeToPlaceDeclareStreetHourNums');
Route::get('task/timeToFeedHourNums', 'TaskController/timeToFeedHourNums');
Route::get('task/timeToSendSmsToLinkTwoDaysHsjc', 'TaskController/timeToSendSmsToLinkTwoDaysHsjc');
// Route::get('task/timeToUpdateHsjcFromLocalSKHL', 'TaskController/timeToUpdateHsjcFromLocalSKHL');
Route::get('task/timeToCheckSlaveDb', 'TaskController/timeToCheckSlaveDb');
Route::get('task/timeToSendSmsToAdmin', 'TaskController/timeToSendSmsToAdmin');
Route::get('task/timeToMoveTmpTestLogToDb', 'TaskController/timeToMoveTmpTestLogToDb');
Route::get('task/timeToSplitPlaceDeclareTable', 'TaskController/timeToSplitPlaceDeclareTable');

// 检测服务器情况
Route::get('serverIsOk', 'CheckController/serverIsOk');
Route::get('serverIsOk112', 'CheckController/serverIsOk112');
Route::get('serverIsOk114', 'CheckController/serverIsOk114');
Route::get('serverIsOk118', 'CheckController/serverIsOk118');
Route::get('serverIsOk95', 'CheckController/serverIsOk95');
Route::get('serverIsOk96', 'CheckController/serverIsOk96');
Route::get('serverIsOk97', 'CheckController/serverIsOk97');
// 测试环境去定时 check 正式的serverIsOk
Route::get('task/timeToCheckServerIsOk', 'TaskController/timeToCheckServerIsOk');

//为测试服务器调用配置接口
Route::get('test/zzsb_view', 'TestController/test_zzsb_view');
Route::get('sgzx_view/zzsb_view', 'IndexController/zzsb_view'); //自主申报视图(为测试环境配置接口)
Route::get('test/csm_ryxx', 'TestController/test_csm_ryxx');
Route::get('sgzx_view/csm_ryxx', 'IndexController/csm_ryxx'); //场所码人员信息视图(为测试环境配置接口)
Route::get('sk_sjpt/token', 'IndexController/sk_token'); //获取省库token(为测试环境配置接口)
Route::get('sk_sjpt/token_actual', 'IndexController/token_actual'); //获取省库token(实时健康码，为测试环境配置接口)
Route::get('sk_sjpt/face_token', 'IndexController/face_token'); // 获取人脸token(为测试环境配置接口)
Route::get('sk_sjpt/proxyapi', 'IndexController/proxyapi'); // 
Route::post('sk_sjpt/proxyapi_jkmss', 'IndexController/proxyapi_jkmss'); // 

// 给冷链提供核酸检测数据
Route::post('llwf/getUserHsjcTime','LlwfController/getUserHsjcTime'); // 给冷链提供人员核酸检测的

// 任务
Route::get('buildAppletCodeByUserId', 'BuildAppletCodeController/buildAppletCodeByUserId'); // 此方法给118服务器使用

//微信二维码图片处理
Route::post('wx_qrcode/watermark', 'BuildAppletCodeController/watermark');
Route::post('wx_qrcode/watermark_company', 'BuildAppletCodeController/watermarkCompany');
Route::post('wx_qrcode/watermark_school', 'BuildAppletCodeController/watermarkSchool');
Route::post('wx_qrcode/watermark_personal', 'BuildAppletCodeController/watermarkPersonal');
// h5个人防疫码
Route::get('personal_code', 'H5Controller/personalCode'); // 此方法给118服务器使用
Route::post('subEvent', 'SubController/subEvent'); // 此方法给118服务器使用

Route::miss(function () {
    $appRequest = request()->pathinfo();
    if ($appRequest === null) {
        $appName = '';
    } else {
        $appRequest = str_replace('//', '/', $appRequest);
        $appName = explode('/', $appRequest)[0] ?? '';
    }
    //检测是否已安装CRMEB系统
    if (file_exists(root_path() . "public/install/") && !file_exists(root_path() . "public/install/install.lock")) {
        return redirect('/install/index');
    }
    switch (strtolower($appName)) {
        case 'admin':
            return view(app()->getRootPath() . 'public' . DS . 'admin' . DS . 'index.html');
        case 'home':
            if (request()->isMobile()) {
                return redirect(app()->route->buildUrl('/'));
            } else {
                return view(app()->getRootPath() . 'public' . DS . 'home' . DS . 'index.html');
            }
        case 'kefu':
            return view(app()->getRootPath() . 'public' . DS . 'admin' . DS . 'index.html');
        case 'ainat':
            return view(app()->getRootPath() . 'public' . DS . 'ainat' . DS . 'index.html');
        // case 'personal_code':
        //     return view(app()->getRootPath() . 'public' . DS . 'uhelp.html');
        default:
            if (!request()->isMobile() && is_dir(app()->getRootPath() . 'public' . DS . 'home') && !request()->get('type')) {
                return redirect(app()->route->buildUrl('/home/'));;
            } else {
                return view(app()->getRootPath() . 'public' . DS . 'index.html');
            }
    }
});
