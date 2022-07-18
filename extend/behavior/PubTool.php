<?php

namespace behavior;
use think\facade\Db;
use think\facade\Cache;
use Curl\Curl;
use think\facade\Config;
//
class PubTool
{
    public static function publish($event,$param=[])
    {

        test_log('PubTool publish:'.$event);
        test_log($param);

        if(Config::get('app.app_host') == 'dev') { 
            $ip_arr = ['localhost'];
        }else{
            $ip_arr = [
                '172.45.253.96','172.45.253.97','172.45.253.95',
                // '172.45.4.112','172.45.4.101','172.45.4.118'
            ];
        }

        foreach($ip_arr as $key => $ip){
            $curl = new Curl();
            $url = "http://".$ip.":30399/subEvent";
            $param['event'] = $event;
            $curl->post($url, $param); // 这个都是get
            $res = $curl->response;
            $result = json_decode($res, true);
            $curl->close();
        }
    }



}
