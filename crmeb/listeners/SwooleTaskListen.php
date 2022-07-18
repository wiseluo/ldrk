<?php



namespace crmeb\listeners;

use app\webscoket\Manager;
use crmeb\interfaces\ListenerInterface;
use Swoole\Server;
use Swoole\Server\Task;
use think\facade\Log;
use app\services\MessageServices;
use app\services\user\AliyunServices;
use app\services\user\UserServices;
use app\services\PlaceServices;
use app\services\CompanyTaskServices;
use app\services\ProcessDeclareServices;
use app\services\admin\ExportServices;
use app\services\admin\ExportCsvServices;
use app\services\CarDeclareServices;
use app\services\PersonalCodeTaskServices;
use app\services\AinatTaskServices;
use app\services\SchoolTaskServices;

class SwooleTaskListen implements ListenerInterface
{
    /**
     * @var Task
     */
    protected $task;

    public function handle($task): void
    {
        $this->task = $task;
        if (method_exists($this, $task->data['type'])) {
            $this->{$task->data['type']}($task->data['data']);
        } else {
            Log::error('任务执行失败,' . $task->data['type'] . '方法不存在');
        }
        //        异步事件执行回调
        //        $task->finish($task->data);
        return;
    }

    // public function message(array $data)
    // {
    //     /** @var Server $server */
    //     $server = app()->make(Server::class);
    //     $uid = is_array($data['uid']) ? $data['uid'] : [$data['uid']];
    //     $except = $data['except'] ?? [];
    //     if (!count($uid) && $data['type'] != 'user') {
    //         $fds = Manager::userFd(0);
    //         foreach ($fds as $fd) {
    //             if (!in_array($fd, $except) && $server->isEstablished($fd))
    //                 $server->push((int)$fd, json_encode($data['data']));
    //         }
    //     } else {
    //         foreach ($uid as $id) {
    //             $fds = Manager::userFd(array_search($data['type'], Manager::USER_TYPE), $id);
    //             foreach ($fds as $fd) {
    //                 if (!in_array($fd, $except) && $server->isEstablished($fd))
    //                     $server->push((int)$fd, json_encode($data['data']));
    //             }
    //         }
    //     }
    // }

    //发送消息任务
    public function msg($data)
    {
        Log::error('执行异步msg任务');
        $task_data = $data['data']['data'];
        try {
            app()->make(MessageServices::class)->{$task_data['action']}($task_data['param']);
        } catch (\Exception $e) {
            //test_log($e->getMessage());
        }
    }

    public function user($data)
    {
        $task_data = $data['data']['data'];
        try {
            $res = app()->make(UserServices::class)->{$task_data['action']}($task_data['param']);

            if ($res['status'] == 1) {
                $this->task->finish($this->task->data);
            }
        } catch (\Exception $e) {
            //test_log($e->getMessage());
        }
    }

    // 阿里云任务
    public function aliyun(array $data)
    {
        $task_data = $data['data']['data'];
        try {
            $res = app()->make(AliyunServices::class)->{$task_data['action']}($task_data['param']);

            if ($res['status'] == 1) {
                $this->task->finish($this->task->data);
            }
        } catch (\Exception $e) {
            //test_log($e->getMessage());
        }
    }

    //人口库任务
    // public function rkk($data)
    // {
    //     $task_data = $data['data']['data'];
    //     //test_log('开始人口库任务'.$task_data['action'].'->'.json_encode($task_data['param']));
    //     try{
    //         $res = app()->make(SgzxServices::class)->{$task_data['action']}($task_data['param']);
    //         //test_log('任务->返回');
    //         if($res['status'] == 1){
    //             $this->task->finish($this->task->data);
    //         }
    //     } catch (\Exception $e){
    //         //test_log($e->getMessage());
    //     }
    // }

    //申报任务
    public function

    declare($data)
    {
        $task_data = $data['data']['data'];
        //test_log('开始申报任务'.$task_data['action'].'->'.json_encode($task_data['param']));
        try {
            $res = app()->make(ProcessDeclareServices::class)->{$task_data['action']}($task_data['param']);
            //test_log('任务->返回');
            // if($res['status'] == 1){
            //     $this->task->finish($this->task->data);
            // }
        } catch (\Exception $e) {
            //test_log($e->getMessage());
        }
    }

    // 货车申报任务
    public function carDeclare($data)
    {
        $task_data = $data['data']['data'];
        try {
            $res = app()->make(CarDeclareServices::class)->{$task_data['action']}($task_data['param']);
        } catch (\Exception $e) {
            //test_log($e->getMessage());
        }
    }

    // 导出任务
    public function export(array $data)
    {
        $task_data = $data['data']['data'];
        try {
            app()->make(ExportServices::class)->{$task_data['action']}($task_data['param']);
        } catch (\Exception $e) {
            test_log('export error:' . $e->getMessage());
        }
    }

    // 导出任务
    public function csv(array $data)
    {
        $task_data = $data['data']['data'];
        try {
            app()->make(ExportCsvServices::class)->{$task_data['action']}($task_data['param']);
        } catch (\Exception $e) {
            test_log('export error:' . $e->getMessage());
        }
    }

    //场所任务
    public function place($data)
    {
        $task_data = $data['data']['data'];
        //test_log('开始数管中心任务'.$task_data['action'].'->'.json_encode($task_data['param']));
        try {
            $res = app()->make(PlaceServices::class)->{$task_data['action']}($task_data['param']);
        } catch (\Exception $e) {
            //test_log($e->getMessage());
        }
    }

    //企业码任务
    public function company($data)
    {
        $task_data = $data['data']['data'];
        //test_log('开始数管中心任务'.$task_data['action'].'->'.json_encode($task_data['param']));
        try {
            app()->make(CompanyTaskServices::class)->{$task_data['action']}($task_data['param']);
        } catch (\Exception $e) {
            //test_log($e->getMessage());
        }
    }

    //学校码
    public function school($data)
    {
        $task_data = $data['data']['data'];
        //test_log('开始数管中心任务'.$task_data['action'].'->'.json_encode($task_data['param']));
        try {
            app()->make(SchoolTaskServices::class)->{$task_data['action']}($task_data['param']);
        } catch (\Exception $e) {
            //test_log($e->getMessage());
        }
    }


    //个人码任务
    public function personalcode($data)
    {
        $task_data = $data['data']['data'];
        try {
            app()->make(PersonalCodeTaskServices::class)->{$task_data['action']}($task_data['param']);
        } catch (\Exception $e) {
            //test_log($e->getMessage());
        }
    }

    //AI核酸任务
    public function ainat($data)
    {
        $task_data = $data['data']['data'];
        try {
            app()->make(AinatTaskServices::class)->{$task_data['action']}($task_data['param']);
        } catch (\Exception $e) {
            //test_log($e->getMessage());
        }
    }
}
