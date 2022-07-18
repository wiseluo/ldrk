<?php

namespace app\controller\admin;


use crmeb\services\CacheService;
use think\Response;
use app\Request;
use app\model\Company;

class PublicController
{

    /**
     * 下载文件
     * @param string $key
     * @return Response|\think\response\File
     */
    public function download(string $key = '')
    {
        if (!$key) {
            return Response::create()->code(500);
        }
        $fileName = CacheService::get($key);
        if (is_array($fileName) && isset($fileName['path']) && isset($fileName['fileName']) && $fileName['path'] && $fileName['fileName'] && file_exists($fileName['path'])) {
            CacheService::delete($key);
            return download($fileName['path'], $fileName['fileName']);
        }
        return Response::create()->code(500);
    }
    
    //判断要下载的excel文件是否存在
    public function phpExcelDownload(Request $request)
    {
        $path = $request->param('path', '');
        $file_dir = app()->getRootPath() . 'public';
        //检查文件是否存在
        if ($path == '' || !file_exists($file_dir . $path)) {
            return show(400, '不存在', 0);
        }else{
            return show(200, '存在', 1);
        }
    }
    public function companyClassifyList(){
        // $hasCache = Cache::get('companyClassifyList');
        // if($hasCache){
        //     return  show(200, '成功cache', $hasCache);
        // }
        $data = Company::getCompanyClassifyList();
        $new_data = [];
        foreach($data as $key => $value){
            array_push($new_data,['name'=>$value,'key'=>$key]);
        }
        // Cache::set('companyClassifyList',$new_data,36000);
        return show(200, '成功', $new_data);
    }

}
