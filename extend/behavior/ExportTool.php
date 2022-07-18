<?php

namespace behavior;

//导出工具类
class ExportTool
{
    /**
   * params $headerList 头部列表信息(一维数组) 必传
   * params $data 导出的数据(二维数组)  必传
   * params $filename 文件名称转码 必传
   * params $tmp 备用信息(二维数组) 选传
   * PS:出现数字格式化情况，可添加看不见的符号，使其正常，如:"\t"
   **/
    public function exportToCsv($headerList = [] , $data = [] , $fileName = '' , $tmp = [])
    {
        $fp = fopen(public_path(). 'phpExcel/'. $fileName . '.csv', 'w');
        //使用fputcsv将数据写入文件句柄
        fputcsv($fp, $tmp);
        //使用fputcsv将数据写入文件句柄
        fputcsv($fp, $headerList);
        //计数器
        //$num = 0;
        //每隔$limit行，刷新一下输出buffer,不要太大亦不要太小
        //$limit = 100000;
        //逐行去除数据,不浪费内存
        $count = count($data);
        for($i = 0 ; $i < $count ; $i++){
            //$num++;
            //刷新一下输出buffer，防止由于数据过多造成问题
            // if($limit == $num) {
            //     ob_flush();
            //     flush();
            //     $num = 0;
            // }
            fputcsv($fp, $data[$i]);
        }
        fclose($fp);
    }
}
