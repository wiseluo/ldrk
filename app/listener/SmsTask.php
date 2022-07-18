<?php
declare (strict_types = 1);

namespace app\listener;

class SmsTask
{
    /**
     * 事件监听处理
     *
     * @return mixed
     */
    public function handle($event)
    {
        var_dump($event->data);//event的data数据即server->task()传入的数据
        echo "开始发送短信：".time().PHP_EOL;
        //模拟耗时 3 秒，测试是否在响应事件内
        sleep(3);
        echo "短信发送成功：".time().PHP_EOL;

        // 可以调用 finish 方法通知其他事件类，通知当前异步任务已经完成了（非必须调用）
        // 参数 $event 是 Swoole\Server\Task 类的一个对象 可以调用 finish 方法触发 task 任务的 onFinish 事件
        $event->finish($event->data);
    }
}
