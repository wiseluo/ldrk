<?php



namespace app\webscoket;


use think\facade\Cache;
use think\facade\Log;

/**
 * Class Ping
 * @package app\webscoket
 * @author xaboy
 * @day 2020-04-29
 */
class Ping
{
    /**
     * @var \Redis
     */
    protected $redis;


    const CACHE_PINK_KEY = 'ws.p.';


    const CACHE_SET_KEY = 'ws.s';


    /**
     * Ping constructor.
     */
    public function __construct()
    {
        try {
            $this->redis = Cache::store('redis')->handler();
            $this->destroy();
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * @param $id
     * @param $time
     * @param int $timeout
     * @author xaboy
     * @day 2020-04-29
     */
    public function createPing($id, $time, $timeout = 0)
    {
        $this->updateTime($id, $time, $timeout);
        $this->redis->sAdd(self::CACHE_SET_KEY, $id);
    }

    /**
     * @param $id
     * @param $time
     * @param int $timeout
     * @author xaboy
     * @day 2020-04-29
     */
    public function updateTime($id, $time, $timeout = 0)
    {
        $this->redis->set(self::CACHE_PINK_KEY . $id, $time, $timeout);
    }

    /**
     * @param $id
     * @author xaboy
     * @day 2020-05-06
     */
    public function removePing($id)
    {
        $this->redis->del(self::CACHE_PINK_KEY . $id);
        $this->redis->del(self::CACHE_SET_KEY, $id);
    }

    /**
     * @param $id
     * @return bool|string
     */
    public function getLastTime($id)
    {
        try {
            return $this->redis->get(self::CACHE_PINK_KEY . $id);
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            return null;
        }

    }

    /**
     * @author xaboy
     * @day 2020-04-29
     */
    public function destroy()
    {
        $members = $this->redis->sMembers(self::CACHE_SET_KEY) ?: [];
        foreach ($members as $k => $member) {
            $members[$k] = self::CACHE_PINK_KEY . $member;
        }
        if (count($members))
            $this->redis->sRem(self::CACHE_SET_KEY, ...$members);
    }
}
