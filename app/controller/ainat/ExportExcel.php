<?php
namespace app\controller\ainat;

use think\facade\App;
use app\controller\ainat\AuthController;
use app\services\ainat\ExportServices;
use crmeb\services\SwooleTaskService;
use think\facade\Config;

/**
 * 导出excel类
 * Class ExportExcel
 */
class ExportExcel extends AuthController
{
    /**
     * @var ExportServices
     */
    protected $service;

    /**
     * ExportExcel constructor.
     * @param App $app
     * @param ExportServices $services
     */
    public function __construct(App $app, ExportServices $services)
    {
        parent::__construct($app);
        $this->service = $services;
    }

    public function natCompare()
    {
        $uploads_dir = "uploads/".Config::get('upload.at_server')."/phpExcel/".Date('Ym');
        $file_dir = app()->getRootPath()."public/". $uploads_dir;
        if(!is_dir($file_dir)) {
            mkdir($file_dir, 0755, true);
        }
        $param = $this->request->param();
        $param['sign'] = $this->request->param('sign', '');
        $param['order'] = $this->request->param('order', '');
        $param['sort'] = $this->request->param('sort', 'asc');
        
        $filename = 'AI核酸比对列表_' . date('YmdHis', time());
        SwooleTaskService::export()->taskType('ainat')->data(['action'=>'ainatCompareListExportTask','param'=>['request'=>$param, 'filename'=> $filename]])->push();
        $res['path'] = '/'. $uploads_dir .'/'. $filename . '.xlsx';
        return show(200, '成功', $res);
    }

}
