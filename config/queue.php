<?php

use think\facade\Env;
// 有三种模式，同步模式/database（数据库）/Redis 三种

return [
    'default'     => 'redis',
    'connections' => [
        // 同步摸索
        'sync'     => [
            'driver' => 'sync',
        ],
        // 数据库摸索
        'database' => [
            'driver' => 'database',
            'queue'  => 'default',
            'table'  => 'jobs',
        ],
        // Redis摸索
        'redis'    => [
            'driver'     => 'redis',
            'queue'      => Env::get('queue.listen_name','CRMEB'),
            'host'       => Env::get('redis.redis_hostname','127.0.0.1'),
            'port'       => Env::get('redis.port',6379),
            'password'   => Env::get('redis.redis_password',''),
            'select'     => Env::get('redis.redis_password',0),
            'timeout'    => 0,
            'persistent' => true,
        ],
    ],
    'failed'      => [
        'type'  => 'none',
        'table' => 'failed_jobs',
    ],
];
