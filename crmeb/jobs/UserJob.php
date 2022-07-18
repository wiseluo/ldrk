<?php

namespace crmeb\jobs;


use app\services\user\UserServices;
use app\services\wechat\WechatUserServices;
use crmeb\basic\BaseJob;
use think\facade\Log;
use crmeb\utils\Queue;

class UserJob extends BaseJob
{
    /**
     * 执行同步数据后
     * @param $openids
     * @return bool
     */
    public function doJob($openids)
    {
        if (!$openids || !is_array($openids)) {
            return true;
        }
        $noBeOpenids = [];
        try {
            /** @var WechatUserServices $wechatUser */
            $wechatUser = app()->make(WechatUserServices::class);
            $noBeOpenids = $wechatUser->syncWechatUser($openids);
        } catch (\Throwable $e) {
            Log::error('更新wechatUser用户信息失败,失败原因:' . $e->getMessage());
        }
        if (!$noBeOpenids) {
            return true;
        }
        try {
            /** @var UserServices $user */
            $user = app()->make(UserServices::class);
            $user->importUser($noBeOpenids);
        } catch (\Throwable $e) {
            Log::error('新增用户失败,失败原因:' . $e->getMessage());
        }
        try {
            /** @var UserServices $user */
            $user = app()->make(UserServices::class);
            $user->adminSendCoupon();
        } catch (\Throwable $e) {
            Log::error('主动发放优惠券失败,失败原因:' . $e->getMessage());
        }
        return true;
    }
}
