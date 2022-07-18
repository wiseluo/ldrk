<?php

namespace app\controller;
use think\facade\Cache;

class SubController
{
    public function subEvent(){
        $param = request()->param();

        $event = $param['event'];

        switch($event){
            case 'clearAllCache':
                Cache::clear();
                test_log('subEvent clearAllCache ');
                break;
            case 'clearCacheByName':
                $name = $param['name'];
                Cache::delete($name);
                test_log('subEvent clearCacheByName name：'.$name);
                break;
            case 'clearPlaceCache':
                $place_code_arr = $param['place_code_arr'];
                foreach($place_code_arr as $key => $code){
                    Cache::delete('place_code_'.$code);
                    test_log('subEvent clearPlaceCache place_code:'.$code);
                }
                break;
        }


    }
}