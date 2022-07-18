<?php

namespace crmeb\jobs;

use crmeb\basic\BaseJob;
use crmeb\services\SpreadsheetExcelService;
use think\facade\Log;

class ExportExcelJob extends BaseJob
{
    /**
     * 分批导出excel
     * @param $order
     * @return bool
     */
    public function doJob(array $export = [], string $filename = '', array $header = [], array $title_arr = [], string $suffix = 'xlsx', bool $is_save = false)
    {
        if (!$export) {
            return true;
        }
        try {
            if ($header && $title_arr) {
                $title = isset($title_arr[0]) && !empty($title_arr[0]) ? $title_arr[0] : '导出数据';
                $name = isset($title_arr[1]) && !empty($title_arr[1]) ? $title_arr[1] : '导出数据';
                $info = isset($title_arr[2]) && !empty($title_arr[2]) ? $title_arr[2] : date('Y-m-d H:i:s', time());
                SpreadsheetExcelService::instance()
                    ->setExcelHeader($header)
                    ->setExcelTile($title, $name, $info)
                    ->setExcelContent($export)
                    ->excelSave($filename, $suffix, $is_save);
            } else {
                SpreadsheetExcelService::instance()
                    ->setExcelContent($export)
                    ->excelSave($filename, $suffix, $is_save);
            }
        } catch (\Throwable $e) {
            Log::error('导出excel' . $title . '失败，原因：' . $e->getMessage());
        }
        return true;
    }
}
