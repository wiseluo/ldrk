<?php
declare (strict_types=1);

namespace tests\user;
use tests\library\TestCase;
use app\services\user\AliyunServices;

final class MatchOcrServicesTest extends TestCase
{
    // 测试
    public function test(){
        $travel_content = '19：405G<通信行程卡通信大数据行程卡疫情防控，人人有责请收下绿色行程卡176****2114的动态行程卡更新于：2021.11.2819：40：13个您于前14天内到达或途经：：厂广东省广州市结果包含您在前14天内到访的国家(地区))与停留4小时以上的国内城市色卡仅对到访地作提醒，不关联健康状况本服务联合提供CA ICT中国信通院中国电信中国移动中国联通CHINA TELECOMChina Mobilechina unicom客服热线：10000/10086/10010一证通查来了!全国移动电话卡"一证通查"立即点击进入防范诈骗，保护你我';
        $route = app()->make(AliyunServices::class)->contentToRoute($travel_content);
        var_dump($route);
        $true = true;
        $this->assertEquals(true, $true);
    }



    
}