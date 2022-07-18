<?php

namespace crmeb\services\template;

use crmeb\basic\BaseManager;
use think\facade\Config;

/**
 * Class Template
 * @package crmeb\services\template
 * @mixin \crmeb\services\template\storage\Wechat
 * @mixin \crmeb\services\template\storage\Subscribe
 */
class Template extends BaseManager
{

    /**
     * 空间名
     * @var string
     */
    protected $namespace = '\\crmeb\\services\\template\\storage\\';

    /**
     * 设置默认
     * @return mixed
     */
    protected function getDefaultDriver()
    {
        return Config::get('template.default', 'wechat');
    }
}
