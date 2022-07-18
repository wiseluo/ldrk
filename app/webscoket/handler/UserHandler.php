<?php

namespace app\webscoket\handler;

use app\services\message\service\StoreServiceRecordServices;
use app\services\user\UserAuthServices;
use app\webscoket\BaseHandler;
use app\webscoket\Response;
use crmeb\exceptions\AuthException;

/**
 * Class UserHandler
 * @package app\webscoket\handler
 */
class UserHandler extends BaseHandler
{

    /**
     * 用户登陆
     * @param array $data
     * @param Response $response
     * @return bool|\think\response\Json|null
     */
    public function login(array $data = [], Response $response)
    {
        // 游客登陆
        if (isset($data['tourist']) && $data['tourist']) {
            return $response->success();
        }

        if (!isset($data['token']) || !$token = $data['token']) {
            return $response->fail('授权失败!');
        }

        try {
            /** @var UserAuthServices $services */
            $services = app()->make(UserAuthServices::class);
            $authInfo = $services->parseToken($token);
        } catch (AuthException $e) {
            return $response->fail($e->getMessage());
        }

        $user = $authInfo['user'];
        /** @var StoreServiceRecordServices $service */
        $service = app()->make(StoreServiceRecordServices::class);
        $service->updateRecord(['to_uid' => $user->uid], ['online' => 1, 'type' => $res['form_type'] ?? 1]);
        //给所有在线客服人员发送当前用户上线消息
        $this->manager->pushing(array_keys($this->room->getKefuRoomAll()), $response->message('user_online', [
            'uid' => $user->uid,
            'online' => 1
        ])->getData(), $this->fd);

        return $response->success('login', $user->toArray());
    }

}
