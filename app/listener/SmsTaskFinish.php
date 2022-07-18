<?php
declare (strict_types = 1);

namespace app\listener;

class SmsTaskFinish
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
        var_dump($event[2]);
        return;
    }
}
