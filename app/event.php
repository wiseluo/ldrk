<?php

// 事件定义文件
return [
    'bind' => [

    ],

    'listen' => [
        'AppInit' => [],
        'HttpRun' => [],
        'HttpEnd' => [],
        'LogLevel' => [],
        'LogWrite' => [],
        // 'swoole.task' => [\crmeb\listeners\SwooleTaskListen::class],//异步任务 事件
        'swoole.init' => [],//swoole 初始化事件
        'swoole.start' => [],//swoole 启动事件
        'swoole.shutDown' => [],//swoole 停止事件
        'swoole.workerStart' => [],//socket 启动事件
        'swoole.websocket.user' => [],//socket 用户调用事件
        'swoole.websocket.admin' => [],//socket 后台事件
        'swoole.websocket.kefu' => [],//socket 客服事件
        'swoole.task'=>[
            \crmeb\listeners\SwooleTaskListen::class,
        ],
        'swoole.finish'=>[
            \crmeb\listeners\SwooleTaskFinish::class
        ],
    ],

    'subscribe' => [
        // \crmeb\subscribes\TaskSubscribe::class
    ],
];
