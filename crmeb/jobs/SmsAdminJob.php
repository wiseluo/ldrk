<?php

namespace crmeb\jobs;


use app\services\message\sms\SmsSendServices;
use crmeb\basic\BaseJob;
use think\facade\Log;


class SmsAdminJob extends BaseJob
{
    /**
     * 退款发送管理员消息任务
     * @param $switch
     * @param $adminList
     * @param $order
     * @return bool
     */
    public function sendAdminRefund($switch, $adminList, $order)
    {
        if (!$switch) {
            return true;
        }
        try {
            /** @var SmsSendServices $smsServices */
            $smsServices = app()->make(SmsSendServices::class);
            foreach ($adminList as $item) {
                $data = ['order_id' => $order['order_id'], 'admin_name' => $item['nickname']];
                $smsServices->send(true, $item['phone'], $data, 'ADMIN_RETURN_GOODS_CODE');
            }
        } catch (\Throwable $e) {
            Log::error('退款发送管理员消息失败，原因：' . $e->getMessage());
        }
        return true;
    }

    /**
     * 用户确认收货管理员短信提醒
     * @param $switch
     * @param $adminList
     * @param $order
     * @return bool
     */
    public function sendAdminConfirmTakeOver($switch, $adminList, $order)
    {
        if (!$switch) {
            return true;
        }
        try {
            /** @var SmsSendServices $smsServices */
            $smsServices = app()->make(SmsSendServices::class);
            foreach ($adminList as $item) {
                $data = ['order_id' => $order['order_id'], 'admin_name' => $item['nickname']];
                $smsServices->send(true, $item['phone'], $data, 'ADMIN_TAKE_DELIVERY_CODE');
            }
        } catch (\Throwable $e) {
            Log::error('用户确认收货管理员短信提醒失败，原因：' . $e->getMessage());
        }
        return true;
    }
}
