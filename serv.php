<?php

 

class Server

{

    private $serv;

 

    public function __construct() {

        $this->serv = new swoole_server("0.0.0.0", 9502);

        $this->serv->set([

            'worker_num'      => 3,

            'task_worker_num' => 3,

        ]);

        $this->serv->on('Start', function ($serv) {

            echo "SWOOLE:".SWOOLE_VERSION . " 服务已启动".PHP_EOL;

            echo "SWOOLE_CPU_NUM:".swoole_cpu_num().PHP_EOL;

        });

        $this->serv->on('Receive', function ($serv, $fd, $from_id, $data) { });

        $this->serv->on('Task', function ($serv, $task) { });

        $this->serv->on('Finish', function ($serv, $task_id, $data) {});

        $this->serv->start();

    }

}

$server = new Server();