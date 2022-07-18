<?php

namespace app\controller;
use think\facade\Db;
use think\facade\Config;
// 尽量不要在此文件中添加其他
class CheckController
{

    public function serverIsOk112(){
        return $this->serverIsOk();
    }

    public function serverIsOk114(){
        return $this->serverIsOk();
    }

    public function serverIsOk118(){
        return $this->serverIsOk();
    }

    public function serverIsOk95(){
        return $this->serverIsOk();
    }

    public function serverIsOk96(){
        return $this->serverIsOk();
    }

    public function serverIsOk97(){
        return $this->serverIsOk();
    }
    
    // 检测环境是否正常
    public function serverIsOk()
    {
        \think\facade\Log::error('serverIsOk');
        test_log('serverIsOk');
        try{
            Db::name('wxtoken')->where('type','=','applet')->find();
            return json(['code'=>200,'msg'=>'成功','data'=>['app_host'=>Config::get('app.app_host')]]);
        }catch(\Exception $e) {
            test_log('serverIsOk失败'.$e->getMessage());
            return json('失败')->code(400);
        }
    }
}