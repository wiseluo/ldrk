<?php

namespace app\controller\applet;

use think\facade\App;
use crmeb\basic\BaseController;
use app\services\applet\UserServices;
use app\validate\applet\UserValidate;
use think\facade\Config;
use app\dao\CompanyDao;
use app\dao\UserManagerDao;
use app\services\applet\SchoolServices;

class User extends BaseController
{
    public function __construct(App $app, UserServices $service)
    {
        parent::__construct($app);
        $this->service = $service;
    }

    public function info()
    {
        $user = $this->request->userInfo();
        
        // 距今时间
        if (isset($user['hsjc_time']) && !empty($user['hsjc_time'])) {
            if (intval((time() - strtotime($user['hsjc_time']) ) /3600) <= 72) {
                $user['jj_time'] = intval((time() - strtotime($user['hsjc_time']) ) /3600).'小时';
            }else {
                $user['jj_time'] = '大于72小时';
            }
            
        }else {
            $user['jj_time'] = '';
        }

        $company = app()->make(CompanyDao::class)->get(['link_id' => $user['id']]);
        //企业码查看员工权限
        $user['qym_status'] = 0;
        if ($company) {
            $user['qym_status'] = 1;
        }
        //反扫场所码权限
        $user_admin = app()->make(UserManagerDao::class)->get(['user_id' => $user['id']]);
        $user['reverse_scan_status'] = 0;
        if ($user_admin) {
            $user['reverse_scan_status'] = 1;
        }

        // 判断用户身份
        // $res = app()->make(SchoolServices::class)->identityInspector($this->request->tokenUser());
        // $user['is_teacher'] = $res['data']['is_teacher'];
        // $user['is_family'] =  $res['data']['is_family'];
        return show(200, '成功', $user);
    }

    public function xcmVerify()
    {
        $param = $this->request->param();
        $validate = new UserValidate();
        if (!$validate->scene('xcmVerify')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->service->xcmVerifyServices($param, $this->request->tokenUser()['id']);
        if ($res['status'] == 1) {
            return show(200, $res['msg'], $res['data']);
        } else if ($res['status'] == 2) {
            return show(4005, $res['msg']);
        } else {
            return show(400, $res['msg']);
        }
    }

    public function replaceXcmVerify()
    {
        $param = $this->request->param();
        $validate = new UserValidate();
        if (!$validate->scene('replaceXcmVerify')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->service->replaceXcmVerifyServices($param, $this->request->tokenUser());
        if ($res['status'] == 1) {
            return show(200, $res['msg'], $res['data']);
        } else if ($res['status'] == 2) {
            return show(4005, $res['msg']);
        } else {
            return show(400, $res['msg']);
        }
    }

    public function clean()
    {
        $res = $this->service->cleanService($this->request->tokenUser()['id']);
        if ($res['status']) {
            return show(200, '正在重新授权，请稍候');
        } else {
            return show(400, $res['msg']);
        }
    }

    public function checkFrequency()
    {
        $param = $this->request->param();
        $validate = new UserValidate();
        if (!$validate->scene('checkFrequency')->check($param)) {
            return show(400, $validate->getError());
        }
        $res = $this->service->checkFrequencyService($param, $this->request->tokenUser()['id']);
        if ($res['status'] == 1) {
            return show(200, $res['msg']);
        } else {
            return show(400, $res['msg']);
        }
    }

    public function subInfoRead()
    {
        $res = $this->service->subInfoReadService($this->request->tokenUser()['id']);
        if ($res['status']) {
            return show(200, $res['msg'], $res['data']);
        } else {
            return show(404, $res['msg']);
        }
    }

    public function subInfo()
    {
        $param = $this->request->param();
        $validate = new UserValidate();
        if (!$validate->scene('subInfo')->check($param)) {
            return show(400, $validate->getError());
        }
        $param['street_id'] = $this->request->param('street_id', 0);
        $param['street'] = $this->request->param('street', '');
        $param['community_id'] = $this->request->param('community_id', 0);
        $param['community'] = $this->request->param('community', '');
        $res = $this->service->subInfoService($param, $this->request->tokenUser()['id']);
        if ($res['status'] == 1) {
            return show(200, $res['msg']);
        } else {
            return show(400, $res['msg']);
        }
    }

    // 人脸识别
    public function faceCheck()
    {
        $param = $this->request->param();
        $res = $this->service->faceCheckService($param, $this->request->tokenUser()['id']);
        if ($res['status'] == 1) {
            return show(200, $res['msg']);
        } else {
            return show(400, $res['msg']);
        }
    }
}
