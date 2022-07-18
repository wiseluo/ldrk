<?php
namespace app\controller\admin\import;

use think\facade\App;
use app\controller\admin\AuthController;
use app\services\admin\import\ImportServices;

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

    public function placeVerify()
    {
        $path = $this->request->param('path', '');
        if(!$path) {
            return show(400, '文件路径必填');
        }
        $filePath = public_path() . $path;
        $res = $this->service->readExcel($filePath, 'placeVerify');
        if($res['status']) {
            return show(200, $res['msg'], $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }
    public function gateVerify()
    {
        $path = $this->request->param('path', '');
        if(!$path) {
            return show(400, '文件路径必填');
        }
        $filePath = public_path() . $path;
        $res = $this->service->readExcel($filePath, 'gateVerify');
        if($res['status']) {
            return show(200, $res['msg'], $res['data']);
        }else{
            return show(400, $res['msg']);
        }
    }

}
