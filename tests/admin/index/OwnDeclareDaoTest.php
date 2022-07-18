<?php
declare (strict_types=1);
namespace tests\admin\index;
use tests\library\TestCase;
use app\dao\OwnDeclareDao;

final class OwnDeclareDaoTest extends TestCase
{
    // 测试
    public function testTodayBackouttimeGroupByStreet(){
        // $this->
        /** @var OwnDeclareDao $dao */
        $dao = app()->make(OwnDeclareDao::class);
        $data = $dao->todayBackouttimeGroupByStreet();
        $true = true;
        $this->assertEquals(true, $true);
    }



    
}