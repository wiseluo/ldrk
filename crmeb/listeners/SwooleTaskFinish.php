<?php



namespace crmeb\listeners;

use app\webscoket\Manager;
use crmeb\interfaces\ListenerInterface;
use Swoole\Server;
use Swoole\Server\Task;
use think\facade\Log;

class SwooleTaskFinish
{
    /**
     * 事件监听处理
     *
     * @return mixed
     */
    public function handle($event)
    {
        echo "finish";
        //这里的第三个索引才是onTask传入的data数据
        // var_dump($event[2]);

        $task_data = $event[2];

        if($task_data['type'] == 'dgwork'){
            // 如果有放入队列。去掉队列里的
            $action = $task_data['data']['data']['data']['action'];
            var_dump($action.'：完成');
        }
        return;
    }
}
