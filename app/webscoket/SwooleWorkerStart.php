<?php

namespace app\webscoket;

use crmeb\interfaces\ListenerInterface;
use Swoole\Server;
use Swoole\Timer;
use think\Config;
use think\facade\Log;

/**
 * Class SwooleWorkerStart
 * @package app\webscoket
 * @author xaboy
 * @day 2020-04-29
 */
class SwooleWorkerStart implements ListenerInterface
{

    /**
     * @var \Swoole\WebSocket\Server
     */
    protected $server;

    /**
     * @var Config
     */
    protected $config;

    /**
     * 定时器执行间隔(毫秒)
     * @var int
     */
    protected $interval = 2000;

    /**
     * SwooleWorkerStart constructor.
     * @param Server $server
     * @param Config $config
     */
    public function __construct(Server $server, Config $config)
    {
        $this->server = $server;
        $this->config = $config;
    }

    /**
     * @param $event
     * @author xaboy
     * @day 2020-04-29
     */
    public function handle($event): void
    {
        if ($this->server->worker_id == ($this->config->get('swoole.server.options.worker_num')) && $this->config->get('swoole.websocket.enable', false)) {
            $this->ping();
            $this->timer();
        }
    }

    /**
     * @author xaboy
     * @day 2020-05-06
     */
    protected function ping()
    {
        /**
         * @var $pingService Ping
         */
        $pingService = app()->make(Ping::class);
        $server = $this->server;
        $timeout = intval($this->config->get('swoole.websocket.ping_timeout', 60000) / 1000);
        Timer::tick(1500, function (int $timer_id) use (&$server, &$pingService, $timeout) {
            $nowTime = time();
            foreach ($server->connections as $fd) {
                if ($server->isEstablished($fd)) {
                    $last = $pingService->getLastTime($fd);
                    if ($last && ($nowTime - $last) > $timeout) {
                        $server->push($fd, 'timeout');
                        $server->close($fd);
                    }
                }
            }
        });
    }

    /**
     * 开启定时器
     */
    protected function timer()
    {
        //开启定时器
        $last = time();
        $task = [6 => $last, 10 => $last, 30 => $last, 60 => $last, 180 => $last, 300 => $last];
        $this->timer = Timer::tick($this->interval, function () use (&$task) {
            try {
                $now = time();
                event('Task_2');
                foreach ($task as $sec => $time) {
                    if ($now - $time >= $sec) {
                        event('Task_' . $sec);
                        $task[$sec] = $now;
                    }
                }
            } catch (\Throwable $e) {
                Log::error($e->getMessage());
            }
        });
    }
}
