<?php

namespace app\services\ainat;

use app\dao\AinatCompareDao;
use \behavior\IdentityCardTool;
use app\services\ainat\BaseServices;
use crmeb\exceptions\AdminException;
use \think\facade\Db;

class ImportServices extends BaseServices
{
    /**对象转字符
     * @param $value
     * @return mixed
     */
    public function objToStr($value)
    {
        return is_object($value) ? $value->__toString() : $value;
    }

    //读取excel文件内容
    public function readExcel($filePath, $dataService, $admin)
    {
        $pathInfo = pathinfo($filePath, PATHINFO_EXTENSION);
        if (!$pathInfo || $pathInfo != "xlsx") throw new AdminException('必须上传xlsx格式文件', 400);
        //加载读取模型
        $readModel = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
        // 创建读操作
        // 打开文件 载入excel表格
        try {
            $readModel->setReadDataOnly(TRUE);
            $spreadsheet = $readModel->load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            # 获取总行数
            $highestRow = $sheet->getHighestRow();
            $lines = $highestRow - 1;
            if ($lines <= 0) {
                throw new AdminException('excel数据不能为空', 400);
            }
            // 获取总列数
            $highestColumn = $sheet->getHighestColumn();
            # 列数 改为数字显示
            $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

            //使用索引循环遍历单元格
            $data = [];
            for($row = 2; $row <= $highestRow; ++$row) {
                $row_item = [];
                for ($col = 1; $col <= $highestColumnIndex; ++$col) {
                    $row_item[] = trim($this->objToStr($sheet->getCellByColumnAndRow($col, $row)->getValue()));
                }
                $data[] = $row_item;
            }

            return $this->$dataService($data, $admin);
        } catch (\Exception $e) {
            throw new AdminException($e->getMessage(), 400);
        }
    }

    public function natCompare($list, $admin)
    {
        $ainatCompareDao = app()->make(AinatCompareDao::class);
        $error_str = '';
        $sign = md5('ainat_compare_'. $admin['id'] .time());
        $data = [];
        $total = count($list);
        $page = 1;
        $size = 1000;
        $last_page = ceil(bcdiv($total, $size, 2)); //上取整
        foreach($list as $k => $v) {
            if($v[0] == null) {
                continue;
            }
            $data[] = [
                'sign' => $sign,
                'user_idcard' => $v[0],
                'natest_hours' => $v[1],
                'staff_classify_name' => $v[5],
                'user_name' => $v[6],
                'user_phone' => $v[7],
                'yw_street' => $v[8],
                'community' => $v[9],
                'company_name' => $v[10],
                'link_name' => $v[11],
                'link_phone' => $v[12],
                'gov' => $v[13],
                'gov_charge' => $v[14],
                'charge_phone' => $v[15],
                'admin_id' => $admin['id'],
                'admin_name' => $admin['real_name'],
                'total_num' => $total,
            ];
            try {
                if($page == $last_page) { //当前页是最后一页，一条条添加
                    $ainatCompareDao->saveAll($data);
                    $data = [];
                }else if(count($data) == $size) { //其它页每1000条添加一次
                    $ainatCompareDao->saveAll($data);
                    $data = [];
                    $page++;
                }
            } catch (\Exception $e) {
                $error_str .= $v[0];
            }
            if($error_str != '') {
                break;
            }
        }
        if($error_str == '') {
            return ['status'=> 1, 'msg'=> '导入成功', 'data'=> ['sign'=> $sign, 'total'=> $total]];
        }else{
            test_log('syncAinatCompareImport导入失败-'. $error_str);
            return ['status'=> 0, 'msg'=> '导入失败'];
        }
    }
}
