<?php

namespace behavior;
use think\facade\Db;
use think\facade\Cache;

// 验证错误记录
class ErrorTool
{
    public function saveLog($function_name,$name,$content)
    {
        $ymdh = Date('ymdH');
        $hasCache = Cache::get('ErrorTool::saveLog_'.$name.$ymdh);
        if(!$hasCache){
            $has = Db::name('system_day_error')->where(['function_name'=>$function_name,'is_send_msg'=>0,'ymdh'=>$ymdh])->find();
            if(!$has){
                
                $save['function_name'] = $function_name;
                $save['name'] = $name;
                $save['content'] = $content;
                $save['ymdh'] = $ymdh;
                $save['is_send_msg'] = 0;
                $save['create_time'] = time();
                Db::name('system_day_error')->save($save);

                Cache::set('ErrorTool::saveLog_'.$name.$ymdh,1);
            }
        }
    }



}
