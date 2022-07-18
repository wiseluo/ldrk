<?php

namespace app\services\admin;

use app\services\admin\BaseServices;
use crmeb\services\SpreadsheetExcelService;
use app\services\admin\OwnDeclareServices;
use app\services\admin\OwnDeclareOcrServices;
use app\services\admin\CompanyServices;

class ExportServices extends BaseServices
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
     * @param array $export 填充数据
     * @param string $filename 保存文件名称
     * @param string $suffix 保存文件后缀
     * @param bool $is_save true|false 是否保存到本地
     * @return mixed
     */
    public function export(array $header, array $title_arr, array $export = [], string $filename = '', string $suffix = 'xlsx', bool $is_save = true)
    {
        $path = [];
        // if (!$export) {
        //     return $path;
        // }
        $title = isset($title_arr[0]) && !empty($title_arr[0]) ? $title_arr[0] : '导出数据';
        $name = isset($title_arr[1]) && !empty($title_arr[1]) ? $title_arr[1] : '导出数据';
        $info = isset($title_arr[2]) && !empty($title_arr[2]) ? $title_arr[2] : date('Y-m-d H:i:s', time());
        $filePath = SpreadsheetExcelService::instance()->setExcelHeader($header)
            ->setExcelTile($title, $name, $info)
            ->setExcelContent($export)
            ->excelSave($filename, $suffix, $is_save);
        $path[] = sys_config('site_url') . $filePath;
        return $path;
    }

    public function owndeclareListData($param)
    {
        $all_data = app()->make(OwnDeclareServices::class)->getList($param['request']);
        $data = $all_data['data'];
        $this->{$param['export_fun']}($data, $param['filename']);
        return true;
    }

    public function leaveOwndeclareListExport($data, $filename)
    {
        $header = ['序号', '姓名', '证件类型', '证件号', '手机号', '目的地', '离义时间', '预计返义时间', '申报时间'];
        $title = ['外出申报资料', '外出申报资料', date('Y-m-d H:i:s', time())];
        $export = [];
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $one_data = [
                    'num' => $key+1,
                    'real_name' => $value['real_name'],
                    'card_type_text' => $value['card_type_text'],
                    'id_card' => $value['id_card'],
                    'phone' => $value['phone'],
                    'destination' => $value['destination'],
                    'leave_time' => $value['leave_time'],
                    'expect_return_time' => $value['expect_return_time'],
                    'create_time' => $value['create_time'],
                ];
                $export[] = array_values($one_data);
            }
        }
        $this->export($header, $title, $export, $filename, 'xlsx', true);
        return true;
    }

    public function comeOwndeclareListExport($data, $filename)
    {
        $header = ['序号', '姓名', '证件类型', '证件号', '手机号', '来源地', '途径城市', '来义居所', '来义时间'];
        $title = ['来返义申报资料', '来返义申报资料', date('Y-m-d H:i:s', time())];
        $export = [];
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $one_data = [
                    'num' => $key+1,
                    'real_name' => $value['real_name'],
                    'card_type_text' => $value['card_type_text'],
                    'id_card' => $value['id_card'],
                    'phone' => $value['phone'],
                    'destination' => $value['destination'],
                    'travel_route' => $value['travel_route'],
                    'yw_street' => $value['yw_street'],
                    'arrival_time' => $value['arrival_time']
                ];
                $export[] = array_values($one_data);
            }
        }
        $this->export($header, $title, $export, $filename, 'xlsx', true);
        return true;
    }

    public function riskareaOwndeclareListExport($data, $filename)
    {
        $header = ['序号', '姓名', '证件类型', '证件号', '手机号', '来源地', '途径城市', '来义居所', '来义时间'];
        $title = ['中高风险地区申报', '中高风险地区申报', date('Y-m-d H:i:s', time())];
        $export = [];
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $one_data = [
                    'num' => $key+1,
                    'real_name' => $value['real_name'],
                    'card_type_text' => $value['card_type_text'],
                    'id_card' => $value['id_card'],
                    'phone' => $value['phone'],
                    'destination' => $value['destination'],
                    'travel_route' => $value['travel_route'],
                    'yw_street' => $value['yw_street'],
                    'arrival_time' => $value['arrival_time'],
                ];
                $export[] = array_values($one_data);
            }
        }
        $this->export($header, $title, $export, $filename, 'xlsx', true);
        return true;
    }

    public function barrierOwndeclareListExport($data, $filename)
    {
        $header = ['序号', '姓名', '证件类型', '证件号', '手机号', '来源地', '来义居所', '行程码是否有星号', '申报时间', '健康码', '疫苗接种剂次', '疫苗接种日期', '最近核酸检测结果', '检测时间', '管控措施'];
        $title = ['卡口申报', '卡口申报', date('Y-m-d H:i:s', time())];
        $export = [];
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $one_data = [
                    'num' => $key+1,
                    'real_name' => $value['real_name'],
                    'card_type_text' => $value['card_type_text'],
                    'id_card' => $value['id_card'],
                    'phone' => $value['phone'],
                    'destination' => $value['destination'],
                    'yw_street' => $value['yw_street'],
                    'isasterisk' => $value['isasterisk'] == 0 ? '否' : '是',
                    'create_time' => $value['create_time'],
                    'jkm_mzt' => $value['jkm_mzt'],
                    'vaccination_times' => $value['vaccination_times'],
                    'vaccination_date' => $value['vaccination_date'],
                    'hsjc_result' => $value['hsjc_result'],
                    'hsjc_time' => $value['hsjc_time'],
                    'control_state' => $value['control_state'],
                ];
                $export[] = array_values($one_data);
            }
        }
        $this->export($header, $title, $export, $filename, 'xlsx', true);
        return true;
    }

    public function quarantineOwndeclareListExport($data, $filename)
    {
        $header = ['序号', '姓名', '证件类型', '证件号', '手机号'];
        $title = ['卡口申报', '卡口申报', date('Y-m-d H:i:s', time())];
        $export = [];
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $one_data = [
                    'num' => $key+1,
                    'real_name' => $value['real_name'],
                    'card_type_text' => $value['card_type_text'],
                    'id_card' => $value['id_card'],
                    'phone' => $value['phone']
                ];
                $export[] = array_values($one_data);
            }
        }
        $this->export($header, $title, $export, $filename, 'xlsx', true);
        return true;
    }
    
    public function dataerrorListData($param)
    {
        $all_data = app()->make(OwnDeclareServices::class)->getDataError($param['request'], $param['declare_type']);
        $data = $all_data['data'];
        $this->{$param['export_fun']}($data, $param['filename']);
        return true;
    }

    public function dataerrorLeaveListExport($data, $filename)
    {
        $header = ['序号', '异常原因', '姓名', '证件类型', '证件号', '手机号', '目的地', '离义时间', '申报时间'];
        $title = ['异常信息外出申报异常', '异常信息外出申报异常', date('Y-m-d H:i:s', time())];
        $export = [];
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $one_data = [
                    'num' => $key+1,
                    'error_infos' => $value['error_infos'],
                    'real_name' => $value['real_name'],
                    'card_type_text' => $value['card_type_text'],
                    'id_card' => $value['id_card'],
                    'phone' => $value['phone'],
                    'destination' => $value['destination'],
                    'leave_time' => $value['leave_time'],
                    'create_time' => $value['create_time'],
                ];
                $export[] = array_values($one_data);
            }
        }
        $this->export($header, $title, $export, $filename, 'xlsx', true);
        return true;
    }
    
    public function dataerrorComeListExport($data, $filename)
    {
        $header = ['序号', '异常原因', '姓名', '证件类型', '证件号', '手机号', '来源地', '途径城市', '来义居所'];
        $title = ['异常信息来返义申报异常', '异常信息来返义申报异常', date('Y-m-d H:i:s', time())];
        $export = [];
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $one_data = [
                    'num' => $key+1,
                    'error_infos' => $value['error_infos'],
                    'real_name' => $value['real_name'],
                    'card_type_text' => $value['card_type_text'],
                    'id_card' => $value['id_card'],
                    'phone' => $value['phone'],
                    'destination' => $value['destination'],
                    'travel_route' => $value['travel_route'],
                    'yw_street' => $value['yw_street'],
                ];
                $export[] = array_values($one_data);
            }
        }
        $this->export($header, $title, $export, $filename, 'xlsx', true);
        return true;
    }
    
    public function dataerrorRiskareaListExport($data, $filename)
    {
        $header = ['序号', '异常原因', '姓名', '证件类型', '证件号', '手机号', '来源地', '来义居所', '来义时间', '详细地址', '申报时间', '离开高风险地区时间', '来义交通工具', '车次/航班/车牌'];
        $title = ['异常信息中高风险申报异常', '异常信息中高风险申报异常', date('Y-m-d H:i:s', time())];
        $export = [];
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $one_data = [
                    'num' => $key+1,
                    'error_infos' => $value['error_infos'],
                    'real_name' => $value['real_name'],
                    'card_type_text' => $value['card_type_text'],
                    'id_card' => $value['id_card'],
                    'phone' => $value['phone'],
                    'destination' => $value['destination'],
                    'yw_street' => $value['yw_street'],
                    'arrival_time' => $value['arrival_time'],
                    'address' => $value['address'],
                    'create_time' => $value['create_time'],
                    'leave_riskarea_time' => $value['leave_riskarea_time'],
                    'arrival_transport_mode_text' => $value['arrival_transport_mode_text'],
                    'arrival_sign' => $value['arrival_sign'],
                ];
                $export[] = array_values($one_data);
            }
        }
        $this->export($header, $title, $export, $filename, 'xlsx', true);
        return true;
    }
    
    public function dataerrorTodayManyListExport($param)
    {
        $filename = $param['filename'];
        $data = app()->make(OwnDeclareServices::class)->getTodayMany($param['request']);
        
        $header = ['序号', '姓名', '相关ids', '离义申报数量', '来义申报数量', '高风险申报数量'];
        $title = ['异常信息短期申报', '异常信息短期申报', date('Y-m-d H:i:s', time())];
        $export = [];
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $one_data = [
                    'num' => $key+1,
                    'real_name' => $value['real_name'],
                    'ids' => $value['ids'],
                    'leave_nums' => $value['leave_nums'],
                    'come_nums' => $value['come_nums'],
                    'riskarea_nums' => $value['riskarea_nums'],
                ];
                $export[] = array_values($one_data);
            }
        }
        $this->export($header, $title, $export, $filename, 'xlsx', true);
        return true;
    }
    
    public function dataerrorOcrListExport($param)
    {
        $filename = $param['filename'];
        $all_data = app()->make(OwnDeclareOcrServices::class)->getList($param['request']);
        $data = $all_data['data'];
        
        $header = ['序号', '申报id', '行程码原始数据', '行程', '行程最新更新时间', '备注'];
        $title = ['异常信息行程码识别异常', '异常信息行程码识别异常', date('Y-m-d H:i:s', time())];
        $export = [];
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $one_data = [
                    'num' => $key+1,
                    'declare_id' => $value['declare_id'],
                    'travel_content' => $value['travel_content'],
                    'travel_route' => $value['travel_route'],
                    'travel_time' => $value['travel_time'],
                    'remark' => $value['remark'],
                ];
                $export[] = array_values($one_data);
            }
        }
        $this->export($header, $title, $export, $filename, 'xlsx', true);
        return true;
    }
    
    public function dataerrorTravelAsteriskListExport($param)
    {
        $filename = $param['filename'];
        $all_data = app()->make(OwnDeclareOcrServices::class)->getList($param['request']);
        $data = $all_data['data'];
        
        $header = ['序号', '申报id', '行程', '行程最新更新时间', '备注'];
        $title = ['异常信息行程码带星', '异常信息行程码带星', date('Y-m-d H:i:s', time())];
        $export = [];
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $one_data = [
                    'num' => $key+1,
                    'declare_id' => $value['declare_id'],
                    'travel_route' => $value['travel_route'],
                    'travel_time' => $value['travel_time'],
                    'remark' => $value['remark'],
                ];
                $export[] = array_values($one_data);
            }
        }
        $this->export($header, $title, $export, $filename, 'xlsx', true);
        return true;
    }
    
    public function dataerrorJkmmztListExport($param)
    {
        $filename = $param['filename'];
        $all_data = app()->make(OwnDeclareOcrServices::class)->getList($param['request']);
        $data = $all_data['data'];
        
        $header = ['序号', '申报id', '行程', '健康码获取时间', '健康码状态'];
        $title = ['异常信息非绿码记录', '异常信息非绿码记录', date('Y-m-d H:i:s', time())];
        $export = [];
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $one_data = [
                    'num' => $key+1,
                    'declare_id' => $value['declare_id'],
                    'travel_route' => $value['travel_route'],
                    'jkm_time' => $value['jkm_time'],
                    'jkm_mzt' => $value['jkm_mzt'],
                ];
                $export[] = array_values($one_data);
            }
        }
        $this->export($header, $title, $export, $filename, 'xlsx', true);
        return true;
    }

    public function datawarningListData($param)
    {
        $all_data = app()->make(OwnDeclareServices::class)->getDataWarning($param['request'], $param['warning_type']);
        $data = $all_data['data'];
        $this->{$param['export_fun']}($data, $param['filename']);
        return true;
    }

    public function datawarningBackouttimeListExport($data, $filename)
    {
        $header = ['序号', '姓名', '证件类型', '证件号', '手机号', '离义时间', '预计返义时间', '是否短信通知', '短信内容', '短信时间'];
        $title = ['未按时返义', '未按时返义', date('Y-m-d H:i:s', time())];
        $export = [];
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $one_data = [
                    'num' => $key+1,
                    'real_name' => $value['real_name'],
                    'card_type_text' => $value['card_type_text'],
                    'id_card' => $value['id_card'],
                    'phone' => $value['phone'],
                    'leave_time' => $value['leave_time'],
                    'expect_return_time' => $value['expect_return_time'],
                    'send_sms' => $value['send_sms'] == 0 ? '否' : '是',
                    'sms_content' => $value['sms_content'],
                    'sms_send_time' => $value['sms_send_time'],
                ];
                $export[] = array_values($one_data);
            }
        }
        $this->export($header, $title, $export, $filename, 'xlsx', true);
        return true;
    }
    
    public function datawarningRiskareaListExport($data, $filename)
    {
        $header = ['序号', '姓名', '证件类型', '证件号', '手机号', '来源地', '来义居所', '来义时间', '详细地址', '离开中高风险地区时间', '来义交通工具', '车次/航班/车牌', '核酸检测结果', '疫苗接种时间', '接种剂次', '风险判断规则', '规则说明', '是否已推送', '管控措施'];
        $title = ['中高风险密接人员', '中高风险密接人员', date('Y-m-d H:i:s', time())];
        $export = [];
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $one_data = [
                    'num' => $key+1,
                    'real_name' => $value['real_name'],
                    'card_type_text' => $value['card_type_text'],
                    'id_card' => $value['id_card'],
                    'phone' => $value['phone'],
                    'destination' => $value['destination'],
                    'yw_street' => $value['yw_street'],
                    'arrival_time' => $value['arrival_time'],
                    'address' => $value['address'],
                    'leave_riskarea_time' => $value['leave_riskarea_time'],
                    'arrival_transport_mode_text' => $value['arrival_transport_mode_text'],
                    'arrival_sign' => $value['arrival_sign'],
                    'hsjc_result' => $value['hsjc_result'],
                    'vaccination_date' => $value['vaccination_date'],
                    'vaccination_times' => $value['vaccination_times'],
                    'warning_from_text' => $value['warning_from_text'],
                    'warning_rule_text' => $value['warning_rule_text'],
                    'is_to_oa' => $value['is_to_oa'],
                    'control_state' => $value['control_state'],
                ];
                $export[] = array_values($one_data);
            }
        }
        $this->export($header, $title, $export, $filename, 'xlsx', true);
        return true;
    }
    
    public function userListExport($param)
    {
        $filename = $param['filename'];
        $all_data = app()->make(OwnDeclareOcrServices::class)->getListService($param['request']);
        $data = $all_data['data'];
        
        $header = ['序号', '姓名', '性别', '民族', '身份证号', '年龄', '户籍地址', '联系电话', '核酸检测结果', '是否疫苗接种', '位置状态', '申报类型', '途径城市', '是否中高风险地区申报'];
        $title = ['人员信息', '人员信息', date('Y-m-d H:i:s', time())];
        $export = [];
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $one_data = [
                    'num' => $key+1,
                    'real_name' => $value['real_name'],
                    'gender_text' => $value['gender_text'],
                    'nation' => $value['nation'],
                    'id_card' => $value['id_card'],
                    'age' => $value['age'],
                    'permanent_address' => $value['permanent_address'],
                    'phone' => $value['phone'],
                    'hsjc_result' => $value['hsjc_result'],
                    'vaccination' => $value['vaccination'] == 0 ? '否' : '是',
                    'position_type_text' => $value['position_type_text'],
                    'declare_type_text' => $value['declare_type_text'],
                    'travel_route' => $value['travel_route'],
                    'is_riskarea' => $value['declare_type'] == 'riskarea' ? '是' : '否',
                ];
                $export[] = array_values($one_data);
            }
        }
        $this->export($header, $title, $export, $filename, 'xlsx', true);
        return true;
    }
    
    public function statisticListData($param)
    {
        $data = app()->make(OwnDeclareServices::class)->getStatistic($param['request'], $param['statistic_type']);
        $this->{$param['export_fun']}($data, $param['filename']);
        return true;
    }

    public function statisticFromwhereListExport($data, $filename)
    {
        $header = ['序号', '省份', '来返义', '中高风险', '合计'];
        $title = ['来源地统计', '来源地统计', date('Y-m-d H:i:s', time())];
        $export = [];
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $one_data = [
                    'num' => $key+1,
                    'province' => $value['province'],
                    'come_nums' => $value['come_nums'],
                    'riskarea_nums' => $value['riskarea_nums'],
                    'total_nums' => $value['total_nums'],
                ];
                $export[] = array_values($one_data);
            }
        }
        $this->export($header, $title, $export, $filename, 'xlsx', true);
        return true;
    }
    
    public function statisticYwstreetListExport($data, $filename)
    {
        $header = ['序号', '镇街', '来返义', '中高风险', '合计'];
        $title = ['来义街道统计', '来义街道统计', date('Y-m-d H:i:s', time())];
        $export = [];
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $one_data = [
                    'num' => $key+1,
                    'yw_street' => $value['yw_street'],
                    'come_nums' => $value['come_nums'],
                    'riskarea_nums' => $value['riskarea_nums'],
                    'total_nums' => $value['total_nums'],
                ];
                $export[] = array_values($one_data);
            }
        }
        $this->export($header, $title, $export, $filename, 'xlsx', true);
        return true;
    }
    
    public function statisticAgeListExport($data, $filename)
    {
        $header = ['序号', '年龄段', '来返义', '中高风险', '合计'];
        $title = ['年龄段统计', '年龄段统计', date('Y-m-d H:i:s', time())];
        $export = [];
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $one_data = [
                    'num' => $key+1,
                    'name' => $value['name'],
                    'come_nums' => $value['come_nums'],
                    'riskarea_nums' => $value['riskarea_nums'],
                    'total_nums' => $value['total_nums'],
                ];
                $export[] = array_values($one_data);
            }
        }
        $this->export($header, $title, $export, $filename, 'xlsx', true);
        return true;
    }
    
    public function unqualifiedCompanyExport($param)
    {
        $filename = $param['filename'];
        $all_data = app()->make(CompanyServices::class)->getUnqualifiedList($param['request']);
        $data = $all_data['data'];
        
        $header = ['序号', '企业名称', '联络人', '联络人手机号', '所属街道', '社区', '营业代码', '地址', '员工数', '7天内检测率', '7天缺检人数', '70天内检测率', '70天缺检人数'];
        $title = ['人员信息', '人员信息', date('Y-m-d H:i:s', time())];
        $export = [];
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $one_data = [
                    'num' => $key+1,
                    'name' => $value['name'],
                    'link_name' => $value['link_name'],
                    'link_phone' => $value['link_phone'],
                    'yw_street' => $value['yw_street'],
                    'community' => $value['community'],
                    'credit_code' => $value['credit_code'],
                    'addr' => $value['addr'],
                    'user_count' => $value['user_count'],
                    'seven_rate' => $value['seven_rate'],
                    'seven_lack' => $value['seven_lack'],
                    'seventy_rate' => $value['seventy_rate'],
                    'seventy_lack' => $value['seventy_lack'],
                ];
                $export[] = array_values($one_data);
            }
        }
        $this->export($header, $title, $export, $filename, 'xlsx', true);
        return true;
    }
    
    public function companyExport($param)
    {
        $filename = $param['filename'];
        $all_data = app()->make(CompanyServices::class)->getList($param['request']);
        $data = $all_data['data'];
        
        $header = ['序号', '企业名称', '员工数', '联络人', '联络人手机号', '所属街道', '社区', '营业代码', '地址'];
        $title = ['人员信息', '人员信息', date('Y-m-d H:i:s', time())];
        $export = [];
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $one_data = [
                    'num' => $key+1,
                    'name' => $value['name'],
                    'user_count' => $value['user_count'],
                    'link_name' => $value['link_name'],
                    'link_phone' => $value['link_phone'],
                    'yw_street' => $value['yw_street'],
                    'community' => $value['community'],
                    'credit_code' => $value['credit_code'],
                    'addr' => $value['addr'],
                ];
                $export[] = array_values($one_data);
            }
        }
        $this->export($header, $title, $export, $filename, 'xlsx', true);
        return true;
    }
}
