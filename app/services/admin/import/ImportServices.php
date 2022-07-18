<?php

namespace app\services\admin\import;

use app\dao\GateFactoryDao;
use \behavior\IdentityCardTool;
use app\services\admin\BaseServices;
use app\services\admin\common\StreetServices;
use crmeb\exceptions\AdminException;
use app\services\admin\system\admin\SystemAdminServices;
use app\services\admin\gov\GovServices;
use app\services\admin\sample\SampleOrganServices;
use app\services\admin\user\UserServices;
use app\services\admin\company\CompanyServices;
use app\services\admin\company\CompanyCategoryServices;
use app\services\admin\user\UserCategoryServices;
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
    public function readExcel($filePath, $dataService)
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

            return $this->$dataService($data);
        } catch (\Exception $e) {
            throw new AdminException($e->getMessage(), 400);
        }
    }

    //验证后台管理员数据
    public function placeVerify($data)
    {
        var_dump($data);
        // $adminService = app()->make(SystemAdminServices::class);
        // $govService = app()->make(GovServices::class);
        // $admin_data = [];
        // foreach($data as $k => $v) {
        //     if($v[0] == null) {
        //         continue;
        //     }
        //     $error = 0;
        //     $error_str = '';
        //     $admin = $adminService->getService(['account' => $v[1]]);
        //     if ($admin) {
        //         $error = 1;
        //         $error_str .= '手机号已存在;';
        //     }
        //     $gov = $govService->getService(['name'=> $v[2]]);
        //     if($gov == null) {
        //         $error = 1;
        //         $error_str .= '组织机构不存在;';
        //     }
        //     if($v[3] == '负责人') {
        //         $level = 2;
        //     }else if($v[3] == '干部') {
        //         $level = 3;
        //     }else{
        //         $level = 3;
        //         $error = 1;
        //         $error_str .= '干部类型只能是负责人或者干部;';
        //     }
        //     $admin_data[] = [
        //         'index' => ($k+1),
        //         'real_name' => $v[0],
        //         'phone' => $v[1],
        //         'gov_id' => $gov['id'],
        //         'gov_name' => $gov['name'],
        //         'level' => $level,
        //         'error' => $error,
        //         'error_str' => $error_str,
        //     ];
            
        // }
        // return ['status'=> 1, 'msg'=> '成功', 'data'=> $admin_data];
    }
    public function gateVerify($data)
    {

        $GateFactoryDao = app()->make(GateFactoryDao::class);

        $gate_data = [];
        $name_arr = [];
        $phone = [];

        $has_find_factory_map = [];
        
        foreach($data as $k => $v) {
            if($v[0] == null) {
                continue;
            }

            $error = 0;
            $error_str = '';

            if(!isset($has_find_factory_map[$v[0]])){
               $factory = $GateFactoryDao->get(['key'=>$v[0]]);
               if(!$factory){
                    $error = 1;
                    $error_str .= 'app_key错误;';
               }else{
                $has_find_factory_map[$v[0]] = $factory;
               }
            }

            if($v[1] == null) {
                $error = 1;
                $error_str .= '名称必填;';
            }
            if($v[2] == null) {
                $error = 1;
                $error_str .= '地址必填;';
            }
            if($v[3] == null) {
                $error = 1;
                $error_str .= '联系人必填;';
            }
            if($v[4] == null) {
                $error = 1;
                $error_str .= '联系电话必填;';
            }
            if(in_array($v[1], $name_arr)) {
                $error = 1;
                $error_str .= '名称重复;';
            }
            $card[] = $v[1];
            $phone[] = $v[2];
            $gate_data[] = [
                'index' => ($k+1),
                'app_key' => $v[0],
                'name' => $v[1],
                'addr' => $v[2],
                'link_man' => $v[3],
                'link_phone' => $v[4],
                'center' => $v[5],
                // 错误情况
                'error' => $error,
                'error_str' => $error_str,
            ];
        }
        return ['status'=> 1, 'msg'=> '成功', 'data'=> $gate_data];
    }
}
