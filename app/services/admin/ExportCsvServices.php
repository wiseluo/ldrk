<?php

namespace app\services\admin;

use app\services\admin\BaseServices;
use app\dao\UserDao;
use app\dao\UserSubDao;
use app\dao\slave\OwnDeclareSlaveDao;
use app\dao\OwnDeclareOcrDao;
use app\dao\slave\PlaceSlaveDao;
use app\dao\slave\PlaceDeclareSlaveDao;
use app\dao\GateDao;
use app\dao\GateFactoryDao;
use app\dao\GateDeclareDao;
use app\dao\PersonalCodeDao;
use app\services\admin\CompanyServices;
use app\services\admin\QueryCenterServices;
use \behavior\ExportTool;
use think\facade\Db;

class ExportCsvServices extends BaseServices
{
    /**
     * 不分页拆分处理最大条数
     * @var int
     */
    public $maxLimit = 1000;
    /**
     * 分页导出每页条数
     * @var int
     */
    public $limit = 1000;

    /**
     * 真实请求导出
     * @param $header excel表头
     * @param $title 标题
     * @param array $data 填充数据
     * @param string $filename 保存文件名称
     * @return mixed
     */
    public function export(array $header, array $title_arr, array $data = [], string $filename = '')
    {
        $export = new ExportTool();
        $export->exportToCsv($header, $data, $filename, $title_arr);
    }

    public function leaveOwndeclareListExport($taskparam)
    {
        $filepath = trim($taskparam['filepath'],'/');
        $param = $taskparam['request'];

        $whiledo = true;
        $pre_id = 0; // 前一个id
        $done_num = 0;
        $param['size'] = 100; 
        $header = ['序号', '姓名', '证件类型', '证件号', '手机号', '目的地', '离义时间', '预计返义时间', '申报时间','是否学生'];

        try{
            while($whiledo) {
                $param['_where_id_lg'] = $pre_id;
                $dataList = app()->make(OwnDeclareSlaveDao::class)->getList($param);
                $data = $dataList['data'];
                if($done_num == 0){
                    $all_num = $dataList['total'];
                }
                $export = [];
                if(count($data) == 0) {
                    $whiledo = false;
                }else{
                    foreach ($data as $key => $v) {
                        $one_data = [
                            'num' => $done_num+$key+1,
                            'real_name' => $v['real_name'],
                            'card_type_text' => $v['card_type_text'],
                            'id_card' => $v['id_card'],
                            'phone' => $v['phone'],
                            'destination' => $v['destination'],
                            'leave_time' => $v['leave_time'],
                            'expect_return_time' => $v['expect_return_time'],
                            'create_time' => $v['create_time'],
                            'is_student' => $v['is_student'] ? '是' : '否'
                        ];
                        $export[] = array_values($one_data);
                        $pre_id = $v['id'];
                    }
                }
                $this->_write_csv($filepath,$header,$export,$done_num,$all_num);
            }
        }catch (\Exception $e){
            test_log('leaveOwndeclareListExport error:'.$e->getMessage());
        }
    }

    public function comeOwndeclareListExport($taskparam)
    {
        $filepath = trim($taskparam['filepath'],'/');
        $param = $taskparam['request'];

        $whiledo = true;
        $pre_id = 0; // 前一个id
        $done_num = 0;
        $param['size'] = 100; 
        $header = ['序号', '姓名', '证件类型', '证件号', '手机号', '来源地', '途径城市', '来义居所', '来义时间','是否学生'];

        try{
            while($whiledo) {
                $param['_where_id_lg'] = $pre_id;
                $dataList = app()->make(OwnDeclareSlaveDao::class)->getList($param);
                $data = $dataList['data'];
                if($done_num == 0){
                    $all_num = $dataList['total'];
                }
                $export = [];
                if(count($data) == 0) {
                    $whiledo = false;
                }else{
                    foreach ($data as $key => $v) {
                        $one_data = [
                            'num' => $done_num+$key+1,
                            'real_name' => $v['real_name'],
                            'card_type_text' => $v['card_type_text'],
                            'id_card' => $v['id_card'],
                            'phone' => $v['phone'],
                            'destination' => $v['destination'],
                            'travel_route' => $v['travel_route'],
                            'yw_street' => $v['yw_street'],
                            'arrival_time' => $v['arrival_time'],
                            'is_student' => $v['is_student'] ? '是' : '否'
                        ];
                        $export[] = array_values($one_data);
                        $pre_id = $v['id'];
                    }
                }
                $this->_write_csv($filepath,$header,$export,$done_num,$all_num);
            }
        }catch (\Exception $e){
            test_log('comeOwndeclareListExport error:'.$e->getMessage());
        }
    }

    public function riskareaOwndeclareListExport($taskparam)
    {
        $filepath = trim($taskparam['filepath'],'/');
        $param = $taskparam['request'];

        $whiledo = true;
        $pre_id = 0; // 前一个id
        $done_num = 0;
        $param['size'] = 100;
        $header = ['序号', '姓名', '证件类型', '证件号', '手机号', '来源地', '途径城市', '来义居所', '来义时间','是否学生'];

        try{
            while($whiledo) {
                $param['_where_id_lg'] = $pre_id;
                $dataList = app()->make(OwnDeclareSlaveDao::class)->getList($param);
                $data = $dataList['data'];
                if($done_num == 0){
                    $all_num = $dataList['total'];
                }
                $export = [];
                if(count($data) == 0) {
                    $whiledo = false;
                }else{
                    foreach ($data as $key => $v) {
                        $one_data = [
                            'num' => $done_num+$key+1,
                            'real_name' => $v['real_name'],
                            'card_type_text' => $v['card_type_text'],
                            'id_card' => $v['id_card'],
                            'phone' => $v['phone'],
                            'destination' => $v['destination'],
                            'travel_route' => $v['travel_route'],
                            'yw_street' => $v['yw_street'],
                            'arrival_time' => $v['arrival_time'],
                            'is_student' => $v['is_student'] ? '是' : '否'
                        ];
                        $export[] = array_values($one_data);
                        $pre_id = $v['id'];
                    }
                }
                $this->_write_csv($filepath,$header,$export,$done_num,$all_num);
            }
        }catch (\Exception $e){
            test_log('riskareaOwndeclareListExport error:'.$e->getMessage());
        }
    }

    public function barrierOwndeclareListExport($taskparam)
    {
        $filepath = trim($taskparam['filepath'],'/');
        $param = $taskparam['request'];

        $whiledo = true;
        $pre_id = 0; // 前一个id
        $done_num = 0;
        $param['size'] = 100;
        $header = ['序号', '姓名', '证件类型', '证件号', '手机号', '来源地', '来义居所', '行程码是否有星号', '申报时间', '健康码', '疫苗接种剂次',
            '疫苗接种日期', '最近核酸检测结果', '检测时间', '管控措施','是否学生'];

        try{
            while($whiledo) {
                $param['_where_id_lg'] = $pre_id;
                $dataList = app()->make(OwnDeclareSlaveDao::class)->getList($param);
                $data = $dataList['data'];
                if($done_num == 0){
                    $all_num = $dataList['total'];
                }
                $export = [];
                if(count($data) == 0) {
                    $whiledo = false;
                }else{
                    foreach ($data as $key => $v) {
                        $one_data = [
                            'num' => $done_num+$key+1,
                            'real_name' => $v['real_name'],
                            'card_type_text' => $v['card_type_text'],
                            'id_card' => $v['id_card'],
                            'phone' => $v['phone'],
                            'destination' => $v['destination'],
                            'yw_street' => $v['yw_street'],
                            'isasterisk' => $v['isasterisk'] == 0 ? '否' : '是',
                            'create_time' => $v['create_time'],
                            'jkm_mzt' => $v['jkm_mzt'],
                            'vaccination_times' => $v['vaccination_times'],
                            'vaccination_date' => $v['vaccination_date'],
                            'hsjc_result' => $v['hsjc_result'],
                            'hsjc_time' => $v['hsjc_time'],
                            'control_state' => $v['control_state'],
                            'is_student' => $v['is_student'] ? '是' : '否'
                        ];
                        $export[] = array_values($one_data);
                        $pre_id = $v['id'];
                    }
                }
                $this->_write_csv($filepath,$header,$export,$done_num,$all_num);
            }
        }catch (\Exception $e){
            test_log('barrierOwndeclareListExport error:'.$e->getMessage());
        }
    }

    public function quarantineOwndeclareListExport($taskparam)
    {
        $filepath = trim($taskparam['filepath'],'/');
        $param = $taskparam['request'];

        $whiledo = true;
        $pre_id = 0; // 前一个id
        $done_num = 0;
        $param['size'] = 100;
        $header = ['序号', '姓名', '证件类型', '证件号', '手机号','是否学生'];

        try{
            while($whiledo) {
                $param['_where_id_lg'] = $pre_id;
                $dataList = app()->make(OwnDeclareSlaveDao::class)->getList($param);
                $data = $dataList['data'];
                if($done_num == 0){
                    $all_num = $dataList['total'];
                }
                $export = [];
                if(count($data) == 0) {
                    $whiledo = false;
                }else{
                    foreach ($data as $key => $v) {
                        $one_data = [
                            'num' => $done_num+$key+1,
                            'real_name' => $v['real_name'],
                            'card_type_text' => $v['card_type_text'],
                            'id_card' => $v['id_card'],
                            'phone' => $v['phone'],
                            'is_student' => $v['is_student'] ? '是' : '否'
                        ];
                        $export[] = array_values($one_data);
                        $pre_id = $v['id'];
                    }
                }
                $this->_write_csv($filepath,$header,$export,$done_num,$all_num);
            }
        }catch (\Exception $e){
            test_log('quarantineOwndeclareListExport error:'.$e->getMessage());
        }
    }
    
    public function owndeclareRecentComeExport($taskparam)
    {
        $filepath = trim($taskparam['filepath'],'/');
        $param = $taskparam['request'];

        $whiledo = true;
        $pre_id = 0; // 前一个id
        $done_num = 0;
        $param['size'] = 100;
        $header = ['序号', '姓名', '身份证号', '手机号', '来源地', '所在镇街', '抵义时间'];

        try{
            while($whiledo) {
                $param['_where_id_lg'] = $pre_id;
                $dataList = app()->make(OwnDeclareSlaveDao::class)->getRecentComeList($param);
                $data = $dataList['data'];
                if($done_num == 0){
                    $all_num = $dataList['total'];
                }
                $export = [];
                if(count($data) == 0) {
                    $whiledo = false;
                }else{
                    foreach ($data as $key => $v) {
                        $one_data = [
                            'num' => $done_num+$key+1,
                            'real_name' => $v['real_name'],
                            'id_card' => $v['id_card'],
                            'phone' => $v['phone'],
                            'destination' => $v['destination'],
                            'yw_street' => $v['yw_street'],
                            'arrival_time' => $v['arrival_time'],
                        ];
                        $export[] = array_values($one_data);
                        $pre_id = $v['id'];
                    }
                }
                $this->_write_csv($filepath,$header,$export,$done_num,$all_num);
            }
        }catch (\Exception $e){
            test_log('owndeclareRecentComeExport error:'.$e->getMessage());
        }
    }
    
    public function dataerrorLeaveListExport($taskparam)
    {
        $filepath = trim($taskparam['filepath'],'/');
        $param = $taskparam['request'];

        $whiledo = true;
        $pre_id = 0; // 前一个id
        $done_num = 0;
        $param['size'] = 100;
        $header = ['序号', '异常原因', '姓名', '证件类型', '证件号', '手机号', '目的地', '离义时间', '申报时间'];

        try{
            while($whiledo) {
                $param['_where_id_lg'] = $pre_id;
                $dataList = app()->make(OwnDeclareSlaveDao::class)->getDataError($param);
                $data = $dataList['data'];
                if($done_num == 0){
                    $all_num = $dataList['total'];
                }
                $export = [];
                if(count($data) == 0) {
                    $whiledo = false;
                }else{
                    foreach ($data as $key => $v) {
                        $one_data = [
                            'num' => $done_num+$key+1,
                            'error_infos' => $v['error_infos'],
                            'real_name' => $v['real_name'],
                            'card_type_text' => $v['card_type_text'],
                            'id_card' => $v['id_card'],
                            'phone' => $v['phone'],
                            'destination' => $v['destination'],
                            'leave_time' => $v['leave_time'],
                            'create_time' => $v['create_time'],
                        ];
                        $export[] = array_values($one_data);
                        $pre_id = $v['id'];
                    }
                }
                $this->_write_csv($filepath,$header,$export,$done_num,$all_num);
            }
        }catch (\Exception $e){
            test_log('dataerrorLeaveListExport error:'.$e->getMessage());
        }
    }
    
    public function dataerrorComeListExport($taskparam)
    {
        $filepath = trim($taskparam['filepath'],'/');
        $param = $taskparam['request'];

        $whiledo = true;
        $pre_id = 0; // 前一个id
        $done_num = 0;
        $param['size'] = 100;
        $header = ['序号', '异常原因', '姓名', '证件类型', '证件号', '手机号', '来源地', '途径城市', '来义居所'];

        try{
            while($whiledo) {
                $param['_where_id_lg'] = $pre_id;
                $dataList = app()->make(OwnDeclareSlaveDao::class)->getDataError($param);
                $data = $dataList['data'];
                if($done_num == 0){
                    $all_num = $dataList['total'];
                }
                $export = [];
                if(count($data) == 0) {
                    $whiledo = false;
                }else{
                    foreach ($data as $key => $v) {
                        $one_data = [
                            'num' => $done_num+$key+1,
                            'error_infos' => $v['error_infos'],
                            'real_name' => $v['real_name'],
                            'card_type_text' => $v['card_type_text'],
                            'id_card' => $v['id_card'],
                            'phone' => $v['phone'],
                            'destination' => $v['destination'],
                            'travel_route' => $v['travel_route'],
                            'yw_street' => $v['yw_street'],
                        ];
                        $export[] = array_values($one_data);
                        $pre_id = $v['id'];
                    }
                }
                $this->_write_csv($filepath,$header,$export,$done_num,$all_num);
            }
        }catch (\Exception $e){
            test_log('dataerrorComeListExport error:'.$e->getMessage());
        }
    }
    
    public function dataerrorRiskareaListExport($taskparam)
    {
        $filepath = trim($taskparam['filepath'],'/');
        $param = $taskparam['request'];

        $whiledo = true;
        $pre_id = 0; // 前一个id
        $done_num = 0;
        $param['size'] = 100;
        $header = ['序号', '异常原因', '姓名', '证件类型', '证件号', '手机号', '来源地', '来义居所', '来义时间', '详细地址', '申报时间',
            '离开高风险地区时间', '来义交通工具', '车次/航班/车牌'];

        try{
            while($whiledo) {
                $param['_where_id_lg'] = $pre_id;
                $dataList = app()->make(OwnDeclareSlaveDao::class)->getDataError($param);
                $data = $dataList['data'];
                if($done_num == 0){
                    $all_num = $dataList['total'];
                }
                $export = [];
                if(count($data) == 0) {
                    $whiledo = false;
                }else{
                    foreach ($data as $key => $v) {
                        $one_data = [
                            'num' => $done_num+$key+1,
                            'error_infos' => $v['error_infos'],
                            'real_name' => $v['real_name'],
                            'card_type_text' => $v['card_type_text'],
                            'id_card' => $v['id_card'],
                            'phone' => $v['phone'],
                            'destination' => $v['destination'],
                            'yw_street' => $v['yw_street'],
                            'arrival_time' => $v['arrival_time'],
                            'address' => $v['address'],
                            'create_time' => $v['create_time'],
                            'leave_riskarea_time' => $v['leave_riskarea_time'],
                            'arrival_transport_mode_text' => $v['arrival_transport_mode_text'],
                            'arrival_sign' => $v['arrival_sign'],
                        ];
                        $export[] = array_values($one_data);
                        $pre_id = $v['id'];
                    }
                }
                $this->_write_csv($filepath,$header,$export,$done_num,$all_num);
            }
        }catch (\Exception $e){
            test_log('dataerrorRiskareaListExport error:'.$e->getMessage());
        }
    }
    
    public function dataerrorTodayManyListExport($taskparam)
    {
        $filepath = trim($taskparam['filepath'],'/');
        $param = $taskparam['request'];

        $done_num = 0;
        $header = ['序号', '姓名', '相关ids', '离义申报数量', '来义申报数量', '高风险申报数量'];

        try{
            $dataList = app()->make(OwnDeclareSlaveDao::class)->getTodayMany($param);
            $export = [];
            foreach ($dataList as $key => $v) {
                $one_data = [
                    'num' => $done_num+$key+1,
                    'real_name' => $v['real_name'],
                    'ids' => $v['ids'],
                    'leave_nums' => $v['leave_nums'],
                    'come_nums' => $v['come_nums'],
                    'riskarea_nums' => $v['riskarea_nums'],
                ];
                $export[] = array_values($one_data);
            }
            $this->_write_csv($filepath,$header,$export,$done_num,count($dataList));
        }catch (\Exception $e){
            test_log('dataerrorTodayManyListExport error:'.$e->getMessage());
        }
    }
    
    public function dataerrorOcrListExport($taskparam)
    {
        $filepath = trim($taskparam['filepath'],'/');
        $param = $taskparam['request'];

        $whiledo = true;
        $pre_id = 0; // 前一个id
        $done_num = 0;
        $param['size'] = 100;
        $header = ['序号', '申报id', '行程码原始数据', '行程', '行程最新更新时间', '备注'];

        try{
            while($whiledo) {
                $param['_where_id_lg'] = $pre_id;
                $dataList = app()->make(OwnDeclareOcrDao::class)->getList($param);
                $data = $dataList['data'];
                if($done_num == 0){
                    $all_num = $dataList['total'];
                }
                $export = [];
                if(count($data) == 0) {
                    $whiledo = false;
                }else{
                    foreach ($data as $key => $v) {
                        $one_data = [
                            'num' => $done_num+$key+1,
                            'declare_id' => $v['declare_id'],
                            'travel_content' => $v['travel_content'],
                            'travel_route' => $v['travel_route'],
                            'travel_time' => $v['travel_time'],
                            'remark' => $v['remark'],
                        ];
                        $export[] = array_values($one_data);
                        $pre_id = $v['id'];
                    }
                }
                $this->_write_csv($filepath,$header,$export,$done_num,$all_num);
            }
        }catch (\Exception $e){
            test_log('dataerrorOcrListExport error:'.$e->getMessage());
        }
    }
    
    // public function dataerrorTravelAsteriskListExport($taskparam)
    // {
    //     $filepath = trim($taskparam['filepath'],'/');
    //     $param = $taskparam['request'];

    //     $whiledo = true;
    //     $pre_id = 0; // 前一个id
    //     $done_num = 0;
    //     $param['size'] = 100;
    //     $header = ['序号', '申报id', '行程', '行程最新更新时间', '备注'];

    //     try{
    //         while($whiledo) {
    //             $param['_where_id_lg'] = $pre_id;
    //             $dataList = app()->make(OwnDeclareSlaveDao::class)->getTravelAsterisk($param);
    //             $data = $dataList['data'];
    //             if($done_num == 0){
    //                 $all_num = $dataList['total'];
    //             }
    //             $export = [];
    //             if(count($data) == 0) {
    //                 $whiledo = false;
    //             }else{
    //                 foreach ($data as $key => $v) {
    //                     $one_data = [
    //                         'num' => $done_num+$key+1,
    //                         'declare_id' => $v['declare_id'],
    //                         'travel_route' => $v['travel_route'],
    //                         'travel_time' => $v['travel_time'],
    //                         'remark' => $v['remark'],
    //                     ];
    //                     $export[] = array_values($one_data);
    //                     $pre_id = $v['id'];
    //                 }
    //             }
    //             $this->_write_csv($filepath,$header,$export,$done_num,$all_num);
    //         }
    //     }catch (\Exception $e){
    //         test_log('dataerrorTravelAsteriskListExport error:'.$e->getMessage());
    //     }
    // }
    
    // public function dataerrorJkmmztListExport($param)
    // {
    //     $filename = $param['filename'];
    //     $all_data = app()->make(OwnDeclareOcrServices::class)->getList($param['request']);
    //     $data = $all_data['data'];
        
    //     $header = ['序号', '申报id', '行程', '健康码获取时间', '健康码状态'];
    //     $title = ['异常信息非绿码记录', '异常信息非绿码记录', date('Y-m-d H:i:s', time()) ."\t"];
    //     $export = [];
    //     if (!empty($data)) {
    //         foreach ($data as $key => $value) {
    //             $one_data = [
    //                 'num' => $key+1,
    //                 'declare_id' => $value['declare_id'],
    //                 'travel_route' => $value['travel_route'],
    //                 'jkm_time' => $value['jkm_time'],
    //                 'jkm_mzt' => $value['jkm_mzt'],
    //             ];
    //             $export[] = array_values($one_data);
    //         }
    //     }
    //     $this->export($header, $title, $export, $filename, 'xlsx', true);
    //     return true;
    // }

    public function datawarningBackouttimeListExport($taskparam)
    {
        $filepath = trim($taskparam['filepath'],'/');
        $param = $taskparam['request'];

        $whiledo = true;
        $pre_id = 0; // 前一个id
        $done_num = 0;
        $param['size'] = 100;
        $header = ['序号', '姓名', '证件类型', '证件号', '手机号', '离义时间', '预计返义时间', '是否短信通知', '短信内容', '短信时间'];

        try{
            while($whiledo) {
                $param['_where_id_lg'] = $pre_id;
                $dataList = app()->make(OwnDeclareSlaveDao::class)->getDataWarning($param, $param['warning_type']);
                $data = $dataList['data'];
                if($done_num == 0){
                    $all_num = $dataList['total'];
                }
                $export = [];
                if(count($data) == 0) {
                    $whiledo = false;
                }else{
                    foreach ($data as $key => $v) {
                        $one_data = [
                            'num' => $done_num+$key+1,
                            'real_name' => $v['real_name'],
                            'card_type_text' => $v['card_type_text'],
                            'id_card' => $v['id_card'],
                            'phone' => $v['phone'],
                            'leave_time' => $v['leave_time'],
                            'expect_return_time' => $v['expect_return_time'],
                            'send_sms' => $v['send_sms'] == 0 ? '否' : '是',
                            'sms_content' => $v['sms_content'],
                            'sms_send_time' => $v['sms_send_time'],
                        ];
                        $export[] = array_values($one_data);
                        $pre_id = $v['id'];
                    }
                }
                $this->_write_csv($filepath,$header,$export,$done_num,$all_num);
            }
        }catch (\Exception $e){
            test_log('datawarningBackouttimeListExport error:'.$e->getMessage());
        }
    }
    
    public function datawarningRiskareaListExport($taskparam)
    {
        $filepath = trim($taskparam['filepath'],'/');
        $param = $taskparam['request'];

        $whiledo = true;
        $pre_id = 0; // 前一个id
        $done_num = 0;
        $param['size'] = 100;
        $header = ['序号', '姓名', '证件类型', '证件号', '手机号', '来源地', '来义居所', '来义时间', '详细地址', '离开中高风险地区时间', '来义交通工具',
            '车次/航班/车牌', '核酸检测结果', '疫苗接种时间', '接种剂次', '风险判断规则', '规则说明', '是否已推送', '管控措施'];

        try{
            while($whiledo) {
                $param['_where_id_lg'] = $pre_id;
                $dataList = app()->make(OwnDeclareSlaveDao::class)->getDataWarning($param, $param['warning_type']);
                $data = $dataList['data'];
                if($done_num == 0){
                    $all_num = $dataList['total'];
                }
                $export = [];
                if(count($data) == 0) {
                    $whiledo = false;
                }else{
                    foreach ($data as $key => $v) {
                        $one_data = [
                            'num' => $done_num+$key+1,
                            'real_name' => $v['real_name'],
                            'card_type_text' => $v['card_type_text'],
                            'id_card' => $v['id_card'],
                            'phone' => $v['phone'],
                            'destination' => $v['destination'],
                            'yw_street' => $v['yw_street'],
                            'arrival_time' => $v['arrival_time'],
                            'address' => $v['address'],
                            'leave_riskarea_time' => $v['leave_riskarea_time'],
                            'arrival_transport_mode_text' => $v['arrival_transport_mode_text'],
                            'arrival_sign' => $v['arrival_sign'],
                            'hsjc_result' => $v['hsjc_result'],
                            'vaccination_date' => $v['vaccination_date'],
                            'vaccination_times' => $v['vaccination_times'],
                            'warning_from_text' => $v['warning_from_text'],
                            'warning_rule_text' => $v['warning_rule_text'],
                            'is_to_oa' => $v['is_to_oa'],
                            'control_state' => $v['control_state'],
                        ];
                        $export[] = array_values($one_data);
                        $pre_id = $v['id'];
                    }
                }
                $this->_write_csv($filepath,$header,$export,$done_num,$all_num);
            }
        }catch (\Exception $e){
            test_log('datawarningRiskareaListExport error:'.$e->getMessage());
        }
    }
    
    public function userListExport($taskparam)
    {
        $filepath = trim($taskparam['filepath'],'/');
        $param = $taskparam['request'];

        $whiledo = true;
        $pre_id = 0; // 前一个id
        $done_num = 0;
        $param['size'] = 100;
        $header = ['序号', '姓名', '性别', '民族', '身份证号', '年龄', '户籍地址', '联系电话', '核酸检测结果', '是否疫苗接种', '位置状态', '申报类型',
            '途径城市', '是否中高风险地区申报'];

        try{
            while($whiledo) {
                $param['_where_id_lg'] = $pre_id;
                $dataList = app()->make(UserDao::class)->getUserList($param);
                $data = $dataList['data'];
                if($done_num == 0){
                    $all_num = $dataList['total'];
                }
                $export = [];
                if(count($data) == 0) {
                    $whiledo = false;
                }else{
                    foreach ($data as $key => $v) {
                        $one_data = [
                            'num' => $done_num+$key+1,
                            'real_name' => $v['real_name'],
                            'gender_text' => $v['gender_text'],
                            'nation' => $v['nation'],
                            'id_card' => $v['id_card'],
                            'age' => $v['age'],
                            'permanent_address' => $v['permanent_address'],
                            'phone' => $v['phone'],
                            'hsjc_result' => $v['hsjc_result'],
                            'vaccination' => $v['vaccination'] == 0 ? '否' : '是',
                            'position_type_text' => $v['position_type_text'],
                            'declare_type_text' => $v['declare_type_text'],
                            'travel_route' => $v['travel_route'],
                            'is_riskarea' => $v['declare_type'] == 'riskarea' ? '是' : '否',
                        ];
                        $export[] = array_values($one_data);
                        $pre_id = $v['id'];
                    }
                }
                $this->_write_csv($filepath,$header,$export,$done_num,$all_num);
            }
        }catch (\Exception $e){
            test_log('userListExport error:'.$e->getMessage());
        }
    }
    
    public function subChangeExport($taskparam)
    {
        $filepath = trim($taskparam['filepath'],'/');
        $param = $taskparam['request'];

        $whiledo = true;
        $pre_id = 0; // 前一个id
        $done_num = 0;
        $param['size'] = 100;
        $header = ['序号', '姓名', '身份证号', '电话号码', '来源地', '所在镇街', '抵义时间'];

        try{
            while($whiledo) {
                $param['_where_id_lg'] = $pre_id;
                $dataList = app()->make(UserSubDao::class)->getSubChangeList($param);
                $data = $dataList['data'];
                if($done_num == 0){
                    $all_num = $dataList['total'];
                }
                $export = [];
                if(count($data) == 0) {
                    $whiledo = false;
                }else{
                    foreach ($data as $key => $v) {
                        $one_data = [
                            'num' => $done_num+$key+1,
                            'real_name' => $v['real_name'],
                            'id_card' => $v['id_card'],
                            'phone' => $v['phone'],
                            'province' => $v['province'].$v['city'].$v['county'].$v['street'],
                            'yw_street' => $v['yw_street'],
                            'arrival_time' => $v['arrival_time'],
                        ];
                        $export[] = array_values($one_data);
                        $pre_id = $v['id'];
                    }
                }
                $this->_write_csv($filepath,$header,$export,$done_num,$all_num);
            }
        }catch (\Exception $e){
            test_log('userListExport error:'.$e->getMessage());
        }
    }
    
    public function statisticListData($param)
    {
        $data = app()->make(OwnDeclareServices::class)->getStatistic($param['request'], $param['statistic_type']);
        $this->{$param['export_fun']}($data, $param['filename']);
        return true;
    }

    public function statisticFromwhereListExport($taskparam)
    {
        $filepath = trim($taskparam['filepath'],'/');
        $param = $taskparam['request'];

        $done_num = 0;
        $header = ['序号', '省份', '来返义', '中高风险', '合计'];

        try{
            $dataList = app()->make(OwnDeclareSlaveDao::class)->getStatistic($param, $param['statistic_type']);
            $export = [];
            foreach ($dataList as $key => $v) {
                $one_data = [
                    'num' => $done_num+$key+1,
                    'province' => $v['province'],
                    'come_nums' => $v['come_nums'],
                    'riskarea_nums' => $v['riskarea_nums'],
                    'total_nums' => $v['total_nums'],
                ];
                $export[] = array_values($one_data);
            }
            $this->_write_csv($filepath,$header,$export,$done_num,count($dataList));
        }catch (\Exception $e){
            test_log('statisticFromwhereListExport error:'.$e->getMessage());
        }
    }
    
    public function statisticYwstreetListExport($taskparam)
    {
        $filepath = trim($taskparam['filepath'],'/');
        $param = $taskparam['request'];

        $done_num = 0;
        $header = ['序号', '镇街', '来返义', '中高风险', '合计'];

        try{
            $dataList = app()->make(OwnDeclareSlaveDao::class)->getStatistic($param, $param['statistic_type']);
            $export = [];
            foreach ($dataList as $key => $v) {
                $one_data = [
                    'num' => $done_num+$key+1,
                    'yw_street' => $v['yw_street'],
                    'come_nums' => $v['come_nums'],
                    'riskarea_nums' => $v['riskarea_nums'],
                    'total_nums' => $v['total_nums'],
                ];
                $export[] = array_values($one_data);
            }
            $this->_write_csv($filepath,$header,$export,$done_num,count($dataList));
        }catch (\Exception $e){
            test_log('statisticYwstreetListExport error:'.$e->getMessage());
        }
    }
    
    public function statisticAgeListExport($taskparam)
    {
        $filepath = trim($taskparam['filepath'],'/');
        $param = $taskparam['request'];

        $done_num = 0;
        $header = ['序号', '年龄段', '来返义', '中高风险', '合计'];

        try{
            $dataList = app()->make(OwnDeclareSlaveDao::class)->getStatistic($param, $param['statistic_type']);
            $export = [];
            foreach ($dataList as $key => $v) {
                $one_data = [
                    'num' => $done_num+$key+1,
                    'name' => $v['name'],
                    'come_nums' => $v['come_nums'],
                    'riskarea_nums' => $v['riskarea_nums'],
                    'total_nums' => $v['total_nums'],
                ];
                $export[] = array_values($one_data);
            }
            $this->_write_csv($filepath,$header,$export,$done_num,count($dataList));
        }catch (\Exception $e){
            test_log('statisticAgeListExport error:'.$e->getMessage());
        }
    }
    
    public function unqualifiedCompanyExport($taskparam)
    {
        $filepath  = trim($taskparam['filepath'],'/');
        $param     = $taskparam['request'];
        $adminInfo = isset($taskparam['adminInfo']) ? $taskparam['adminInfo'] : [];

        // 
        $whiledo = true;
        $pre_id = 0; // 前一个id
        $done_num = 0;
        // 
        $param['size'] = 100;

        $header = ['序号','企业名称', '联络人', '联络人手机号','企业类型', '所属街道', '社区',  '员工数', '2天频次人数', '7天频次人数','14天频次人数',
            '28天频次人数','70天频次人数','7天内检测率', '70天内检测率', '2天（48小时）内检测率'];

        try{
            while ($whiledo){
                $param['_where_id_lg'] = $pre_id;
                $dataList = app()->make(CompanyServices::class)->getUnqualifiedListFromSlaveForExport($param,$adminInfo);
                // 
                $data = $dataList['data'];
                if($done_num == 0){
                    $all_num = $dataList['total'];
                }
                $get_num = count($data);
                $export = [];
                if($get_num == 0) {
                    $whiledo = false;
                }else{
                    foreach ($data as $key => $value) {
                        $one_data = [
                            'num' => $done_num+$key+1,
                            'name' => $value['name'],
                            'link_name' => $value['link_name'],
                            'link_phone' => $value['link_phone'],
                            'classify_name' => $value['classify_name'],
                            'yw_street' => $value['yw_street'],
                            'community' => $value['community'],
                            'user_count' => $value['user_count'],
                            'user_2_count' => $value['user_2_count'],
                            'user_7_count' => $value['user_7_count'],
                            'user_14_count' => $value['user_14_count'],
                            'user_28_count' => $value['user_28_count'],
                            'user_70_count' => $value['user_70_count'],
                            'seven_rate' => $value['seven_rate'],
                            'seventy_rate' => $value['seventy_rate'],
                            'two_rate' => $value['two_rate'],
                        ];
                        $export[] = array_values($one_data);
                        $pre_id = $value['id'];
                    }
                }
                $this->_write_csv($filepath,$header,$export,$done_num,$all_num);
            }
        }catch (\Exception $e){
            test_log('unqualifiedCompanyExport error:'.$e->getMessage() );
        }
    }

    // * params $headerList 头部列表信息(一维数组) 必传
    // * params $data 导出的数据(二维数组)  必传
    // * params $filename 文件名称转码 必传
    // * params $tmp 备用信息(二维数组) 选传
    // * PS:出现数字格式化情况，可添加看不见的符号，使其正常，如:"\t"
    public function companyExport($taskparam=[]){

        $filepath  = trim($taskparam['filepath'],'/');
        $param     = $taskparam['request'];
        $adminInfo = isset($taskparam['adminInfo']) ? $taskparam['adminInfo'] : [];

        // 
        $whiledo = true;
        $pre_id = 0; // 前一个id
        $done_num = 0;
        // 
        $param['size'] = 100;

        // $header = ['序号', '企业名称', '联络人', '联络人手机号', '所属街道', '社区', '营业代码', '地址', '员工数', '7天内检测率', '70天内检测率'];
        $header = ['序号','企业名称', '联络人', '联络人手机号','企业类型', '所属街道', '社区',  '员工数', '2天频次人数', '7天频次人数','14天频次人数','28天频次人数','70天频次人数','7天内检测率', '70天内检测率', '2天（48小时）内检测率'];

        try{
            while ($whiledo){
                $param['_where_id_lg'] = $pre_id;
                $dataList = app()->make(CompanyServices::class)->getListFromSlaveForExport($param,$adminInfo);
                // 
                $data = $dataList['data'];
                if($done_num == 0){
                    $all_num = $dataList['total'];
                }
                
                $get_num = count($data);
                $export = [];
                if($get_num == 0) {
                    $whiledo = false;
                }else{
                    foreach ($data as $key => $value) {
                        $one_data = [
                            'num' => $done_num+$key+1,
                            'name' => $value['name'],
                            'link_name' => $value['link_name'],
                            'link_phone' => $value['link_phone'],
                            'classify_name' => $value['classify_name'],
                            'yw_street' => $value['yw_street'],
                            'community' => $value['community'],
                            'user_count' => $value['user_count'],
                            'user_2_count' => $value['user_2_count'],
                            'user_7_count' => $value['user_7_count'],
                            'user_14_count' => $value['user_14_count'],
                            'user_28_count' => $value['user_28_count'],
                            'user_70_count' => $value['user_70_count'],
                            'seven_rate' => $value['seven_rate'],
                            'seventy_rate' => $value['seventy_rate'],
                            'two_rate' => $value['two_rate'],
                        ];
                        $export[] = array_values($one_data);
                        $pre_id = $value['id'];
                    }
                }
                $this->_write_csv($filepath,$header,$export,$done_num,$all_num);
            }
        }catch (\Exception $e){
            test_log('companyExport error:'.$e->getMessage() );
        }
    }

    public function placeExport($taskparam)
    {
        $filepath = trim($taskparam['filepath'],'/');
        $param = $taskparam['request'];

        $whiledo = true;
        $pre_id = 0; // 前一个id
        $done_num = 0;
        $param['size'] = 100;
        $header = ['序号', '镇街', '社区', '单位名称', '简称', '企业信用代码', '场所分类', '行业类型', '上级主管部门', '地址', '联络员', '联系电话', '机关单位'];

        try{
            while($whiledo) {
                $param['_where_id_lg'] = $pre_id;
                $dataList = app()->make(PlaceSlaveDao::class)->getList($param);
                $data = $dataList['data'];
                if($done_num == 0){
                    $all_num = $dataList['total'];
                }
                $export = [];
                if(count($data) == 0) {
                    $whiledo = false;
                }else{
                    foreach ($data as $key => $value) {
                        $one_data = [
                            'num' => $done_num+$key+1,
                            'yw_street' => $value['yw_street'],
                            'community' => $value['community'],
                            'name' => $value['name'],
                            'short_name' => $value['short_name'],
                            'credit_code' => $value['credit_code'],
                            'place_classify_text' => $value['place_classify_text'],
                            'place_type' => $value['place_type'],
                            'superior_gov' => $value['superior_gov'],
                            'addr' => $value['addr'],
                            'link_man' => $value['link_man'],
                            'link_phone' => $value['link_phone'],
                            'gov' => $value['place_classify'] == 'gov' ? '是' : '否',
                        ];
                        $export[] = array_values($one_data);
                        $pre_id = $value['id'];
                    }
                }
                $this->_write_csv($filepath,$header,$export,$done_num,$all_num);
            }
        }catch (\Exception $e){
            test_log('placeExport error:'.$e->getMessage());
        }
    }

    public function placeDeclareExport($taskparam)
    {
        $filepath = trim($taskparam['filepath'],'/');
        $param = $taskparam['request'];

        $whiledo = true;
        $pre_id = 0; // 前一个id
        $done_num = 0;
        $param['size'] = 100;
        $header = ['序号','健康码状态', '管控状态', '行程码结果', '场所全称', '场所简称', '姓名', '身份证号', '手机号', '联络人', '联系电话', '地址',
            '义乌镇街', '核酸结果', '核酸时间', '接种日期', '疫苗接种', '接种剂次', '申报时间'];

        try{
            while($whiledo) {
                $param['_where_id_lg'] = $pre_id;
                $dataList = app()->make(PlaceDeclareSlaveDao::class)->getList($param);
                $data = $dataList['data'];
                if($done_num == 0){
                    $all_num = $dataList['total'];
                }
                $export = [];
                if(count($data) == 0) {
                    $whiledo = false;
                }else{
                    foreach ($data as $key => $value) {
                        $one_data = [
                            'num' => $done_num+$key+1,
                            'jkm_mzt' => $value['jkm_mzt'],
                            'ryxx_result' => $value['ryxx_result'],
                            'xcm_result_text' => $value['xcm_result_text'],
                            'place_name' => $value['place_name'],
                            'place_short_name' => $value['place_short_name'],
                            'real_name' => $value['real_name'],
                            'id_card' => $value['id_card'],
                            'phone' => $value['phone'],
                            'link_man' => $value['link_man'],
                            'link_phone' => $value['link_phone'],
                            'place_addr' => $value['place_addr'],
                            'yw_street' => $value['yw_street'],
                            'hsjc_result' => $value['hsjc_result'],
                            'hsjc_time' => $value['hsjc_time'],
                            'vaccination_date' => $value['vaccination_date'],
                            'vaccination' => $value['vaccination'] == 1 ? '是' : '否',
                            'vaccination_times' => $value['vaccination_times'],
                            'create_time' => $value['create_time'],
                        ];
                        $export[] = array_values($one_data);
                        $pre_id = $value['id'];
                    }
                }
                $this->_write_csv($filepath,$header,$export,$done_num,$all_num);
            }
        }catch (\Exception $e){
            test_log('placeDeclareExport error:'.$e->getMessage());
        }
    }

    public function placeDeclareAbnormalExport($taskparam)
    {
        $filepath = trim($taskparam['filepath'],'/');
        $param = $taskparam['request'];

        $whiledo = true;
        $pre_id = 0; // 前一个id
        $done_num = 0;
        $param['size'] = 100;
        $header = ['序号','健康码状态', '管控状态', '行程码结果', '场所全称', '场所简称', '姓名', '手机号', '联络人', '联系电话', '地址',
            '义乌镇街', '核酸结果', '核酸时间', '接种日期', '疫苗接种', '接种剂次', '申报时间'];

        try{
            while($whiledo) {
                $param['_where_id_lg'] = $pre_id;
                $dataList = app()->make(PlaceDeclareSlaveDao::class)->getList($param);
                $data = $dataList['data'];
                if($done_num == 0){
                    $all_num = $dataList['total'];
                }
                $export = [];
                if(count($data) == 0) {
                    $whiledo = false;
                }else{
                    foreach ($data as $key => $value) {
                        $one_data = [
                            'num' => $done_num+$key+1,
                            'jkm_mzt' => $value['jkm_mzt'],
                            'ryxx_result' => $value['ryxx_result'],
                            'xcm_result_text' => $value['xcm_result_text'],
                            'place_name' => $value['place_name'],
                            'place_short_name' => $value['place_short_name'],
                            'real_name' => $value['real_name'],
                            'phone' => $value['phone'],
                            'link_man' => $value['link_man'],
                            'link_phone' => $value['link_phone'],
                            'place_addr' => $value['place_addr'],
                            'yw_street' => $value['yw_street'],
                            'hsjc_result' => $value['hsjc_result'],
                            'hsjc_time' => $value['hsjc_time'],
                            'vaccination_date' => $value['vaccination_date'],
                            'vaccination' => $value['vaccination'] == 1 ? '是' : '否',
                            'vaccination_times' => $value['vaccination_times'],
                            'create_time' => $value['create_time'],
                        ];
                        $export[] = array_values($one_data);
                        $pre_id = $value['id'];
                    }
                }
                $this->_write_csv($filepath,$header,$export,$done_num,$all_num);
            }
        }catch (\Exception $e){
            test_log('placeDeclareAbnormalExport error:'.$e->getMessage());
        }
    }

    public function placeDeclareCodeExport($taskparam)
    {
        $filepath = trim($taskparam['filepath'],'/');
        $param = $taskparam['request'];

        $whiledo = true;
        $pre_id = 0; // 前一个id
        $done_num = 0;
        $param['size'] = 100;
        $header = ['序号','健康码状态', '管控状态', '行程码结果', '场所全称', '场所简称', '姓名', '手机号', '联络人', '联系电话', '地址',
            '义乌镇街', '核酸结果', '核酸时间', '接种日期', '疫苗接种', '接种剂次', '申报时间'];

        try{
            while($whiledo) {
                $param['_where_id_lg'] = $pre_id;
                $dataList = app()->make(PlaceDeclareSlaveDao::class)->getList($param);
                $data = $dataList['data'];
                if($done_num == 0){
                    $all_num = $dataList['total'];
                }
                $export = [];
                if(count($data) == 0) {
                    $whiledo = false;
                }else{
                    foreach ($data as $key => $value) {
                        $one_data = [
                            'num' => $done_num+$key+1,
                            'jkm_mzt' => $value['jkm_mzt'],
                            'ryxx_result' => $value['ryxx_result'],
                            'xcm_result_text' => $value['xcm_result_text'],
                            'place_name' => $value['place_name'],
                            'place_short_name' => $value['place_short_name'],
                            'real_name' => $value['real_name'],
                            'phone' => $value['phone'],
                            'link_man' => $value['link_man'],
                            'link_phone' => $value['link_phone'],
                            'place_addr' => $value['place_addr'],
                            'yw_street' => $value['yw_street'],
                            'hsjc_result' => $value['hsjc_result'],
                            'hsjc_time' => $value['hsjc_time'],
                            'vaccination_date' => $value['vaccination_date'],
                            'vaccination' => $value['vaccination'] == 1 ? '是' : '否',
                            'vaccination_times' => $value['vaccination_times'],
                            'create_time' => $value['create_time'],
                        ];
                        $export[] = array_values($one_data);
                        $pre_id = $value['id'];
                    }
                }
                $this->_write_csv($filepath,$header,$export,$done_num,$all_num);
            }
        }catch (\Exception $e){
            test_log('placeDeclareCodeExport error:'.$e->getMessage());
        }
    }

    public function todaySummaryExport($taskparam)
    {
        $filepath = trim($taskparam['filepath'],'/');
        $param = $taskparam['request'];

        $whiledo = true;
        $pre_id = 0; // 前一个id
        $done_num = 0;
        $param['size'] = 100;
        $header = ['序号', '街道', '场所码', '场所名称', '场所简称', '联络员', '联络员电话', '地址', '扫码次数', '申请时间'];

        try{
            $total = app()->make(PlaceDeclareSlaveDao::class)->getPartPlaceListTotal();
            while($whiledo) {
                $param['_where_id_lg'] = $pre_id;
                $place_data = app()->make(PlaceDeclareSlaveDao::class)->getPartPlaceList($param);
                if($done_num == 0) {
                    $all_num = $total;
                }
                $export = [];
                if(count($place_data) == 0) {
                    $whiledo = false;
                    if($done_num > 0) {
                        $this->_write_csv_end($filepath, $done_num);
                    }
                }else{
                    //$param['place_code'] = implode(','.array_column($place_data, 'code'));
                    $place_code = [];
                    foreach($place_data as $v) {
                        $place_code[] = $v['code'];
                    }
                    $param['place_code'] = $place_code;
                    $place_declare_data = app()->make(PlaceDeclareSlaveDao::class)->getTodaySummaryPartList($param);
                    $place_declare_cishu = [];
                    foreach($place_declare_data as $n => $m) {
                        $place_declare_cishu[$m['place_code']] = $m['cishu'];
                    }
                    foreach ($place_data as $key => $value) {
                        $one_data = [
                            'num' => $done_num+$key+1,
                            'yw_street' => $value['yw_street'],
                            'code' => $value['code'],
                            'name' => $value['name'],
                            'short_name' => $value['short_name'],
                            'link_man' => $value['link_man'],
                            'link_phone' => $value['link_phone'],
                            'addr' => $value['addr'],
                            'cishu' => 0,
                            'create_time' => $value['create_time'],
                        ];
                        if(array_key_exists($value['code'], $place_declare_cishu)) {
                            $one_data['cishu'] = $place_declare_cishu[$value['code']];
                        }
                        $export[] = array_values($one_data);
                        $pre_id = $value['id'];
                    }
                }
                $this->_write_csv($filepath,$header,$export,$done_num,$all_num);
            }
        }catch (\Exception $e){
            test_log('todaySummaryExport error:'.$e->getMessage());
        }
    }

    public function predayTotalExport($taskparam)
    {
        $filepath = trim($taskparam['filepath'],'/');
        $param = $taskparam['request'];
        $header = ['镇街', '发码任务数', '发码完成数', '日扫码人次', '24小时活跃码', '日扫10次及以上占活跃码'];

        try{
            $done_num = 0;
            //发码完成数
            $complete_num_list = app()->make(PlaceDeclareSlaveDao::class)->codeSendCompleteNum();
            //日扫码人次
            $scan_time_list = app()->make(PlaceDeclareSlaveDao::class)->dayScanCodeTime($param);
            //24小时活跃码
            $active_code_list = app()->make(PlaceDeclareSlaveDao::class)->dayActiveCode($param);
            //日扫码10次及以上
            $code_time_list = app()->make(PlaceDeclareSlaveDao::class)->scanCodeTimeOrMore($param);
            $export = [];
            $total_complete_num = 0;
            $total_scan_time = 0;
            $total_active_code = 0;
            $total_code_time = 0;
            foreach ($complete_num_list as $v) {
                $scan_time = 0;
                foreach($scan_time_list as $s) {
                    if($v['yw_street'] == $s['yw_street']) {
                        $scan_time = $s['cishu'];
                    }
                }
                $active_code = 0;
                foreach($active_code_list as $s) {
                    if($v['yw_street'] == $s['yw_street']) {
                        $active_code = $s['mashu24'];
                    }
                }
                $code_time = 0;
                foreach($code_time_list as $s) {
                    if($v['yw_street'] == $s['yw_street']) {
                        $code_time = $s['mashu10'];
                    }
                }
                $one_data = [
                    'yw_street' => $v['yw_street'],
                    'task_num' => '',
                    'complete_num' => $v['mashu'],
                    'scan_time' => $scan_time,
                    'active_code' => $active_code,
                    'code_time' => $code_time,
                ];
                $total_complete_num += $one_data['complete_num'];
                $total_scan_time += $one_data['scan_time'];
                $total_active_code += $one_data['active_code'];
                $total_code_time += $one_data['code_time'];
                $export[] = array_values($one_data);
            }
            $one_data = [
                'yw_street' => '合计',
                'task_num' => '',
                'complete_num' => $total_complete_num,
                'scan_time' => $total_scan_time,
                'active_code' => $total_active_code,
                'code_time' => $total_code_time,
            ];
            $export[] = array_values($one_data);
            $this->_write_csv($filepath, $header, $export, $done_num, count($complete_num_list));
        }catch (\Exception $e){
            test_log('predayTotalExport error:'.$e->getMessage());
        }
    }
    
    public function beiyuanFushipinExport($taskparam)
    {
        $filepath = trim($taskparam['filepath'],'/');
        $param = $taskparam['request'];
        $whiledo = true;
        $pre_id = 0; // 前一个id
        $done_num = 0;
        $param['size'] = 100;
        $header = ['序号', '街道', '场所码', '场所名称', '场所简称', '联络员', '联络员电话', '地址', '扫码次数', '申请时间'];
        try{
            $res = app()->make(PlaceDeclareSlaveDao::class)->beiyuanFushipinListTotal();
            $total = $res[0]['total'];
            while($whiledo) {
                $param['_where_id_lg'] = $pre_id;
                $data = app()->make(PlaceDeclareSlaveDao::class)->beiyuanFushipinList($param);
                if($done_num == 0) {
                    $all_num = $total;
                }
                $export = [];
                if(count($data) == 0) {
                    $whiledo = false;
                    if($done_num > 0) {
                        $this->_write_csv_end($filepath, $done_num);
                    }
                }else{
                    foreach ($data as $key => $value) {
                        $one_data = [
                            'num' => $done_num+$key+1,
                            'yw_street' => $value['yw_street'],
                            'code' => $value['code'],
                            'name' => $value['name'],
                            'short_name' => $value['short_name'],
                            'link_man' => $value['link_man'],
                            'link_phone' => $value['link_phone'],
                            'addr' => $value['addr'],
                            'cishu' => $value['cishu'],
                            'create_time' => $value['create_time'],
                        ];
                        $export[] = array_values($one_data);
                        $pre_id = $value['id'];
                    }
                }
                $this->_write_csv($filepath,$header,$export,$done_num,$all_num);
            }
        }catch (\Exception $e){
            test_log('beiyuanFushipinExport error:'.$e->getMessage());
        }
    }

    public function beiyuanGuopinExport($taskparam)
    {
        $filepath = trim($taskparam['filepath'],'/');
        $param = $taskparam['request'];
        $whiledo = true;
        $pre_id = 0; // 前一个id
        $done_num = 0;
        $param['size'] = 100;
        $header = ['序号', '街道', '场所码', '场所名称', '场所简称', '联络员', '联络员电话', '地址', '扫码次数', '申请时间'];
        try{
            $res = app()->make(PlaceDeclareSlaveDao::class)->beiyuanGuopinExportListTotal();
            $total = $res[0]['total'];
            while($whiledo) {
                $param['_where_id_lg'] = $pre_id;
                $data = app()->make(PlaceDeclareSlaveDao::class)->beiyuanGuopinExportList($param);
                if($done_num == 0) {
                    $all_num = $total;
                }
                $export = [];
                if(count($data) == 0) {
                    $whiledo = false;
                    if($done_num > 0) {
                        $this->_write_csv_end($filepath, $done_num);
                    }
                }else{
                    foreach ($data as $key => $value) {
                        $one_data = [
                            'num' => $done_num+$key+1,
                            'yw_street' => $value['yw_street'],
                            'code' => $value['code'],
                            'name' => $value['name'],
                            'short_name' => $value['short_name'],
                            'link_man' => $value['link_man'],
                            'link_phone' => $value['link_phone'],
                            'addr' => $value['addr'],
                            'cishu' => $value['cishu'],
                            'create_time' => $value['create_time'],
                        ];
                        $export[] = array_values($one_data);
                        $pre_id = $value['id'];
                    }
                }
                $this->_write_csv($filepath,$header,$export,$done_num,$all_num);
            }
        }catch (\Exception $e){
            test_log('beiyuanGuopinExport error:'.$e->getMessage());
        }
    }

    public function beiyuanShoucangpinExport($taskparam)
    {
        $filepath = trim($taskparam['filepath'],'/');
        $param = $taskparam['request'];
        $whiledo = true;
        $pre_id = 0; // 前一个id
        $done_num = 0;
        $param['size'] = 100;
        $header = ['序号', '街道', '场所码', '场所名称', '场所简称', '联络员', '联络员电话', '地址', '扫码次数', '申请时间'];
        try{
            $res = app()->make(PlaceDeclareSlaveDao::class)->beiyuanShoucangpinExportListTotal();
            $total = $res[0]['total'];
            while($whiledo) {
                $param['_where_id_lg'] = $pre_id;
                $data = app()->make(PlaceDeclareSlaveDao::class)->beiyuanShoucangpinExportList($param);
                if($done_num == 0) {
                    $all_num = $total;
                }
                $export = [];
                if(count($data) == 0) {
                    $whiledo = false;
                    if($done_num > 0) {
                        $this->_write_csv_end($filepath, $done_num);
                    }
                }else{
                    foreach ($data as $key => $value) {
                        $one_data = [
                            'num' => $done_num+$key+1,
                            'yw_street' => $value['yw_street'],
                            'code' => $value['code'],
                            'name' => $value['name'],
                            'short_name' => $value['short_name'],
                            'link_man' => $value['link_man'],
                            'link_phone' => $value['link_phone'],
                            'addr' => $value['addr'],
                            'cishu' => $value['cishu'],
                            'create_time' => $value['create_time'],
                        ];
                        $export[] = array_values($one_data);
                        $pre_id = $value['id'];
                    }
                }
                $this->_write_csv($filepath,$header,$export,$done_num,$all_num);
            }
        }catch (\Exception $e){
            test_log('beiyuanShoucangpinExport error:'.$e->getMessage());
        }
    }

    public function beiyuanWuziExport($taskparam)
    {
        $filepath = trim($taskparam['filepath'],'/');
        $param = $taskparam['request'];
        $whiledo = true;
        $pre_id = 0; // 前一个id
        $done_num = 0;
        $param['size'] = 100;
        $header = ['序号', '街道', '场所码', '场所名称', '场所简称', '联络员', '联络员电话', '地址', '扫码次数', '申请时间'];
        try{
            $res = app()->make(PlaceDeclareSlaveDao::class)->beiyuanWuziExportListTotal();
            $total = $res[0]['total'];
            while($whiledo) {
                $param['_where_id_lg'] = $pre_id;
                $data = app()->make(PlaceDeclareSlaveDao::class)->beiyuanWuziExportList($param);
                if($done_num == 0) {
                    $all_num = $total;
                }
                $export = [];
                if(count($data) == 0) {
                    $whiledo = false;
                    if($done_num > 0) {
                        $this->_write_csv_end($filepath, $done_num);
                    }
                }else{
                    foreach ($data as $key => $value) {
                        $one_data = [
                            'num' => $done_num+$key+1,
                            'yw_street' => $value['yw_street'],
                            'code' => $value['code'],
                            'name' => $value['name'],
                            'short_name' => $value['short_name'],
                            'link_man' => $value['link_man'],
                            'link_phone' => $value['link_phone'],
                            'addr' => $value['addr'],
                            'cishu' => $value['cishu'],
                            'create_time' => $value['create_time'],
                        ];
                        $export[] = array_values($one_data);
                        $pre_id = $value['id'];
                    }
                }
                $this->_write_csv($filepath,$header,$export,$done_num,$all_num);
            }
        }catch (\Exception $e){
            test_log('beiyuanWuziExport error:'.$e->getMessage());
        }
    }

    public function chengxiLiangshiExport($taskparam)
    {
        $filepath = trim($taskparam['filepath'],'/');
        $param = $taskparam['request'];
        $whiledo = true;
        $pre_id = 0; // 前一个id
        $done_num = 0;
        $param['size'] = 100;
        $header = ['序号', '街道', '场所码', '场所名称', '场所简称', '联络员', '联络员电话', '地址', '扫码次数', '申请时间'];
        try{
            $res = app()->make(PlaceDeclareSlaveDao::class)->chengxiLiangshiExportListTotal();
            $total = $res[0]['total'];
            while($whiledo) {
                $param['_where_id_lg'] = $pre_id;
                $data = app()->make(PlaceDeclareSlaveDao::class)->chengxiLiangshiExportList($param);
                if($done_num == 0) {
                    $all_num = $total;
                }
                $export = [];
                if(count($data) == 0) {
                    $whiledo = false;
                    if($done_num > 0) {
                        $this->_write_csv_end($filepath, $done_num);
                    }
                }else{
                    foreach ($data as $key => $value) {
                        $one_data = [
                            'num' => $done_num+$key+1,
                            'yw_street' => $value['yw_street'],
                            'code' => $value['code'],
                            'name' => $value['name'],
                            'short_name' => $value['short_name'],
                            'link_man' => $value['link_man'],
                            'link_phone' => $value['link_phone'],
                            'addr' => $value['addr'],
                            'cishu' => $value['cishu'],
                            'create_time' => $value['create_time'],
                        ];
                        $export[] = array_values($one_data);
                        $pre_id = $value['id'];
                    }
                }
                $this->_write_csv($filepath,$header,$export,$done_num,$all_num);
            }
        }catch (\Exception $e){
            test_log('chengxiLiangshiExport error:'.$e->getMessage());
        }
    }

    public function chouchengJiadianExport($taskparam)
    {
        $filepath = trim($taskparam['filepath'],'/');
        $param = $taskparam['request'];
        $whiledo = true;
        $pre_id = 0; // 前一个id
        $done_num = 0;
        $param['size'] = 100;
        $header = ['序号', '街道', '场所码', '场所名称', '场所简称', '联络员', '联络员电话', '地址', '扫码次数', '申请时间'];
        try{
            $res = app()->make(PlaceDeclareSlaveDao::class)->chouchengJiadianExportListTotal();
            $total = $res[0]['total'];
            while($whiledo) {
                $param['_where_id_lg'] = $pre_id;
                $data = app()->make(PlaceDeclareSlaveDao::class)->chouchengJiadianExportList($param);
                if($done_num == 0) {
                    $all_num = $total;
                }
                $export = [];
                if(count($data) == 0) {
                    $whiledo = false;
                    if($done_num > 0) {
                        $this->_write_csv_end($filepath, $done_num);
                    }
                }else{
                    foreach ($data as $key => $value) {
                        $one_data = [
                            'num' => $done_num+$key+1,
                            'yw_street' => $value['yw_street'],
                            'code' => $value['code'],
                            'name' => $value['name'],
                            'short_name' => $value['short_name'],
                            'link_man' => $value['link_man'],
                            'link_phone' => $value['link_phone'],
                            'addr' => $value['addr'],
                            'cishu' => $value['cishu'],
                            'create_time' => $value['create_time'],
                        ];
                        $export[] = array_values($one_data);
                        $pre_id = $value['id'];
                    }
                }
                $this->_write_csv($filepath,$header,$export,$done_num,$all_num);
            }
        }catch (\Exception $e){
            test_log('chouchengJiadianExport error:'.$e->getMessage());
        }
    }

    public function choujiangJiajuExport($taskparam)
    {
        $filepath = trim($taskparam['filepath'],'/');
        $param = $taskparam['request'];
        $whiledo = true;
        $pre_id = 0; // 前一个id
        $done_num = 0;
        $param['size'] = 100;
        $header = ['序号', '街道', '场所码', '场所名称', '场所简称', '联络员', '联络员电话', '地址', '扫码次数', '申请时间'];
        try{
            $res = app()->make(PlaceDeclareSlaveDao::class)->choujiangJiajuExportListTotal();
            $total = $res[0]['total'];
            while($whiledo) {
                $param['_where_id_lg'] = $pre_id;
                $data = app()->make(PlaceDeclareSlaveDao::class)->choujiangJiajuExportList($param);
                if($done_num == 0) {
                    $all_num = $total;
                }
                $export = [];
                if(count($data) == 0) {
                    $whiledo = false;
                    if($done_num > 0) {
                        $this->_write_csv_end($filepath, $done_num);
                    }
                }else{
                    foreach ($data as $key => $value) {
                        $one_data = [
                            'num' => $done_num+$key+1,
                            'yw_street' => $value['yw_street'],
                            'code' => $value['code'],
                            'name' => $value['name'],
                            'short_name' => $value['short_name'],
                            'link_man' => $value['link_man'],
                            'link_phone' => $value['link_phone'],
                            'addr' => $value['addr'],
                            'cishu' => $value['cishu'],
                            'create_time' => $value['create_time'],
                        ];
                        $export[] = array_values($one_data);
                        $pre_id = $value['id'];
                    }
                }
                $this->_write_csv($filepath,$header,$export,$done_num,$all_num);
            }
        }catch (\Exception $e){
            test_log('choujiangJiajuExport error:'.$e->getMessage());
        }
    }

    public function choujiangJiancaiExport($taskparam)
    {
        $filepath = trim($taskparam['filepath'],'/');
        $param = $taskparam['request'];
        $whiledo = true;
        $pre_id = 0; // 前一个id
        $done_num = 0;
        $param['size'] = 100;
        $header = ['序号', '街道', '场所码', '场所名称', '场所简称', '联络员', '联络员电话', '地址', '扫码次数', '申请时间'];
        try{
            $res = app()->make(PlaceDeclareSlaveDao::class)->choujiangJiancaiExportListTotal();
            $total = $res[0]['total'];
            while($whiledo) {
                $param['_where_id_lg'] = $pre_id;
                $data = app()->make(PlaceDeclareSlaveDao::class)->choujiangJiancaiExportList($param);
                if($done_num == 0) {
                    $all_num = $total;
                }
                $export = [];
                if(count($data) == 0) {
                    $whiledo = false;
                    if($done_num > 0) {
                        $this->_write_csv_end($filepath, $done_num);
                    }
                }else{
                    foreach ($data as $key => $value) {
                        $one_data = [
                            'num' => $done_num+$key+1,
                            'yw_street' => $value['yw_street'],
                            'code' => $value['code'],
                            'name' => $value['name'],
                            'short_name' => $value['short_name'],
                            'link_man' => $value['link_man'],
                            'link_phone' => $value['link_phone'],
                            'addr' => $value['addr'],
                            'cishu' => $value['cishu'],
                            'create_time' => $value['create_time'],
                        ];
                        $export[] = array_values($one_data);
                        $pre_id = $value['id'];
                    }
                }
                $this->_write_csv($filepath,$header,$export,$done_num,$all_num);
            }
        }catch (\Exception $e){
            test_log('choujiangJiancaiExport error:'.$e->getMessage());
        }
    }

    public function fotangMucaiExport($taskparam)
    {
        $filepath = trim($taskparam['filepath'],'/');
        $param = $taskparam['request'];
        $whiledo = true;
        $pre_id = 0; // 前一个id
        $done_num = 0;
        $param['size'] = 100;
        $header = ['序号', '街道', '场所码', '场所名称', '场所简称', '联络员', '联络员电话', '地址', '扫码次数', '申请时间'];
        try{
            $res = app()->make(PlaceDeclareSlaveDao::class)->fotangMucaiExportListTotal();
            $total = $res[0]['total'];
            while($whiledo) {
                $param['_where_id_lg'] = $pre_id;
                $data = app()->make(PlaceDeclareSlaveDao::class)->fotangMucaiExportList($param);
                if($done_num == 0) {
                    $all_num = $total;
                }
                $export = [];
                if(count($data) == 0) {
                    $whiledo = false;
                    if($done_num > 0) {
                        $this->_write_csv_end($filepath, $done_num);
                    }
                }else{
                    foreach ($data as $key => $value) {
                        $one_data = [
                            'num' => $done_num+$key+1,
                            'yw_street' => $value['yw_street'],
                            'code' => $value['code'],
                            'name' => $value['name'],
                            'short_name' => $value['short_name'],
                            'link_man' => $value['link_man'],
                            'link_phone' => $value['link_phone'],
                            'addr' => $value['addr'],
                            'cishu' => $value['cishu'],
                            'create_time' => $value['create_time'],
                        ];
                        $export[] = array_values($one_data);
                        $pre_id = $value['id'];
                    }
                }
                $this->_write_csv($filepath,$header,$export,$done_num,$all_num);
            }
        }catch (\Exception $e){
            test_log('fotangMucaiExport error:'.$e->getMessage());
        }
    }

    public function fotangNongfuchanpinExport($taskparam)
    {
        $filepath = trim($taskparam['filepath'],'/');
        $param = $taskparam['request'];
        $whiledo = true;
        $pre_id = 0; // 前一个id
        $done_num = 0;
        $param['size'] = 100;
        $header = ['序号', '街道', '场所码', '场所名称', '场所简称', '联络员', '联络员电话', '地址', '扫码次数', '申请时间'];
        try{
            $res = app()->make(PlaceDeclareSlaveDao::class)->fotangNongfuchanpinExportListTotal();
            $total = $res[0]['total'];
            while($whiledo) {
                $param['_where_id_lg'] = $pre_id;
                $data = app()->make(PlaceDeclareSlaveDao::class)->fotangNongfuchanpinExportList($param);
                if($done_num == 0) {
                    $all_num = $total;
                }
                $export = [];
                if(count($data) == 0) {
                    $whiledo = false;
                    if($done_num > 0) {
                        $this->_write_csv_end($filepath, $done_num);
                    }
                }else{
                    foreach ($data as $key => $value) {
                        $one_data = [
                            'num' => $done_num+$key+1,
                            'yw_street' => $value['yw_street'],
                            'code' => $value['code'],
                            'name' => $value['name'],
                            'short_name' => $value['short_name'],
                            'link_man' => $value['link_man'],
                            'link_phone' => $value['link_phone'],
                            'addr' => $value['addr'],
                            'cishu' => $value['cishu'],
                            'create_time' => $value['create_time'],
                        ];
                        $export[] = array_values($one_data);
                        $pre_id = $value['id'];
                    }
                }
                $this->_write_csv($filepath,$header,$export,$done_num,$all_num);
            }
        }catch (\Exception $e){
            test_log('fotangNongfuchanpinExport error:'.$e->getMessage());
        }
    }

    public function houzhaiErshoucheExport($taskparam)
    {
        $filepath = trim($taskparam['filepath'],'/');
        $param = $taskparam['request'];
        $whiledo = true;
        $pre_id = 0; // 前一个id
        $done_num = 0;
        $param['size'] = 100;
        $header = ['序号', '街道', '场所码', '场所名称', '场所简称', '联络员', '联络员电话', '地址', '扫码次数', '申请时间'];
        try{
            $res = app()->make(PlaceDeclareSlaveDao::class)->houzhaiErshoucheExportListTotal();
            $total = $res[0]['total'];
            while($whiledo) {
                $param['_where_id_lg'] = $pre_id;
                $data = app()->make(PlaceDeclareSlaveDao::class)->houzhaiErshoucheExportList($param);
                if($done_num == 0) {
                    $all_num = $total;
                }
                $export = [];
                if(count($data) == 0) {
                    $whiledo = false;
                    if($done_num > 0) {
                        $this->_write_csv_end($filepath, $done_num);
                    }
                }else{
                    foreach ($data as $key => $value) {
                        $one_data = [
                            'num' => $done_num+$key+1,
                            'yw_street' => $value['yw_street'],
                            'code' => $value['code'],
                            'name' => $value['name'],
                            'short_name' => $value['short_name'],
                            'link_man' => $value['link_man'],
                            'link_phone' => $value['link_phone'],
                            'addr' => $value['addr'],
                            'cishu' => $value['cishu'],
                            'create_time' => $value['create_time'],
                        ];
                        $export[] = array_values($one_data);
                        $pre_id = $value['id'];
                    }
                }
                $this->_write_csv($filepath,$header,$export,$done_num,$all_num);
            }
        }catch (\Exception $e){
            test_log('houzhaiErshoucheExport error:'.$e->getMessage());
        }
    }

    public function shangxiMujuExport($taskparam)
    {
        $filepath = trim($taskparam['filepath'],'/');
        $param = $taskparam['request'];
        $whiledo = true;
        $pre_id = 0; // 前一个id
        $done_num = 0;
        $param['size'] = 100;
        $header = ['序号', '街道', '场所码', '场所名称', '场所简称', '联络员', '联络员电话', '地址', '扫码次数', '申请时间'];
        try{
            $res = app()->make(PlaceDeclareSlaveDao::class)->shangxiMujuExportListTotal();
            $total = $res[0]['total'];
            while($whiledo) {
                $param['_where_id_lg'] = $pre_id;
                $data = app()->make(PlaceDeclareSlaveDao::class)->shangxiMujuExportList($param);
                if($done_num == 0) {
                    $all_num = $total;
                }
                $export = [];
                if(count($data) == 0) {
                    $whiledo = false;
                    if($done_num > 0) {
                        $this->_write_csv_end($filepath, $done_num);
                    }
                }else{
                    foreach ($data as $key => $value) {
                        $one_data = [
                            'num' => $done_num+$key+1,
                            'yw_street' => $value['yw_street'],
                            'code' => $value['code'],
                            'name' => $value['name'],
                            'short_name' => $value['short_name'],
                            'link_man' => $value['link_man'],
                            'link_phone' => $value['link_phone'],
                            'addr' => $value['addr'],
                            'cishu' => $value['cishu'],
                            'create_time' => $value['create_time'],
                        ];
                        $export[] = array_values($one_data);
                        $pre_id = $value['id'];
                    }
                }
                $this->_write_csv($filepath,$header,$export,$done_num,$all_num);
            }
        }catch (\Exception $e){
            test_log('shangxiMujuExport error:'.$e->getMessage());
        }
    }

    public function gateFactoryExport($taskparam)
    {
        $filepath = trim($taskparam['filepath'],'/');
        $param = $taskparam['request'];

        $whiledo = true;
        $pre_id = 0; // 前一个id
        $done_num = 0;
        $param['size'] = 100;
        $header = ['序号', '编码', 'key', '名称', '联系人', '联系电话', 'secret', '创建时间'];

        try{
            while($whiledo) {
                $param['_where_id_lg'] = $pre_id;
                $dataList = app()->make(GateFactoryDao::class)->getList($param);
                $data = $dataList['data'];
                if($done_num == 0){
                    $all_num = $dataList['total'];
                }
                $export = [];
                if(count($data) == 0) {
                    $whiledo = false;
                }else{
                    foreach ($data as $key => $value) {
                        $one_data = [
                            'num' => $done_num+$key+1,
                            'code' => $value['code'],
                            'key' => $value['key'],
                            'name' => $value['name'],
                            'link_man' => $value['link_man'],
                            'link_phone' => $value['link_phone'],
                            'secret' => $value['secret'],
                            'create_time' => $value['create_time'],
                        ];
                        $export[] = array_values($one_data);
                        $pre_id = $value['id'];
                    }
                }
                $this->_write_csv($filepath,$header,$export,$done_num,$all_num);
            }
        }catch (\Exception $e){
            test_log('gateFactoryExport error:'.$e->getMessage());
        }
    }

    public function gateExport($taskparam)
    {
        $filepath = trim($taskparam['filepath'],'/');
        $param = $taskparam['request'];

        $whiledo = true;
        $pre_id = 0; // 前一个id
        $done_num = 0;
        $param['size'] = 100;
        $header = ['序号', '闸机code', '闸机名称', '联系人', '联系电话', '地址','闸机厂商', '创建时间'];

        try{
            while($whiledo) {
                $param['_where_id_lg'] = $pre_id;
                $dataList = app()->make(GateDao::class)->getList($param);
                $data = $dataList['data'];
                if($done_num == 0){
                    $all_num = $dataList['total'];
                }
                $export = [];
                if(count($data) == 0) {
                    $whiledo = false;
                }else{
                    foreach ($data as $key => $value) {
                        $one_data = [
                            'num' => $done_num+$key+1,
                            'code' => $value['code'],
                            'name' => $value['name'],
                            'link_man' => $value['link_man'],
                            'link_phone' => $value['link_phone'],
                            'addr' => $value['addr'],
                            'gate_factory_name' => $value['gate_factory_name'],
                            'create_time' => $value['create_time'],
                        ];
                        $export[] = array_values($one_data);
                        $pre_id = $value['id'];
                    }
                }
                $this->_write_csv($filepath,$header,$export,$done_num,$all_num);
            }
        }catch (\Exception $e){
            test_log('gateExport error:'.$e->getMessage());
        }
    }

    public function gateDeclareExport($taskparam)
    {
        $filepath = trim($taskparam['filepath'],'/');
        $param = $taskparam['request'];

        $whiledo = true;
        $pre_id = 0; // 前一个id
        $done_num = 0;
        $param['size'] = 100;
        $header = ['序号', '编码', '闸机名称', '姓名', '身份证', '手机', '接种次数', '健康码时间', '健康码状态', '核酸检测时间', '核酸结果', '人员管控信息'];

        try{
            while($whiledo) {
                $param['_where_id_lg'] = $pre_id;
                $dataList = app()->make(GateDeclareDao::class)->getList($param);
                $data = $dataList['data'];
                if($done_num == 0) {
                    $all_num = $dataList['total'];
                }
                $export = [];
                if(count($data) == 0) {
                    $whiledo = false;
                }else{
                    foreach ($data as $key => $value) {
                        $one_data = [
                            'num' => $done_num+$key+1,
                            'gate_name' => $value['gate_name'],
                            'real_name' => $value['real_name'],
                            'id_card' => $value['id_card'],
                            'phone' => $value['phone'],
                            'vaccination_times' => $value['vaccination_times'],
                            'jkm_time' => $value['jkm_time'],
                            'jkm_mzt' => $value['jkm_mzt'],
                            'hsjc_time' => $value['hsjc_time'],
                            'hsjc_result' => $value['hsjc_result'],
                            'ryxx_result' => $value['ryxx_result'],
                        ];
                        $export[] = array_values($one_data);
                        $pre_id = $value['id'];
                    }
                }
                $this->_write_csv($filepath,$header,$export,$done_num,$all_num);
            }
        }catch (\Exception $e){
            test_log('gateDeclareExport error:'.$e->getMessage());
        }
    }

    public function querycenterRygkExport($taskparam)
    {
        $filepath = trim($taskparam['filepath'],'/');
        $param = $taskparam['request'];

        $whiledo = true;
        $pre_id = 0; // 前一个id
        $done_num = 0;
        $param['size'] = 100;
        $header = ['序号', '管控人员姓名', '身份证号码', '手机号', '镇街', '村社', '管控开始时间', '管控结束时间', '管控状态', '来源', '颜色', '创建时间',
            '频次', '企业名称', '联系人', '联系号码', '人员类型'];

        try{
            while($whiledo) {
                $param['_where_id_lg'] = $pre_id;
                $dataList = app()->make(QueryCenterServices::class)->rygkService($param);
                $data = $dataList['data'];
                if($done_num == 0) {
                    $all_num = $dataList['total'];
                }
                $export = [];
                if(count($data) == 0) {
                    $whiledo = false;
                }else{
                    foreach ($data as $key => $value) {
                        $one_data = [
                            'num' => $done_num+$key+1,
                            'name' => $value['name'],
                            'idcard' => $value['idcard'],
                            'phonenum' => $value['phonenum'],
                            'town' => $value['town'],
                            'village' => $value['village'],
                            'quarantine_start_time' => $value['quarantine_start_time'],
                            'quarantine_end_time' => $value['quarantine_end_time'],
                            'state' => $value['state'],
                            'source' => $value['source'],
                            'cc' => $value['cc'],
                            'create_datetime' => $value['create_datetime'],
                            'frequency' => $value['frequency'],
                            'company_name' => $value['company_name'],
                            'lxname' => $value['lxname'],
                            'lxphone' => $value['lxphone'],
                            'person_classification' => $value['person_classification'],
                        ];
                        $export[] = array_values($one_data);
                        $pre_id = $value['id'];
                    }
                }
                $this->_write_csv($filepath,$header,$export,$done_num,$all_num);
            }
        }catch (\Exception $e){
            test_log('querycenterRygkExport error:'.$e->getMessage());
        }
    }

    public function personalCodeExport($taskparam)
    {
        $filepath = trim($taskparam['filepath'],'/');
        $param = $taskparam['request'];

        $whiledo = true;
        $pre_id = 0; // 前一个id
        $done_num = 0;
        $param['size'] = 100;
        $header = ['序号', '姓名', '性别', '身份证号码', '手机号', '代领人姓名', '代领人身份证号', '代领时间'];

        try{
            while($whiledo) {
                $param['_where_id_lg'] = $pre_id;
                $dataList = app()->make(PersonalCodeDao::class)->getList($param);
                $data = $dataList['data'];
                if($done_num == 0) {
                    $all_num = $dataList['total'];
                }
                $export = [];
                if(count($data) == 0) {
                    $whiledo = false;
                }else{
                    foreach ($data as $key => $value) {
                        $one_data = [
                            'num' => $done_num+$key+1,
                            'real_name' => $value['real_name'],
                            'id_card' => $value['id_card'],
                            'phone' => $value['phone'],
                            'agent_name' => $value['agent_name'],
                            'agent_idcard' => $value['agent_idcard'],
                            'create_time' => $value['create_time'],
                        ];
                        $export[] = array_values($one_data);
                        $pre_id = $value['id'];
                    }
                }
                $this->_write_csv($filepath,$header,$export,$done_num,$all_num);
            }
        }catch (\Exception $e){
            test_log('personalCodeExport error:'.$e->getMessage());
        }
    }

    private function _write_csv($filepath, $header, $export, &$done_num=0, $all_num=0)
    {
        $fp = fopen(public_path().$filepath, 'a+');
        if($done_num == 0){
            fwrite($fp, chr(0xEF).chr(0xBB).chr(0xBF)); // 添加 BOM
            fputcsv($fp,  array_map(function($item){
                return trim($item)."\t"; // 使其变成字符串，否则身份证会变成数字型
            } ,array_values($header)));
        }
        // 开始写
        foreach($export as $key => $value){                        
            fputcsv($fp,  array_map(function($item){
                return trim($item)."\t"; // 使其变成字符串，否则身份证会变成数字型
            } ,$value));
        }
        $done_num = $done_num + count($export);
        fclose($fp);
        // 进度，判断是否已经全部完成
        $work_path = str_replace('.csv','.work',$filepath);
        $workfp = fopen(public_path(). $work_path, 'w'); // w
        fputcsv($workfp, [$done_num,$all_num]);
        fclose($workfp);
    }

    private function _write_csv_end($filepath, $done_num=0)
    {
        // 进度直接置为全部完成
        $work_path = str_replace('.csv','.work',$filepath);
        $workfp = fopen(public_path(). $work_path, 'w'); // w
        fputcsv($workfp, [$done_num,$done_num]);
        fclose($workfp);
    }
}
