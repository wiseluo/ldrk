<?php
namespace app\controller\ainat;

use think\facade\App;
use app\controller\ainat\AuthController;
use app\services\ainat\ImportServices;
use crmeb\services\SwooleTaskService;

/**
 * 导入excel类
 * Class ExportExcel
 */
class ImportExcel extends AuthController
{
    /**
     * @var ImportServices
     */
    protected $service;

    /**
     * ExportExcel constructor.
     * @param App $app
     * @param ImportServices $services
     */
    public function __construct(App $app, ImportServices $services)
    {
        parent::__construct($app);
        $this->service = $services;
    }

    public function natCompare()
    {
        $path = $this->request->param('path', '');
        if(!$path) {
            return show(400, '文件路径必填');
        }
        $filePath = public_path() . $path;
        $admin = $this->adminInfo;
        //$sign = md5('ainat_compare_'. $admin['id'] .time());
        $res = $this->service->readExcel($filePath, 'natCompare', $admin);
        if($res['status'] == 1) {
            // $size = 200;
            // $last_page = ceil(bcdiv($res['data']['total'], $size, 2)); //上取整
            // for($i=1; $i<=$last_page; $i++) { //批量生成一批异步任务，同时处理比对数据
            //     SwooleTaskService::ainat()->taskType('ainat')->data(['action'=> 'syncAinatCompare', 'param'=> ['sign'=> $res['data']['sign'], 'page'=> $i, 'size'=> $size]])->push();
            // }
            return show(200, '处理中，请稍等', $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }
}
