<?php

namespace crmeb\services;

use Swoole\Server;
use think\facade\Log;

/**
 * 异步任务
 * Class SwooleTaskService
 * @package crmeb\services
 */
class SwooleTaskService
{

    /**
     * @var Server
     */
    protected $server;

    /**
     * 任务类型
     * @var string
     */
    protected $taskType = 'message';

    /**
     * 送达人
     * @var array
     */
    protected $to;

    /**
     * 任务内容
     * @var array
     */
    protected $data = ['type' => null, 'data' => []];

    /**
     * 排除发送人
     * @var array
     */
    protected $except;

    /**
     * 任务区分类型
     * @var string
     */
    protected $type;

    /**
     * @var static
     */
    protected static $instance;

    /**
     * SwooleTaskService constructor.
     * @param string $taskType
     */
    public function __construct(string $taskType = null)
    {
        if ($taskType) {
            $this->taskType = $taskType;
        }
        $this->server = app('swoole.server');
    }

    /**
     * 任务类型
     * @param string $taskType
     * @return $this
     */
    public function taskType(string $taskType)
    {
        $this->taskType = $taskType;
        return $this;
    }

    /**
     * 消息类型
     * @param string $type
     * @return $this
     */
    public function type(string $type)
    {
        $this->data['type'] = $type;
        return $this;
    }

    /**
     * 设置送达人
     * @param $to
     * @return $this
     */
    public function to($to)
    {
        $this->to = is_array($to) ? $to : func_get_args();
        return $this;
    }

    /**
     * 设置除那个用户不发送
     * @param $except
     * @return $this
     */
    public function except($except)
    {
        $this->except = is_array($except) ? $except : [$except];
        return $this;
    }

    /**
     * 设置参数
     * @param $data
     * @return $this
     */
    public function data($data)
    {
        $this->data['data'] = is_array($data) ? $data : func_get_args();
        return $this;
    }

    /**
     * 执行任务
     */
    public function push()
    {
        try {
            $this->server->task([
                'type' => $this->taskType,
                'data' => [
                    'except' => $this->except,
                    'data' => $this->data,
                    'uid' => $this->to,
                    'type' => $this->type,
                ]
            ]);
            $this->reset();
        } catch (\Throwable $e) {
            Log::error('任务执行失败,失败原因:' . $e->getMessage());
        }
    }

    /**
     * 实例化
     * @return static
     */
    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    /**
     * 客服任务
     * @return SwooleTaskService
     */
    public static function kefu()
    {
        self::instance()->type = __FUNCTION__;
        return self::instance();
    }

    /**
     * 后台任务
     * @return SwooleTaskService
     */
    public static function admin()
    {
        self::instance()->type = __FUNCTION__;
        return self::instance();
    }

    /**
     * 用户任务
     * @return SwooleTaskService
     */
    public static function user()
    {
        self::instance()->type = __FUNCTION__;
        return self::instance();
    }
    /**
     * 消息任务
     * @return SwooleTaskService
     */
    public static function msg()
    {
        self::instance()->type = __FUNCTION__;
        return self::instance();
    }
    /**
     * 阿里云任务
     * @return SwooleTaskService
     */
    public static function aliyun()
    {
        self::instance()->type = __FUNCTION__;
        return self::instance();
    }
    /**
     * 场所码任务
     * @return SwooleTaskService
     */
    public static function place()
    {
        self::instance()->type = __FUNCTION__;
        return self::instance();
    }
    /**
     * 企业码任务
     * @return SwooleTaskService
     */
    public static function company()
    {
        self::instance()->type = __FUNCTION__;
        return self::instance();
    }

    /**
     * 校园码任务
     * @return SwooleTaskService
     */
    public static function school()
    {
        self::instance()->type = __FUNCTION__;
        return self::instance();
    }

    /**
     * 个人码任务
     * @return SwooleTaskService
     */
    public static function personalcode()
    {
        self::instance()->type = __FUNCTION__;
        return self::instance();
    }
    /**
     * 申报任务
     * @return SwooleTaskService
     */
    public static function declare()
    {
        self::instance()->type = __FUNCTION__;
        return self::instance();
    }
    /**
     * 卡车申报任务
     * @return SwooleTaskService
     */
    public static function carDeclare()
    {
        self::instance()->type = __FUNCTION__;
        return self::instance();
    }
    /**
     * 导出任务
     * @return SwooleTaskService
     */
    public static function export()
    {
        self::instance()->type = __FUNCTION__;
        return self::instance();
    }
    /**
     * 导出任务
     * @return SwooleTaskService
     */
    public static function csv()
    {
        self::instance()->type = __FUNCTION__;
        return self::instance();
    }
    /**
     * 数管中心任务
     * @return SwooleTaskService
     */
    public static function sgzx()
    {
        self::instance()->type = __FUNCTION__;
        return self::instance();
    }
    
    /**
     * AI核酸任务
     * @return SwooleTaskService
     */
    public static function ainat()
    {
        self::instance()->type = __FUNCTION__;
        return self::instance();
    }
    /**
     * 重置数据
     * @return $this
     */
    protected function reset()
    {
        $this->taskType = 'message';
        $this->except = null;
        $this->data = ['type' => null, 'data' => []];
        $this->to = null;
        $this->type = null;
        return $this;
    }
}
