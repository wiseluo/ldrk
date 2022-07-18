<?php

namespace app\controller;

use think\facade\Db;

// 对接冷链物防
class LlwfController
{
    // 通过身份证号，获取核酸检测结果
    public function getUserHsjcTime(){
        $param = request()->param();
        $id_card = $param['id_card'];
        $data =  Db::name('user')->field('id_card,hsjc_time,hsjc_result,hsjc_jcjg')->where('id_card','=',$id_card)->find();
        if($data){
            return show(200,'ok',$data);
        }
        return show(200,'未查询到',[]);
    }
}