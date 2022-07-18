<?php

namespace app\services;

use app\model\AinatCompare;
use app\dao\AinatCompareDao;
use Curl\Curl;
use think\facade\Config;
use think\facade\Db;
use behavior\SsjptActualTool;
use app\services\ainat\ExportServices;

//Ai核酸异步任务服务
class AinatTaskServices
{
    //AI核酸导出任务
    public function ainatCompareListExportTask($param)
    {
        app()->make(ExportServices::class)->ainatCompareListExport($param);
        return true;
    }

    //AI核酸比对
    public function syncAinatCompare($param)
    {
        try {
            //test_log('syncAinatCompare'. $param['page'] .'-'. $param['size']);
            $ainatCompareDao = app()->make(AinatCompareDao::class);
            $ssjptTool = new SsjptActualTool();
            $list = $ainatCompareDao->getComparePartList($param);
            if(count($list) == 0) {
                $loop = false;
            }else{
                $data = [];
                foreach($list as $k => $v) {
                    //省库回流采样时间
                    $skhl_time = '';
                    $collect_time1 = Db::connect('mysql_shengku')->table('dsc_jh_dm_037_pt_patient_sampinfo_delta')->where('id_card','=',$v['user_idcard'])->order('sampling_time','desc')->value('sampling_time');
                    $collect_time2 = Db::connect('mysql_shengku')->table('frryk_sgxg_labreport')->where('KH','=', $v['user_idcard'])->order('COLLECTION_DATE','desc')->value('COLLECTION_DATE');
                    if($collect_time1 && $collect_time2) {
                        if(strtotime($collect_time1) > strtotime($collect_time2)) {
                            $skhl_time = $collect_time1;
                        }else{
                            $skhl_time = $collect_time2;
                        }
                    }else if($collect_time1) {
                        $skhl_time = $collect_time1;
                    }else if($collect_time2) {
                        $skhl_time = $collect_time2;
                    }
                    $sk_get = 1;
                    if($skhl_time != '') {
                        $collect_time = date('Y-m-d H:i:s', strtotime($skhl_time));
                        $diff_hours = bcdiv(time() - strtotime($skhl_time), 3600);
                        $unexpired_hours = $diff_hours > $v['natest_hours'] ? '' : bcsub($v['natest_hours'], $diff_hours);
                        if($unexpired_hours != '') { //未过期
                            if($v['natest_hours'] == 24) { //24小时期限的，间距超3小时算有效，在3小时内则需再次查询省库实时接口
                                if($unexpired_hours > 3) {
                                    $sk_get = 0;
                                }
                            }else{ //48、72小时期限的，间距超6小时算有效，在6小时内则需再次查询省库实时接口
                                if($unexpired_hours > 6) {
                                    $sk_get = 0;
                                }
                            }
                        }
                    }
                    if($sk_get == 1) { //查询省库实时采样接口
                        $res = $ssjptTool->skGetHsjcCollectTime($v['user_idcard'], 0);
                        if($res['status'] == 0) {
                            $ainatCompareDao->update($v['id'], ['result'=> 2, 'result_text'=> '查询失败，'. $res['msg']]);
                            continue;
                        }else{
                            if(isset($res['data'][0])) {
                                $time = strtotime($res['data'][0]['collectTime']);
                                $collect_time = date('Y-m-d H:i:s', $time);
                            }else{
                                $ainatCompareDao->update($v['id'], ['result'=> 2, 'result_text'=> '查询成功，数据为空']);
                                continue;
                            }
                            $diff_hours = bcdiv(time() - $time, 3600);
                            $unexpired_hours = $diff_hours > $v['natest_hours'] ? '' : bcsub($v['natest_hours'], $diff_hours);
                        }
                    }
                    $data[] = [
                        'id'=> $v['id'],
                        'result'=> 1,
                        'result_text'=> '成功',
                        'collect_time' => $collect_time,
                        'unexpired_hours' => $unexpired_hours,
                        'is_overdue' => $diff_hours > $v['natest_hours'] ? '是' : '否',
                        'hsjc_explain' => $sk_get == 1 ? '省库实时采样' : '省库回流',
                    ];
                    //$ainatCompareDao->update($v['id'], $data);
                }
                app()->make(AinatCompare::class)->saveAll($data);
            }
        }catch (\Throwable $e){
            test_log('syncAinatCompare error-'. $e->getMessage());
        }
        return true;
    }
    
}
