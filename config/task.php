<?php
//swoole.task配置文件
return [
    //任务关键字提取
    'key' => 'cmd',
    //任务别名
    'alias' => [
        //名称
        'order' => [
            //调用类
            'class' => \app\job\Task::class,
            //执行方法
            'methods' => [
                'func1',
                'func2',
            ],
            //触发task.finish
            'finish' => true
        ]
    ],
];