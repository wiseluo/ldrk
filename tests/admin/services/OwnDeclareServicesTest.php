<?php
declare (strict_types=1);
namespace tests\admin\services;
use tests\library\TestCase;
use app\services\admin\OwnDeclareServices;

final class OwnDeclareServicesTest extends TestCase
{
    // 测试
    public function testTodayBackouttimeGroupByStreet(){
        // $this->
        /** @var OwnDeclareServices $server */
        $server = app()->make(OwnDeclareServices::class);
        $data = $server->archivePre3Day();
        $true = true;
        $this->assertEquals(true, $true);
    }



    
}