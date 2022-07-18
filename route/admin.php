<?php

use think\facade\Route;
use think\facade\Config;
use think\Response;
use app\http\middleware\AllowOriginMiddleware;

Route::group('adminapi', function () {

    /**
     * 无需授权的接口
     */
    Route::group(function () {
        //用户名密码登录
        Route::post('login', 'Login/login')->name('AdminLogin');
        // 验证码登录
        Route::post('smslogin', 'Login/smslogin')->name('AdminSmslogin');
        
        //后台登录页面数据
        Route::get('login/info', 'Login/info');
        //下载文件
        Route::get('download', 'PublicController/download');
        //验证码
        Route::get('captcha_pro', 'Login/captcha');

        //发送短信验证码
        Route::get('sms/code', 'Sms/smsCode');

        //接收短信状态报告
        //Route::post('sms/reportstatus', 'Test/reportStatus');
        //发送短信（测试)
        Route::get('sms/sendcs', 'Test/sendcs');

        //判断要下载的excel文件是否存在
        Route::get('phpExcel/download', 'PublicController/phpExcelDownload');

        Route::get('companyClassify', 'PublicController/companyClassifyList');
    })->middleware(AllowOriginMiddleware::class);

    Route::group(function () {
        //下载备份记录表
        Route::get('backup/download', 'system.SystemDatabackup/downloadFile');

        // 消息提醒
        Route::get('jnotice', 'Common/jnotice');
        //验证授权
        Route::get('check_auth', 'Common/check_auth');
        //授权
        Route::get('auth', 'Common/auth');
        //获取左侧菜单
        Route::get('menus', 'setting.SystemMenus/menus');
        //获取搜索菜单列表
        Route::get('menusList', 'Common/menusList');
        //获取logo
        Route::get('logo', 'Common/getLogo');

        //省市区镇街列表
        Route::get('district', 'District/index');
        //社区列表
        Route::get('community', 'District/communityList');

        //人员信息
        Route::get('user/user', 'User/index');
        //子表信息最近变更过的人员列表(最近返义人员列表)
        Route::get('user/sub_change', 'User/subChange');
        //用户管理员
        Route::resource('user/manager', 'UserManager');

        //设置风险地区
        Route::resource('riskdistrict', 'RiskDistrict');
        //设置高风险地区（加强版）
        Route::resource('riskdistrictPro', 'RiskDistrictPro');
        //设置卡口地区
        Route::resource('barrier_district', 'BarrierDistrict');
        //场所码类型
        Route::get('place/type', 'Place/type');
        //已删除的场所码列表
        Route::get('place/delete_list', 'Place/deleteList');
        //设置场所
        Route::resource('place', 'Place');
        //恢复场所码
        Route::post('place/restore/:id', 'Place/restore');
        // 批量修改场所码的行程码等级
        Route::post('place/batchUpdateXcmLevel', 'Place/batchUpdateXcmLevel');
        // 批量修改场所码的是否人脸识别
        Route::post('place/batchUpdateIsNeedFace', 'Place/batchUpdateIsNeedFace');

        //场所码申报列表
        Route::get('placedeclare', 'PlaceDeclare/index');
        //场所码扫码异常列表
        Route::get('placedeclare/abnormal', 'PlaceDeclare/abnormal');
        //红黄码人员列表
        Route::get('placedeclare/code', 'PlaceDeclare/code');
        // 查看最近6次核酸检测结果
        Route::get('hsjc/result/log', 'PlaceDeclare/hsjcResultLog');
        //场所码扫码分表节点列表员列表
        Route::get('placedeclare/node', 'PlaceDeclareNode/index');

        //自主申报
        Route::get('owndeclare', 'OwnDeclare/index');
        //自主申报图片ocr识别
        Route::get('owndeclare_ocr', 'OwnDeclareOcr/index');
        //最近返义人员列表
        Route::get('owndeclare/recent_come', 'OwnDeclare/recentCome');

        // 异常信息-外出异常
        Route::get('dataerror/leave', 'DataError/leave');
        // 异常信息-来义异常
        Route::get('dataerror/come', 'DataError/come');
        // 异常信息-中高风险异常
        Route::get('dataerror/riskarea', 'DataError/riskarea');
        // 异常信息-短期重复申报
        Route::get('dataerror/todayMany', 'DataError/todayMany');
        // 异常信息-ocr
        Route::get('dataerror/ocr', 'DataError/ocr');
        // 异常信息-行程码带星
        Route::get('dataerror/travel_asterisk', 'DataError/travelAsterisk');
        // 异常信息-非绿码记录
        Route::get('dataerror/jkm_mzt', 'DataError/jkmmzt');
        // 预警信息-未按时反义
        Route::get('datawarning/backouttime', 'DataWarning/backouttime');
        // 预警信息-中高风险密接
        Route::get('datawarning/riskarea', 'DataWarning/riskarea');

        // 统计-来源地
        Route::get('statistic/fromwhere', 'Statistic/fromwhere');
        // 统计-在义街道
        Route::get('statistic/ywstreet', 'Statistic/ywstreet');
        // 统计-年龄段
        Route::get('statistic/age', 'Statistic/age');
        // 统计-核酸检测情况
        Route::get('statistic/jiance', 'Statistic/jiance');
        // 统计-户籍或者流动人口
        Route::get('statistic/huji', 'Statistic/huji');
        // 货车申报
        Route::resource('cardeclare', 'CarDeclare');

        //企业码
        //企业列表
        Route::get('company/index', 'Company/index');
        Route::get('company/read/:id', 'Company/read');
        Route::put('company/:id', 'Company/update');
        Route::delete('company/:id', 'Company/delete');
        //转移联络员
        Route::post('company/transfer_link', 'Company/transferLink');
        //不合格企业列表
        Route::get('company/unqualified', 'Company/unqualifiedList');
        //给不合格企业的联络员发送短信
        Route::get('company/unqualified/sms', 'Company/unqualifiedSms');
        //企业类型
        Route::resource('company/classify', 'CompanyClassify');
        //企业员工类型
        Route::resource('company/staff/classify', 'CompanyStaffClassify');
        //某企业的员工列表
        Route::get('company/staff', 'Company/staff');
        //员工加入的企业列表
        Route::get('staff/company', 'Company/staffCompanyList');
        //员工列表
        Route::get('staff/list', 'Company/staffList');
        Route::delete('staff/:id', 'Company/staffDelete');
        //修改企业码下的员工检测频率
        Route::post('staff/check_frequency', 'Company/checkFrequency');
        // 批量修改企业类型
        Route::post('company/batchUpdateCompanyClassify', 'Company/batchUpdateCompanyClassify');
        // 批量修改员工类型
        Route::post('company/batchUpdateCompanyStaffClassify', 'Company/batchUpdateCompanyStaffClassify');
        //管理员关注企业
        Route::post('admincompany/follow', 'AdminCompany/follow');
        //管理员取消关注企业
        Route::post('admincompany/unfollow', 'AdminCompany/unfollow');
        //我关注的企业列表
        Route::get('admincompany/followed', 'AdminCompany/index');

        //查询中心
        //人员健康信息
        Route::get('querycenter/health_info', 'QueryCenter/healthInfo');
        //人员管控状态列表
        Route::get('querycenter/rygk', 'QueryCenter/rygk');
        // 场所码分类每日汇总
        Route::get('querycenter/place_classify_date_nums', 'QueryCenter/placeClassifyDateNums');
        // 场所码行业每日汇总
        Route::get('querycenter/place_type_date_nums', 'QueryCenter/placeTypeDateNums');
        // 场所码镇街每日汇总
        Route::get('querycenter/place_street_date_nums', 'QueryCenter/placeStreetDateNums');
        // 场所码每日汇总(健康码,核酸检测,疫苗接种,行程码,管控状态)
        Route::get('querycenter/place_date_nums_by_name', 'QueryCenter/placeDateNumsByName');
        // 场所码小时汇总
        Route::get('querycenter/place_hour_nums', 'QueryCenter/placeHourNums');

        //个人防疫码
        //个人码清册
        Route::get('personal/code', 'PersonalCode/index');
        Route::put('personal/code/:id', 'PersonalCode/update');
        Route::delete('personal/code/:id', 'PersonalCode/delete');

        //系统参数配置
        Route::get('systemconfig', 'SystemConfig/index');
        Route::get('systemconfig/:id', 'SystemConfig/read');
        Route::put('systemconfig/:id', 'SystemConfig/update');
    })->middleware([
        \app\http\middleware\AllowOriginMiddleware::class,
        \app\http\middleware\admin\AdminAuthTokenMiddleware::class,
        \app\http\middleware\admin\AdminLogMiddleware::class
    ]);

    /**
     * 导入excel添加数据相关路由
     */
    Route::group('import', function () {
        // 场所验证
        Route::post('place/verify', 'import.ImportExcel/placeVerify');
        Route::post('gate/verify', 'import.ImportExcel/gateVerify');

    })->middleware([
        \app\http\middleware\AllowOriginMiddleware::class,
        \app\http\middleware\admin\AdminAuthTokenMiddleware::class,
        \app\http\middleware\admin\AdminLogMiddleware::class
    ]);

    /**
     * 基础
     */
    Route::group('common', function () {
        //全国人口库查询
        Route::get('qgrkk', 'common.Sgzx/qgrkk');

    })->middleware([
        \app\http\middleware\AllowOriginMiddleware::class,
        \app\http\middleware\admin\AdminAuthTokenMiddleware::class,
        \app\http\middleware\admin\AdminLogMiddleware::class
    ]);

    //大屏数据
    Route::group(function () {
        //人员申报时间
        Route::get('screen/declare_date', 'Screen/declareDate');
        //来源省份
        Route::get('screen/source_province', 'Screen/sourceProvince');
        //流动人口
        Route::get('screen/floating_population', 'Screen/floatingPopulation');
        //中高风险返义列表
        Route::get('screen/riskarea_come', 'Screen/riskareaCome');
        // 未按时返义
        Route::get('screen/backouttime', 'Screen/backouttime');
        //中高风险省返义人数（全国地图内使用）
        Route::get('screen/riskarea_province_come', 'Screen/riskareaProvinceCome');
        //中高风险返义/返义总人数（义乌地图内使用）
        Route::get('screen/come_yw_street', 'Screen/comeYWStreet');
        //总申报统计数据
        Route::get('screen/nums', 'Screen/nums');
        // 管控情况
        Route::get('screen/control', 'Screen/control');
        // 风险地区新增情况
        Route::get('screen/riskarea', 'Screen/riskarea');
    })->middleware([
        \app\http\middleware\AllowOriginMiddleware::class,
        \app\http\middleware\admin\AdminScreenTokenMiddleware::class,
    ]);

    /**
     * 导出excel相关路由
     */
    // Route::group('export', function () {
    //     //申报资料
    //     Route::get('owndeclare', 'ExportExcel/owndeclare');
    //     // 异常信息-外出异常
    //     Route::get('dataerror/leave', 'ExportExcel/dataerrorLeave');
    //     // 异常信息-来义异常
    //     Route::get('dataerror/come', 'ExportExcel/dataerrorCome');
    //     // 异常信息-中高风险异常
    //     Route::get('dataerror/riskarea', 'ExportExcel/dataerrorRiskarea');
    //     // 异常信息-短期重复申报
    //     Route::get('dataerror/todayMany', 'ExportExcel/dataerrorTodayMany');
    //     // 异常信息-行程码识别
    //     Route::get('dataerror/ocr', 'ExportExcel/dataerrorOcr');
    //     // 异常信息-行程码带星
    //     Route::get('dataerror/travel_asterisk', 'ExportExcel/dataerrorTravelAsterisk');
    //     // 异常信息-非绿码记录
    //     Route::get('dataerror/jkm_mzt', 'ExportExcel/dataerrorJkmmzt');
    //     // 预警信息-未按时反义
    //     Route::get('datawarning/backouttime', 'ExportExcel/datawarningBackouttime');
    //     // 预警信息-中高风险密接
    //     Route::get('datawarning/riskarea', 'ExportExcel/datawarningRiskarea');
    //     // 人员信息
    //     Route::get('user', 'ExportExcel/userList');
    //     // 统计-来源地
    //     Route::get('statistic/fromwhere', 'ExportExcel/statisticFromwhere');
    //     // 统计-在义街道
    //     Route::get('statistic/ywstreet', 'ExportExcel/statisticYwstreet');
    //     // 统计-年龄段
    //     Route::get('statistic/age', 'ExportExcel/statisticAge');
    //     //不合格企业列表
    //     Route::get('company/unqualified', 'ExportExcel/unqualifiedCompany');
    // })->middleware([
    //     \app\http\middleware\AllowOriginMiddleware::class,
    //     \app\http\middleware\admin\AdminAuthTokenMiddleware::class,
    //     \app\http\middleware\admin\AdminLogMiddleware::class
    // ]);

    /**
     * 导出csv相关路由
     */
    Route::group('csv', function () {
        //获取导出百分比
        Route::get('getCsvProgress', 'ExportCsv/getCsvProgress');

        // 人员信息
        Route::get('user', 'ExportCsv/userList');
        //子表信息最近变更过的人员列表(最近返义人员列表)
        Route::get('user/sub_change', 'ExportCsv/subChange');

        //申报资料
        Route::get('owndeclare', 'ExportCsv/owndeclare');
        //自主申报的最近返义人员列表
        Route::get('owndeclare/recent_come', 'ExportCsv/owndeclareRecentCome');

        // 异常信息-外出异常
        Route::get('dataerror/leave', 'ExportCsv/dataerrorLeave');
        // 异常信息-来义异常
        Route::get('dataerror/come', 'ExportCsv/dataerrorCome');
        // 异常信息-中高风险异常
        Route::get('dataerror/riskarea', 'ExportCsv/dataerrorRiskarea');
        // 异常信息-短期重复申报
        Route::get('dataerror/todayMany', 'ExportCsv/dataerrorTodayMany');
        // 异常信息-行程码识别
        Route::get('dataerror/ocr', 'ExportCsv/dataerrorOcr');
        // 异常信息-行程码带星
        Route::get('dataerror/travel_asterisk', 'ExportCsv/dataerrorTravelAsterisk');
        // 异常信息-非绿码记录
        Route::get('dataerror/jkm_mzt', 'ExportCsv/dataerrorJkmmzt');
        // 预警信息-未按时反义
        Route::get('datawarning/backouttime', 'ExportCsv/datawarningBackouttime');
        // 预警信息-中高风险密接
        Route::get('datawarning/riskarea', 'ExportCsv/datawarningRiskarea');
        // 统计-来源地
        Route::get('statistic/fromwhere', 'ExportCsv/statisticFromwhere');
        // 统计-在义街道
        Route::get('statistic/ywstreet', 'ExportCsv/statisticYwstreet');
        // 统计-年龄段
        Route::get('statistic/age', 'ExportCsv/statisticAge');

        //企业列表
        Route::get('company', 'ExportCsv/company');
        //不合格企业列表
        Route::get('company/unqualified', 'ExportCsv/unqualifiedCompany');
        Route::get('unqualified', 'ExportCsv/unqualifiedCompany'); // 前端少了个company.临时加的路由
        
        //场所码清册列表
        Route::get('place', 'ExportCsv/place');
        //场所码扫码列表
        Route::get('placedeclare', 'ExportCsv/placeDeclare');
        //场所码扫码异常列表
        Route::get('placedeclare/abnormal', 'ExportCsv/placeDeclareAbnormal');
        //红黄码人员列表
        Route::get('placedeclare/code', 'ExportCsv/placeDeclareCode');

        //当日汇总（24小时场所码扫码次数）
        Route::get('place/today_summary', 'ExportCsv/todaySummary');
        //前一天场所扫码合计（场所码每日数据）
        Route::get('place/preday_total', 'ExportCsv/predayTotal');
        //市场集团下属场所扫码合计
        //北苑街道副食品市场
        Route::get('place/beiyuan_fushipin', 'ExportCsv/beiyuanFushipin');
        //北苑街道果品市场
        Route::get('place/beiyuan_guopin', 'ExportCsv/beiyuanGuopin');
        //北苑街道收藏品市场
        Route::get('place/beiyuan_shoucangpin', 'ExportCsv/beiyuanShoucangpin');
        //北苑街道物资市场
        Route::get('place/beiyuan_wuzi', 'ExportCsv/beiyuanWuzi');
        //城西街道粮食市场
        Route::get('place/chengxi_liangshi', 'ExportCsv/chengxiLiangshi');
        //稠城街道家电市场
        Route::get('place/choucheng_jiadian', 'ExportCsv/chouchengJiadian');
        //稠江街道国际家居城或家具城
        Route::get('place/choujiang_jiaju', 'ExportCsv/choujiangJiaju');
        //稠江街道建材市场
        Route::get('place/choujiang_jiancai', 'ExportCsv/choujiangJiancai');
        //佛堂镇木材市场
        Route::get('place/fotang_mucai', 'ExportCsv/fotangMucai');
        //佛堂镇浙中农副产品物流中心
        Route::get('place/fotang_nongfuchanpin', 'ExportCsv/fotangNongfuchanpin');
        //后宅街道二手车中心
        Route::get('place/houzhai_ershouche', 'ExportCsv/houzhaiErshouche');
        //上溪镇模具城
        Route::get('place/shangxi_muju', 'ExportCsv/shangxiMuju');
        // 闸机厂商
        Route::get('gate_factory', 'ExportCsv/gateFactory');
        // 闸机
        Route::get('gate', 'ExportCsv/gate');
        // 闸机通行记录
        Route::get('gate_declare', 'ExportCsv/gateDeclare');

        //查询中心
        //人员管控状态列表
        Route::get('querycenter/rygk', 'ExportCsv/querycenterRygk');

        //个人防疫码
        //个人防疫码清册
        Route::get('personal/code', 'ExportCsv/personalCode');
    })->middleware([
        \app\http\middleware\AllowOriginMiddleware::class,
        \app\http\middleware\admin\AdminAuthTokenMiddleware::class,
        \app\http\middleware\admin\AdminLogMiddleware::class
    ]);

    /**
     * 附件相关路由
     */
    Route::group('file', function () {
        //附件列表
        Route::get('file', 'file.SystemAttachment/index');
        //删除图片和数据记录
        Route::post('file/delete', 'file.SystemAttachment/delete');
        //移动图片分来表单
        Route::get('file/move', 'file.SystemAttachment/move');
        //移动图片分类
        Route::put('file/do_move', 'file.SystemAttachment/moveImageCate');
        //修改图片名称
        Route::put('file/update/:id', 'file.SystemAttachment/update');
        //上传图片
        Route::post('upload/[:upload_type]', 'file.SystemAttachment/upload');
        //附件分类管理资源路由
        Route::resource('category', 'file.SystemAttachmentCategory');

        //上传附件
        Route::post('attach/upload', 'file.SystemUpload/attach');
        //上传临时文件
        Route::post('tmp/upload', 'file.SystemUpload/tmp');
        Route::post('base64img/upload', 'file.SystemUpload/base64Img');
    })->middleware([
        \app\http\middleware\AllowOriginMiddleware::class,
        \app\http\middleware\admin\AdminAuthTokenMiddleware::class,
        \app\http\middleware\admin\AdminLogMiddleware::class
    ]);

    /**
     * 系统设置维护 系统权限管理、系统菜单管理 系统配置 相关路由
     */
    Route::group('setting', function () {
        // 
        Route::post('setSafepwd', 'setting.SystemAdmin/setSafepwd');
        //退出登陆
        Route::get('admin/logout', 'setting.SystemAdmin/logout')->name('SystemAdminLogout');
        //修改状态
        Route::put('set_status/:id/:status', 'setting.SystemAdmin/set_status')->name('SystemAdminSetStatus');
        //获取当前管理员信息
        Route::get('info', 'setting.SystemAdmin/info')->name('SystemAdminInfo');
        //修改当前管理员信息
        Route::put('update_admin', 'setting.SystemAdmin/update_admin')->name('SystemAdminUpdateAdmin');
        //管理员资源路由
        Route::resource('admin', 'setting.SystemAdmin')->except(['read']);
        //批量添加管理员数据
        Route::post('admin/batch', 'setting.SystemAdmin/adminBatchSave');
        //获取当前管理员所属角色列表
        Route::get('admin/roles', 'setting.SystemAdmin/adminRoles');
        //当前管理员切换角色
        Route::post('admin/roles/:role_id', 'setting.SystemAdmin/switchAdminRoles');
        //绑定手机号
        Route::post('phone/binding', 'setting.SystemAdmin/phoneBinding');
        //批量设置角色
        Route::post('batchRole', 'setting.SystemAdmin/batchRole');
        //批量设置状态
        Route::post('admin/batchStatus', 'setting.SystemAdmin/batchStatus');

        //修改显示
        Route::put('menus/show/:id', 'setting.SystemMenus/show')->name('SystemMenusShow');
        //身份列表
        Route::get('role', 'setting.SystemRole/index');
        //身份选择列表
        Route::get('role_select', 'setting.SystemRole/roleSelect');
        //身份权限列表
        Route::get('role/create', 'setting.SystemRole/create');
        //编辑详情
        Route::get('role/:id/edit', 'setting.SystemRole/edit');
        //保存新建或编辑
        Route::post('role/:id', 'setting.SystemRole/save');
        //修改身份状态
        Route::put('role/set_status/:id/:status', 'setting.SystemRole/set_status');
        //删除身份
        Route::delete('role/:id', 'setting.SystemRole/delete');
        //修改配置分类状态
        Route::put('config_class/set_status/:id/:status', 'setting.SystemConfigTab/set_status');
        //修改配置状态
        Route::put('config/set_status/:id/:status', 'setting.SystemConfig/set_status');
        //基本配置编辑表单
        Route::get('config/header_basics', 'setting.SystemConfig/header_basics');
        //基本配置编辑表单
        Route::get('config/edit_basics', 'setting.SystemConfig/edit_basics');
        //基本配置保存数据
        Route::post('config/save_basics', 'setting.SystemConfig/save_basics');
        //基本配置上传文件
        Route::post('config/upload', 'setting.SystemConfig/file_upload');
        //组合数据全部
        Route::get('group_all', 'setting.SystemGroup/getGroup');
        //修改数据状态
        Route::get('group_data/header', 'setting.SystemGroupData/header');
        //修改数据状态
        Route::put('group_data/set_status/:id/:status', 'setting.SystemGroupData/set_status');
        //获取城市数据列表
        Route::get('city/list/:parent_id', 'setting.SystemCity/index');
        //添加城市数据表单
        Route::get('city/add/:parent_id', 'setting.SystemCity/add');
        //修改城市数据表单
        Route::get('city/:id/edit', 'setting.SystemCity/edit');
        //新增/修改城市数据
        Route::post('city/save', 'setting.SystemCity/save');
        //修改城市数据表单
        Route::delete('city/del/:city_id', 'setting.SystemCity/delete');
        //清除城市数据缓存
        Route::get('city/clean_cache', 'setting.SystemCity/clean_cache');
        //城市数据接口
        Route::get('shipping_templates/city_list', 'setting.ShippingTemplates/city_list');
        //获取隐私协议
        Route::get('get_user_agreement', 'setting.SystemGroupData/getUserAgreement');
        //设置隐私协议
        Route::post('set_user_agreement', 'setting.SystemGroupData/setUserAgreement');
        //个人中心菜单数据字段
        Route::get('usermenu_data/header', 'setting.SystemGroupData/header');
        //个人中心菜单数据状态
        Route::put('usermenu_data/set_status/:id/:status', 'setting.SystemGroupData/set_status');
        //个人中心菜单配置资源
        Route::resource('usermenu_data', 'setting.SystemGroupData');
        //组合数据资源路由
        Route::resource('group', 'setting.SystemGroup');
        //组合数据子数据资源路由
        Route::resource('group_data', 'setting.SystemGroupData');
        //配置分类资源路由
        Route::resource('config_class', 'setting.SystemConfigTab');
        //配置资源路由
        Route::resource('config', 'setting.SystemConfig');
        //权限菜单资源路由
        Route::resource('menus', 'setting.SystemMenus');
    })->middleware([
        \app\http\middleware\AllowOriginMiddleware::class,
        \app\http\middleware\admin\AdminAuthTokenMiddleware::class,
        \app\http\middleware\admin\AdminLogMiddleware::class
    ]);

    /**
     * 维护 相关路由
     */
    Route::group('system', function () {
        //系统日志
        Route::get('log', 'system.SystemLog/index')->name('SystemLog');
        //系统日志管理员搜索条件
        Route::get('log/search_admin', 'system.SystemLog/search_admin');
        //文件校验
        Route::get('file', 'system.SystemFile/index')->name('SystemFile');
        //打开目录
        Route::get('file/opendir', 'system.SystemFile/opendir');
        //读取文件
        Route::get('file/openfile', 'system.SystemFile/openfile');
        //保存文件
        Route::post('file/savefile', 'system.SystemFile/savefile');
        //数据所有表
        Route::get('backup', 'system.SystemDatabackup/index');
        //数据备份详情
        Route::get('backup/read', 'system.SystemDatabackup/read');
        //数据备份 优化表
        Route::put('backup/optimize', 'system.SystemDatabackup/optimize');
        //数据备份 修复表
        Route::put('backup/repair', 'system.SystemDatabackup/repair');
        //数据备份 备份表
        Route::put('backup/backup', 'system.SystemDatabackup/backup');
        //备份记录
        Route::get('backup/file_list', 'system.SystemDatabackup/fileList');
        //删除备份记录
        Route::delete('backup/del_file', 'system.SystemDatabackup/delFile');
        //导入备份记录表
        Route::post('backup/import', 'system.SystemDatabackup/import');
        //清除用户数据
        Route::get('clear/:type', 'system.SystemClearData/index');
        //清除缓存
        Route::get('refresh_cache/cache', 'system.Clear/refresh_cache');
        //清除日志
        Route::get('refresh_cache/log', 'system.Clear/delete_log');
        //域名替换接口
        Route::post('replace_site_url', 'system.SystemClearData/replaceSiteUrl');

    })->middleware([
        \app\http\middleware\AllowOriginMiddleware::class,
        \app\http\middleware\admin\AdminAuthTokenMiddleware::class,
        \app\http\middleware\admin\AdminCkeckRoleMiddleware::class,
        \app\http\middleware\admin\AdminLogMiddleware::class
    ]);
    

    /**
     * 闸机 相关路由
     */
    Route::group('gate', function () {
        // 闸机厂商
        Route::resource('gatefactory', 'GateFactory');
        Route::resource('gate', 'Gate');
        Route::resource('gateDeclare', 'GateDeclare');
        Route::post('batchSaveGate','Gate/batchSave');

    })->middleware([
        \app\http\middleware\AllowOriginMiddleware::class,
        \app\http\middleware\admin\AdminAuthTokenMiddleware::class,
        //\app\http\middleware\admin\AdminCkeckRoleMiddleware::class,
        \app\http\middleware\admin\AdminLogMiddleware::class
    ]);

    /**
     * miss 路由
     */
    Route::miss(function () {
        if (app()->request->isOptions()) {
            $header = Config::get('cookie.header');
            $header['Access-Control-Allow-Origin'] = app()->request->header('origin');
            return Response::create('ok')->code(200)->header($header);
        } else
            return Response::create()->code(404);
    });
})->prefix('admin.');
