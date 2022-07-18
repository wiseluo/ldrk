<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2015 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: yunwuxin <448901948@qq.com>
// +----------------------------------------------------------------------
namespace tests\library;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use think\App;

abstract class TestCase extends PHPUnitTestCase
{
    public function __construct(?string $name = null, array $data = [], string $dataName = '') {
        if(!defined('IS_TESTING')){
            define('IS_TESTING',1); // 定义测试模式
        }
        require __DIR__ . '/../../vendor/autoload.php';
        // 执行HTTP应用并响应
        $http = (new App())->http;
        $response = $http->run();
        // $response->send();
        // $http->end($response);
        parent::__construct($name, $data, $dataName);
    }
}
