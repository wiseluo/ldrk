<?php
declare (strict_types=1);
namespace tests\applet\controller;
use tests\library\TestCase;
use app\controller\applet\Common;

final class CommonTest extends TestCase
{
    // 测试
    public function testRiskDistrictList(){
        $param = [];
        $request = app()->make(\think\Request::class);
        $request->setRoute($param);
        $CommonController =  app()->make(Common::class);
		// 请求接口的控制器方法
        $response = $CommonController->riskDistrictList();
		// 获取接口返回得body内容
        $content = $response->getContent();
        $res = json_decode($content,true);
        var_dump($res);
        return $res;


    }



    
}