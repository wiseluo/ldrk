<?php

namespace app\services\ainat;

use app\services\ainat\BaseServices;
use crmeb\services\SpreadsheetExcelService;
use app\dao\AinatCompareDao;

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

    public function ainatCompareListExport($taskParam)
    {
        $filename = $taskParam['filename'];
        $param = $taskParam['request'];
        $header = ['证件号', '检测要求期限（小时）', '最近一次采样时间', '距离超期时间（小时）', '是否超期未检', '人员类别', '姓名', '手机号', '所在镇街',
            '所属社区', '所在企业（单位）', '负责人', '手机号', '主管部门', '部门负责人', '电话'];
        $title = ['AI核酸比对', 'AI核酸比对', date('Y-m-d H:i:s', time())];

        $export = [];
        $data = app()->make(AinatCompareDao::class)->getList($param, 1);
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                $one_data = [
                    'user_idcard' => $v['user_idcard'] ."\t",
                    'natest_hours' => $v['natest_hours'],
                    'collect_time' => $v['collect_time'],
                    'unexpired_hours' => $v['unexpired_hours'],
                    'is_overdue' => $v['is_overdue'] == '是' ? '超期' : '',
                    'staff_classify_name' => $v['staff_classify_name'],
                    'user_name' => $v['user_name'],
                    'user_phone' => $v['user_phone'] ."\t",
                    'yw_street' => $v['yw_street'],
                    'community' => $v['community'],
                    'company_name' => $v['company_name'],
                    'link_name' => $v['link_name'],
                    'link_phone' => $v['link_phone'],
                    'gov' => $v['gov'],
                    'gov_charge' => $v['gov_charge'],
                    'charge_phone' => $v['charge_phone'] ."\t",
                ];
                $export[] = array_values($one_data);
            }
        }
        $this->export($header, $title, $export, $filename, 'xlsx', true);
        return true;
    }

}
