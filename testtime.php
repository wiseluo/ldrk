<?php
$jkm_info= [
    'jkm_time' => '2021-12-15 10:31:28',
    'jkm_mzt' => '绿码',
    'jkm_date' => '2021-12-15',
];

$hsjc_info= [
    'hsjc_result' => '阴性',
    'hsjc_time' => '2021-12-15 14:45:25',
    'hsjc_date' => '2021-12-15',
];

$ywhsjc_info= [
    'hsjc_result' => '义乌查询失败',
    'hsjc_time' => null,
    'hsjc_date' => null,
];

$return_data = [];
$return_data['jkm_info'] = $jkm_info;
$return_data['hsjc_info'] = $hsjc_info;
$return_data['ywhsjc_info'] = $ywhsjc_info;
// 同时将信息 进行 合并加工
$hsjc_time = null;
if($hsjc_info['hsjc_time'] != null){
    $hsjc_time = $hsjc_info['hsjc_time'];
}
if($hsjc_info['hsjc_time'] != null){
    if($hsjc_time == null){
        $hsjc_time = $ywhsjc_info['hsjc_time'];
    }else{
        // 查看哪个时间是最近的
        if(  strtotime($hsjc_info['hsjc_time']) > strtotime($ywhsjc_info['hsjc_time']) ){
            $hsjc_time = $hsjc_info['hsjc_time'];
        }else{
            $hsjc_time = $ywhsjc_info['hsjc_time'];
        }
    }
    var_dump($hsjc_time);
    var_dump(strtotime($hsjc_time));
    var_dump(time() - 24*3600);

    if( strtotime($hsjc_time) > (time() - 24*3600) ){
        $return_data['hsjc_24_info'] = '有';
    }else{
        $return_data['hsjc_24_info'] = '无';
    }
}else{
    var_dump('');
    $return_data['hsjc_24_info'] = '无';
}
// $return_data['real_name'] = $param['real_name'];
// $return_data['phone'] = $param['phone'];
// $return_data['id_card'] = $param['id_card'];
$return_data['jkm'] = $return_data['jkm_info']['jkm_mzt'];
$return_data['last_datetime'] = Date('m月d日 H:i');


var_dump($return_data);