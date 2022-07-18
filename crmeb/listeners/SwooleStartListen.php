<?php

namespace crmeb\listeners;


use app\webscoket\Room;
use crmeb\interfaces\ListenerInterface;
use crmeb\services\CacheService;
use think\facade\Log;

/**
 * swoole启动监听
 * Class SwooleStartListen
 * @package crmeb\listeners
 */
class SwooleStartListen implements ListenerInterface
{

    /**
     * 事件执行
     * @param $event
     */
    public function handle($event): void
    {
        try {
            //重启过后重置房间人
            /** @var Room $room */
            $room = app()->make(Room::class);
            $room->setCache(CacheService::redisHandler())->remove();
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
        }
    }
}
