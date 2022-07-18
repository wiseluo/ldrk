<?php

namespace app\controller;

use app\dao\UserDao;
use think\facade\View;
use EasyWeChat\Factory;
use think\facade\Config;

class H5Controller
{

    public function personalCode(){
        $param = request()->param();
        $config = [
            'app_id' => 'wxf624448f566155c5',            
            'secret' => '92a4d14befee0aaf0fecb2fb30a310f0',
            'response_type' => 'array',
        ];
            
        $app = Factory::officialAccount($config);
        if(Config::get('app.app_host') == 'dev') { //测试环境
            $url = 'https://ldrk.hejieshangcheng.com/personal_code?uniqid='.$param['uniqid'];
        }else{
            $url = 'https://yqfk.yw.gov.cn/personal_code?uniqid='.$param['uniqid'];
        }
        $app->jssdk->setUrl($url);
        $wxconfig = $app->jssdk->buildConfig(['scanQRCode','chooseImage','previewImage'], true); // （必须有，不然安卓不显示）

        $wxconfig = json_decode($wxconfig,true);
        $wxconfig['debug'] = true;
        // $wxconfig['openTagList'] = ['wx-open-launch-app'];

        View::assign('uniqid', $param['uniqid']);
        View::assign('wxconfig', $wxconfig);

        return view('personalCode');

    }


}