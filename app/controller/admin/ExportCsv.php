<?php
namespace app\controller\admin;

use crmeb\basic\BaseController;
use app\controller\admin\AuthController;
use app\services\admin\ExportCsvServices;
use think\facade\App;
use app\services\admin\ExportServices;
use crmeb\services\SwooleTaskService;
use think\facade\Config;
use think\facade\Cache;
use app\dao\slave\PlaceDeclareSlaveDao;
use app\services\admin\PlaceDeclareServices;

/**
 * 导出csv类
 * Class ExportExcel
 */
class ExportCsv extends AuthController
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

    public function owndeclare()
    {
        $uploads_dir = "uploads/".Config::get('upload.at_server')."/phpExcel/".Date('Ym');
        $file_dir = app()->getRootPath()."public/". $uploads_dir;
        if(!is_dir($file_dir)) {
            mkdir($file_dir, 0755, true);
        }

        $param = $this->request->param();
        $param['declare_type'] = $this->request->param('declare_type', '');
        if($param['declare_type'] == 'leave') {
            $filename = '外出申报_' . md5(time().__METHOD__);
            $filepath = $uploads_dir .'/'. $filename . '.csv';
            SwooleTaskService::csv()->taskType('csv')->data(['action'=>'leaveOwndeclareListExport','param'=>['request'=>$param, 'filepath'=> $filepath]])->push();
        }else if($param['declare_type'] == 'come') {
            $filename = '来返义申报_' . md5(time().__METHOD__);
            $filepath = $uploads_dir .'/'. $filename . '.csv';
            SwooleTaskService::csv()->taskType('csv')->data(['action'=>'comeOwndeclareListExport','param'=>['request'=>$param, 'filepath'=> $filepath]])->push();
        }else if($param['declare_type'] == 'riskarea') {
            $filename = '中高风险地区申报_' . md5(time().__METHOD__);
            $filepath = $uploads_dir .'/'. $filename . '.csv';
            SwooleTaskService::csv()->taskType('csv')->data(['action'=>'riskareaOwndeclareListExport','param'=>['request'=>$param, 'filepath'=> $filepath]])->push();
        }else if($param['declare_type'] == 'barrier') {
            $filename = '卡口申报_' . md5(time().__METHOD__);
            $filepath = $uploads_dir .'/'. $filename . '.csv';
            SwooleTaskService::csv()->taskType('csv')->data(['action'=>'barrierOwndeclareListExport','param'=>['request'=>$param, 'filepath'=> $filepath]])->push();
        }else if($param['declare_type'] == 'quarantine') {
            $filename = '防疫隔离申报_' . md5(time().__METHOD__);
            $filepath = $uploads_dir .'/'. $filename . '.csv';
            SwooleTaskService::csv()->taskType('csv')->data(['action'=>'quarantineOwndeclareListExport','param'=>['request'=>$param, 'filepath'=> $filepath]])->push();
        }else{
            return show(400, '类型错误');
        }
        $res['path'] = '/'.$filepath;
        return show(200, '成功', $res);
    }

    public function owndeclareRecentCome()
    {
        $uploads_dir = "uploads/".Config::get('upload.at_server')."/phpExcel/".Date('Ym');
        $file_dir = app()->getRootPath()."public/". $uploads_dir;
        if(!is_dir($file_dir)) {
            mkdir($file_dir, 0755, true);
        }

        $param = $this->request->param();
        $param['declare_type'] = 'leave';
        $filename = '最近返义人员列表_' . md5(time().__METHOD__);
        $filepath = $uploads_dir .'/'. $filename . '.csv';
        SwooleTaskService::csv()->taskType('csv')->data(['action'=>'owndeclareRecentComeExport','param'=>['request'=>$param, 'filepath'=> $filepath]])->push();
        $res['path'] = '/'.$filepath;
        return show(200, '成功', $res);
    }

    public function dataerrorLeave()
    {
        $uploads_dir = "uploads/".Config::get('upload.at_server')."/phpExcel/".Date('Ym');
        $file_dir = app()->getRootPath()."public/". $uploads_dir;
        if(!is_dir($file_dir)) {
            mkdir($file_dir, 0755, true);
        }

        $param = $this->request->param();
        $param['declare_type'] = 'leave';
        $filename = '外出申报异常_' . md5(time().__METHOD__);
        $filepath = $uploads_dir .'/'. $filename . '.csv';
        SwooleTaskService::csv()->taskType('csv')->data(['action'=>'dataerrorLeaveListExport','param'=>['request'=>$param, 'filepath'=> $filepath]])->push();
        $res['path'] = '/'.$filepath;
        return show(200, '成功', $res);
    }

    public function dataerrorCome()
    {
        $uploads_dir = "uploads/".Config::get('upload.at_server')."/phpExcel/".Date('Ym');
        $file_dir = app()->getRootPath()."public/". $uploads_dir;
        if(!is_dir($file_dir)) {
            mkdir($file_dir, 0755, true);
        }

        $param = $this->request->param();
        $param['declare_type'] = 'come';
        $filename = '来返义申报异常_' . md5(time().__METHOD__);
        $filepath = $uploads_dir .'/'. $filename . '.csv';
        SwooleTaskService::csv()->taskType('csv')->data(['action'=>'dataerrorComeListExport','param'=>['request'=>$param, 'filepath'=> $filepath]])->push();
        $res['path'] = '/'.$filepath;
        return show(200, '成功', $res);
    }

    public function dataerrorRiskarea()
    {
        $uploads_dir = "uploads/".Config::get('upload.at_server')."/phpExcel/".Date('Ym');
        $file_dir = app()->getRootPath()."public/". $uploads_dir;
        if(!is_dir($file_dir)) {
            mkdir($file_dir, 0755, true);
        }

        $param = $this->request->param();
        $param['declare_type'] = 'riskarea';
        $filename = '中高风险申报异常_' . md5(time().__METHOD__);
        $filepath = $uploads_dir .'/'. $filename . '.csv';
        SwooleTaskService::csv()->taskType('csv')->data(['action'=>'dataerrorRiskareaListExport','param'=>['request'=>$param, 'filepath'=> $filepath]])->push();
        $res['path'] = '/'.$filepath;
        return show(200, '成功', $res);
    }

    public function dataerrorTodayMany()
    {
        $uploads_dir = "uploads/".Config::get('upload.at_server')."/phpExcel/".Date('Ym');
        $file_dir = app()->getRootPath()."public/". $uploads_dir;
        if(!is_dir($file_dir)) {
            mkdir($file_dir, 0755, true);
        }

        $param = $this->request->param();
        $filename = '短期申报_' . md5(time().__METHOD__);
        $filepath = $uploads_dir .'/'. $filename . '.csv';
        SwooleTaskService::csv()->taskType('csv')->data(['action'=>'dataerrorTodayManyListExport','param'=>['request'=>$param, 'filepath'=> $filepath]])->push();
        $res['path'] = '/'.$filepath;
        return show(200, '成功', $res);
    }

    public function dataerrorOcr()
    {
        $uploads_dir = "uploads/".Config::get('upload.at_server')."/phpExcel/".Date('Ym');
        $file_dir = app()->getRootPath()."public/". $uploads_dir;
        if(!is_dir($file_dir)) {
            mkdir($file_dir, 0755, true);
        }

        $param = $this->request->param();
        $param['list_type'] = 'data_error';
        $filename = '行程码识别异常_' . md5(time().__METHOD__);
        $filepath = $uploads_dir .'/'. $filename . '.csv';
        SwooleTaskService::csv()->taskType('csv')->data(['action'=>'dataerrorOcrListExport','param'=>['request'=>$param, 'filepath'=> $filepath]])->push();
        $res['path'] = '/'.$filepath;
        return show(200, '成功', $res);
    }
    
    // public function dataerrorTravelAsterisk()
    // {
    //     $uploads_dir = "uploads/".Config::get('upload.at_server')."/phpExcel/".Date('Ym');
    //     $file_dir = app()->getRootPath()."public/". $uploads_dir;
    //     if(!is_dir($file_dir)) {
    //         mkdir($file_dir, 0755, true);
    //     }

    //     $param = $this->request->param();
    //     $param['list_type'] = 'data_asterisk';
    //     $filename = '行程码带星_' . md5(time().__METHOD__);
    //     $filepath = $uploads_dir .'/'. $filename . '.csv';
    //     SwooleTaskService::csv()->taskType('csv')->data(['action'=>'dataerrorTravelAsteriskListExport','param'=>['request'=>$param, 'filepath'=> $filepath]])->push();
    //     $res['path'] = '/'.$filepath;
    //     return show(200, '成功', $res);
    // }
    
    // public function dataerrorJkmmzt()
    // {
    //     $param = $this->request->param();
    //     $param['list_type'] = 'data_error'; 
    //     $param['page'] = 1;
    //     $param['size'] = 10000;
    //     $filename = '异常信息非绿码记录_' . date('YmdHis', time());
    //     SwooleTaskService::csv()->taskType('csv')->data(['action'=>'dataerrorJkmmztListExport','param'=>['request'=>$param, 'filename'=> $filename]])->push();
    //     $res['path'] = '/phpExcel/' . $filename . '.csv';
    //     return show(200, '成功', $res);
    // }
    
    public function datawarningBackouttime()
    {
        $uploads_dir = "uploads/".Config::get('upload.at_server')."/phpExcel/".Date('Ym');
        $file_dir = app()->getRootPath()."public/". $uploads_dir;
        if(!is_dir($file_dir)) {
            mkdir($file_dir, 0755, true);
        }

        $param = $this->request->param();
        $param['warning_type'] = 'backouttime';
        $filename = '未按时返义_' . md5(time().__METHOD__);
        $filepath = $uploads_dir .'/'. $filename . '.csv';
        SwooleTaskService::csv()->taskType('csv')->data(['action'=>'datawarningBackouttimeListExport','param'=>['request'=>$param, 'filepath'=> $filepath]])->push();
        $res['path'] = '/'.$filepath;
        return show(200, '成功', $res);
    }

    public function datawarningRiskarea()
    {
        $uploads_dir = "uploads/".Config::get('upload.at_server')."/phpExcel/".Date('Ym');
        $file_dir = app()->getRootPath()."public/". $uploads_dir;
        if(!is_dir($file_dir)) {
            mkdir($file_dir, 0755, true);
        }

        $param = $this->request->param();
        $param['warning_type'] = 'riskarea';
        $filename = '中高风险密接_' . md5(time().__METHOD__);
        $filepath = $uploads_dir .'/'. $filename . '.csv';
        SwooleTaskService::csv()->taskType('csv')->data(['action'=>'datawarningRiskareaListExport','param'=>['request'=>$param, 'filepath'=> $filepath]])->push();
        $res['path'] = '/'.$filepath;
        return show(200, '成功', $res);
    }
    
    public function userList()
    {
        $uploads_dir = "uploads/".Config::get('upload.at_server')."/phpExcel/".Date('Ym');
        $file_dir = app()->getRootPath()."public/". $uploads_dir;
        if(!is_dir($file_dir)) {
            mkdir($file_dir, 0755, true);
        }

        $param = $this->request->param();
        $filename = '人员信息_' . md5(time().__METHOD__);
        $filepath = $uploads_dir .'/'. $filename . '.csv';
        SwooleTaskService::csv()->taskType('csv')->data(['action'=>'userListExport','param'=>['request'=>$param, 'filepath'=> $filepath]])->push();
        $res['path'] = '/'.$filepath;
        return show(200, '成功', $res);
    }

    public function subChange()
    {
        $uploads_dir = "uploads/".Config::get('upload.at_server')."/phpExcel/".Date('Ym');
        $file_dir = app()->getRootPath()."public/". $uploads_dir;
        if(!is_dir($file_dir)) {
            mkdir($file_dir, 0755, true);
        }

        $param = $this->request->param();
        $filename = '最近返义人员列表_' . md5(time().__METHOD__);
        $filepath = $uploads_dir .'/'. $filename . '.csv';
        SwooleTaskService::csv()->taskType('csv')->data(['action'=>'subChangeExport','param'=>['request'=>$param, 'filepath'=> $filepath]])->push();
        $res['path'] = '/'.$filepath;
        return show(200, '成功', $res);
    }

    public function statisticFromwhere()
    {
        $uploads_dir = "uploads/".Config::get('upload.at_server')."/phpExcel/".Date('Ym');
        $file_dir = app()->getRootPath()."public/". $uploads_dir;
        if(!is_dir($file_dir)) {
            mkdir($file_dir, 0755, true);
        }

        $param = $this->request->param();
        $param['statistic_type'] = 'fromwhere';
        $filename = '来源地统计_' . md5(time().__METHOD__);
        $filepath = $uploads_dir .'/'. $filename . '.csv';
        SwooleTaskService::csv()->taskType('csv')->data(['action'=>'statisticFromwhereListExport','param'=>['request'=>$param, 'filepath'=> $filepath]])->push();
        $res['path'] = '/'.$filepath;
        return show(200, '成功', $res);
    }

    public function statisticYwstreet()
    {
        $uploads_dir = "uploads/".Config::get('upload.at_server')."/phpExcel/".Date('Ym');
        $file_dir = app()->getRootPath()."public/". $uploads_dir;
        if(!is_dir($file_dir)) {
            mkdir($file_dir, 0755, true);
        }

        $param = $this->request->param();
        $param['statistic_type'] = 'ywstreet';
        $filename = '来义街道统计_' . md5(time().__METHOD__);
        $filepath = $uploads_dir .'/'. $filename . '.csv';
        SwooleTaskService::csv()->taskType('csv')->data(['action'=>'statisticYwstreetListExport','param'=>['request'=>$param, 'filepath'=> $filepath]])->push();
        $res['path'] = '/'.$filepath;
        return show(200, '成功', $res);
    }

    public function statisticAge()
    {
        $uploads_dir = "uploads/".Config::get('upload.at_server')."/phpExcel/".Date('Ym');
        $file_dir = app()->getRootPath()."public/". $uploads_dir;
        if(!is_dir($file_dir)) {
            mkdir($file_dir, 0755, true);
        }

        $param = $this->request->param();
        $param['statistic_type'] = 'age';
        $filename = '年龄段统计_' . md5(time().__METHOD__);
        $filepath = $uploads_dir .'/'. $filename . '.csv';
        SwooleTaskService::csv()->taskType('csv')->data(['action'=>'statisticAgeListExport','param'=>['request'=>$param, 'filepath'=> $filepath]])->push();
        $res['path'] = '/'.$filepath;
        return show(200, '成功', $res);
    }

    public function unqualifiedCompany()
    {
        $uploads_dir = "uploads/".Config::get('upload.at_server')."/phpExcel/".Date('Ym');
        $file_dir = app()->getRootPath()."public/". $uploads_dir;
        if(!is_dir($file_dir)) {
            mkdir($file_dir, 0755, true);
        }

        $param = $this->request->param();
        $adminInfo = $this->request->adminInfo();

        $filename = '不合格企业_' . md5(time().__METHOD__);
        $filepath = $uploads_dir .'/'. $filename . '.csv';
        // 0的进度
        // $work_path = str_replace('.csv','.work',$filepath);
        // $workfp = fopen(public_path(). $work_path, 'w'); // w
        // fputcsv($workfp, [0,100]);
        // fclose($workfp);

        SwooleTaskService::csv()->taskType('csv')->data(['action'=>'unqualifiedCompanyExport','param'=>['request'=>$param, 'filepath'=> $filepath,'adminInfo'=> $adminInfo]])->push();
        $res['path'] = '/'.$filepath;
        return show(200, '成功', $res);

    }
    
    public function company()
    {
        $uploads_dir = "uploads/".Config::get('upload.at_server')."/phpExcel/".Date('Ym');
        $file_dir = app()->getRootPath()."public/". $uploads_dir;
        if(!is_dir($file_dir)) {
            mkdir($file_dir, 0755, true);
        }

        $param = $this->request->param();
        $adminInfo = $this->request->adminInfo();

        $filename = '企业列表_' . md5(time().__METHOD__);
        $filepath = $uploads_dir .'/'. $filename . '.csv';

        // 0的进度
        // $work_path = str_replace('.csv','.work',$filepath);
        // $workfp = fopen(public_path(). $work_path, 'w'); // w
        // fputcsv($workfp, [0,100]);
        // fclose($workfp);
        
        SwooleTaskService::csv()->taskType('csv')->data(['action'=>'companyExport','param'=>['request'=> $param, 'filepath'=> $filepath,'adminInfo'=> $adminInfo]])->push();
        $res['path'] = '/'.$filepath;
        return show(200, '成功', $res);
    }

    public function place()
    {
        $uploads_dir = "uploads/".Config::get('upload.at_server')."/phpExcel/".Date('Ym');
        $file_dir = app()->getRootPath()."public/". $uploads_dir;
        if(!is_dir($file_dir)) {
            mkdir($file_dir, 0755, true);
        }

        $param = $this->request->param();
        $filename = '场所码清册_' . md5(time().__METHOD__);
        $filepath = $uploads_dir .'/'. $filename . '.csv';
        
        SwooleTaskService::csv()->taskType('csv')->data(['action'=>'placeExport','param'=>['request'=>$param, 'filepath'=> $filepath]])->push();
        $res['path'] = '/'.$filepath;
        return show(200, '成功', $res);
    }

    public function placeDeclare()
    {
        $uploads_dir = "uploads/".Config::get('upload.at_server')."/phpExcel/".Date('Ym');
        $file_dir = app()->getRootPath()."public/". $uploads_dir;
        if(!is_dir($file_dir)) {
            mkdir($file_dir, 0755, true);
        }

        $param = $this->request->param();
        $param['list_type'] = $this->request->param('list_type', 'index');
        $param['size'] = 10;
        $dataList = app()->make(PlaceDeclareSlaveDao::class)->getList($param);
        if($dataList['total'] > 100000) {
            return show(400, '记录数超过10万，不能导出');
        }
        //记录导出日志
        app()->make(PlaceDeclareServices::class)->savePlaceDeclareLog($param, $this->request->adminInfo(), '导出');
        
        if($param['list_type'] == 'index') {
            $filename = '场所码扫码列表_' . md5(time().__METHOD__);
            $filepath = $uploads_dir .'/'. $filename . '.csv';
            SwooleTaskService::csv()->taskType('csv')->data(['action'=>'placeDeclareExport','param'=>['request'=>$param, 'filepath'=> $filepath]])->push();
        }else if($param['list_type'] == 'abnormal') {
            $filename = '行程及状态异常列表_' . md5(time().__METHOD__);
            $filepath = $uploads_dir .'/'. $filename . '.csv';
            SwooleTaskService::csv()->taskType('csv')->data(['action'=>'placeDeclareExport','param'=>['request'=>$param, 'filepath'=> $filepath]])->push();
        }else if($param['list_type'] == 'code') {
            $filename = '红黄码人员列表列表_' . md5(time().__METHOD__);
            $filepath = $uploads_dir .'/'. $filename . '.csv';
            SwooleTaskService::csv()->taskType('csv')->data(['action'=>'placeDeclareExport','param'=>['request'=>$param, 'filepath'=> $filepath]])->push();
        }else if($param['list_type'] == 'mail') {
            $filename = '国际邮件收件扫码列表_' . md5(time().__METHOD__);
            $filepath = $uploads_dir .'/'. $filename . '.csv';
            SwooleTaskService::csv()->taskType('csv')->data(['action'=>'placeDeclareExport','param'=>['request'=>$param, 'filepath'=> $filepath]])->push();
        }else if($param['list_type'] == 'high_speed') {
            $filename = '高速入口扫码列表_' . md5(time().__METHOD__);
            $filepath = $uploads_dir .'/'. $filename . '.csv';
            SwooleTaskService::csv()->taskType('csv')->data(['action'=>'placeDeclareExport','param'=>['request'=>$param, 'filepath'=> $filepath]])->push();
        }else if($param['list_type'] == 'hs_test') {
            $filename = '核酸检测登记扫码列表_' . md5(time().__METHOD__);
            $filepath = $uploads_dir .'/'. $filename . '.csv';
            SwooleTaskService::csv()->taskType('csv')->data(['action'=>'placeDeclareExport','param'=>['request'=>$param, 'filepath'=> $filepath]])->push();
        }else{
            return show(400, '类型错误');
        }
        $res['path'] = '/'.$filepath;
        return show(200, '成功', $res);
    }

    public function placeDeclareAbnormal()
    {
        $uploads_dir = "uploads/".Config::get('upload.at_server')."/phpExcel/".Date('Ym');
        $file_dir = app()->getRootPath()."public/". $uploads_dir;
        if(!is_dir($file_dir)) {
            mkdir($file_dir, 0755, true);
        }

        $param = $this->request->param();
        $filename = '场所码扫码异常列表_' . md5(time().__METHOD__);
        $filepath = $uploads_dir .'/'. $filename . '.csv';
        
        SwooleTaskService::csv()->taskType('csv')->data(['action'=>'placeDeclareAbnormalExport','param'=>['request'=>$param, 'filepath'=> $filepath]])->push();
        $res['path'] = '/'.$filepath;
        return show(200, '成功', $res);
    }

    public function placeDeclareCode()
    {
        $uploads_dir = "uploads/".Config::get('upload.at_server')."/phpExcel/".Date('Ym');
        $file_dir = app()->getRootPath()."public/". $uploads_dir;
        if(!is_dir($file_dir)) {
            mkdir($file_dir, 0755, true);
        }

        $param = $this->request->param();
        $filename = '红黄码人员列表_' . md5(time().__METHOD__);
        $filepath = $uploads_dir .'/'. $filename . '.csv';
        SwooleTaskService::csv()->taskType('csv')->data(['action'=>'placeDeclareCodeExport','param'=>['request'=>$param, 'filepath'=> $filepath]])->push();
        $res['path'] = '/'.$filepath;
        return show(200, '成功', $res);
    }

    public function todaySummary()
    {
        $uploads_dir = "uploads/".Config::get('upload.at_server')."/phpExcel/".Date('Ym');
        $file_dir = app()->getRootPath()."public/". $uploads_dir;
        if(!is_dir($file_dir)) {
            mkdir($file_dir, 0755, true);
        }

        $param = $this->request->param();
        $param['create_date'] = $this->request->param('create_date', date('Y-m-d',strtotime("-1 day")));
        $filename = date('m月d日') .'24小时场所码扫码次数_'. md5(time().__METHOD__);
        $filepath = $uploads_dir .'/'. $filename . '.csv';
        SwooleTaskService::csv()->taskType('csv')->data(['action'=>'todaySummaryExport','param'=>['request'=>$param, 'filepath'=> $filepath]])->push();
        $res['path'] = '/'.$filepath;
        return show(200, '成功', $res);
    }

    public function predayTotal()
    {
        $uploads_dir = "uploads/".Config::get('upload.at_server')."/phpExcel/".Date('Ym');
        $file_dir = app()->getRootPath()."public/". $uploads_dir;
        if(!is_dir($file_dir)) {
            mkdir($file_dir, 0755, true);
        }

        $param = $this->request->param();
        $param['create_date'] = $this->request->param('create_date', date('Y-m-d',strtotime("-1 day")));
        $filename = date('YmdHis') .'场所码每日数据_'. md5(time().__METHOD__);
        $filepath = $uploads_dir .'/'. $filename . '.csv';
        SwooleTaskService::csv()->taskType('csv')->data(['action'=>'predayTotalExport','param'=>['request'=>$param, 'filepath'=> $filepath]])->push();
        $res['path'] = '/'.$filepath;
        return show(200, '成功', $res);
    }

    public function beiyuanFushipin()
    {
        $uploads_dir = "uploads/".Config::get('upload.at_server')."/phpExcel/".Date('Ym');
        $file_dir = app()->getRootPath()."public/". $uploads_dir;
        if(!is_dir($file_dir)) {
            mkdir($file_dir, 0755, true);
        }

        $param = $this->request->param();
        $filename = '北苑街道副食品市场_'. md5(time().__METHOD__);
        $filepath = $uploads_dir .'/'. $filename . '.csv';
        SwooleTaskService::csv()->taskType('csv')->data(['action'=>'beiyuanFushipinExport','param'=>['request'=>$param, 'filepath'=> $filepath]])->push();
        $res['path'] = '/'.$filepath;
        return show(200, '成功', $res);
    }

    public function beiyuanGuopin()
    {
        $uploads_dir = "uploads/".Config::get('upload.at_server')."/phpExcel/".Date('Ym');
        $file_dir = app()->getRootPath()."public/". $uploads_dir;
        if(!is_dir($file_dir)) {
            mkdir($file_dir, 0755, true);
        }

        $param = $this->request->param();
        $filename = '北苑街道果品市场_'. md5(time().__METHOD__);
        $filepath = $uploads_dir .'/'. $filename . '.csv';
        SwooleTaskService::csv()->taskType('csv')->data(['action'=>'beiyuanGuopinExport','param'=>['request'=>$param, 'filepath'=> $filepath]])->push();
        $res['path'] = '/'.$filepath;
        return show(200, '成功', $res);
    }

    public function beiyuanShoucangpin()
    {
        $uploads_dir = "uploads/".Config::get('upload.at_server')."/phpExcel/".Date('Ym');
        $file_dir = app()->getRootPath()."public/". $uploads_dir;
        if(!is_dir($file_dir)) {
            mkdir($file_dir, 0755, true);
        }

        $param = $this->request->param();
        $filename = '北苑街道收藏品市场_'. md5(time().__METHOD__);
        $filepath = $uploads_dir .'/'. $filename . '.csv';
        SwooleTaskService::csv()->taskType('csv')->data(['action'=>'beiyuanShoucangpinExport','param'=>['request'=>$param, 'filepath'=> $filepath]])->push();
        $res['path'] = '/'.$filepath;
        return show(200, '成功', $res);
    }

    public function beiyuanWuzi()
    {
        $uploads_dir = "uploads/".Config::get('upload.at_server')."/phpExcel/".Date('Ym');
        $file_dir = app()->getRootPath()."public/". $uploads_dir;
        if(!is_dir($file_dir)) {
            mkdir($file_dir, 0755, true);
        }

        $param = $this->request->param();
        $filename = '北苑街道物资市场_'. md5(time().__METHOD__);
        $filepath = $uploads_dir .'/'. $filename . '.csv';
        SwooleTaskService::csv()->taskType('csv')->data(['action'=>'beiyuanWuziExport','param'=>['request'=>$param, 'filepath'=> $filepath]])->push();
        $res['path'] = '/'.$filepath;
        return show(200, '成功', $res);
    }

    public function chengxiLiangshi()
    {
        $uploads_dir = "uploads/".Config::get('upload.at_server')."/phpExcel/".Date('Ym');
        $file_dir = app()->getRootPath()."public/". $uploads_dir;
        if(!is_dir($file_dir)) {
            mkdir($file_dir, 0755, true);
        }

        $param = $this->request->param();
        $filename = '城西街道粮食市场_'. md5(time().__METHOD__);
        $filepath = $uploads_dir .'/'. $filename . '.csv';
        SwooleTaskService::csv()->taskType('csv')->data(['action'=>'chengxiLiangshiExport','param'=>['request'=>$param, 'filepath'=> $filepath]])->push();
        $res['path'] = '/'.$filepath;
        return show(200, '成功', $res);
    }

    public function chouchengJiadian()
    {
        $uploads_dir = "uploads/".Config::get('upload.at_server')."/phpExcel/".Date('Ym');
        $file_dir = app()->getRootPath()."public/". $uploads_dir;
        if(!is_dir($file_dir)) {
            mkdir($file_dir, 0755, true);
        }

        $param = $this->request->param();
        $filename = '稠城街道家电市场_'. md5(time().__METHOD__);
        $filepath = $uploads_dir .'/'. $filename . '.csv';
        SwooleTaskService::csv()->taskType('csv')->data(['action'=>'chouchengJiadianExport','param'=>['request'=>$param, 'filepath'=> $filepath]])->push();
        $res['path'] = '/'.$filepath;
        return show(200, '成功', $res);
    }

    public function choujiangJiaju()
    {
        $uploads_dir = "uploads/".Config::get('upload.at_server')."/phpExcel/".Date('Ym');
        $file_dir = app()->getRootPath()."public/". $uploads_dir;
        if(!is_dir($file_dir)) {
            mkdir($file_dir, 0755, true);
        }

        $param = $this->request->param();
        $filename = '稠江街道国际家居城或家具城_'. md5(time().__METHOD__);
        $filepath = $uploads_dir .'/'. $filename . '.csv';
        SwooleTaskService::csv()->taskType('csv')->data(['action'=>'choujiangJiajuExport','param'=>['request'=>$param, 'filepath'=> $filepath]])->push();
        $res['path'] = '/'.$filepath;
        return show(200, '成功', $res);
    }

    public function choujiangJiancai()
    {
        $uploads_dir = "uploads/".Config::get('upload.at_server')."/phpExcel/".Date('Ym');
        $file_dir = app()->getRootPath()."public/". $uploads_dir;
        if(!is_dir($file_dir)) {
            mkdir($file_dir, 0755, true);
        }

        $param = $this->request->param();
        $filename = '稠江街道建材市场_'. md5(time().__METHOD__);
        $filepath = $uploads_dir .'/'. $filename . '.csv';
        SwooleTaskService::csv()->taskType('csv')->data(['action'=>'choujiangJiancaiExport','param'=>['request'=>$param, 'filepath'=> $filepath]])->push();
        $res['path'] = '/'.$filepath;
        return show(200, '成功', $res);
    }

    public function fotangMucai()
    {
        $uploads_dir = "uploads/".Config::get('upload.at_server')."/phpExcel/".Date('Ym');
        $file_dir = app()->getRootPath()."public/". $uploads_dir;
        if(!is_dir($file_dir)) {
            mkdir($file_dir, 0755, true);
        }

        $param = $this->request->param();
        $filename = '佛堂镇木材市场_'. md5(time().__METHOD__);
        $filepath = $uploads_dir .'/'. $filename . '.csv';
        SwooleTaskService::csv()->taskType('csv')->data(['action'=>'fotangMucaiExport','param'=>['request'=>$param, 'filepath'=> $filepath]])->push();
        $res['path'] = '/'.$filepath;
        return show(200, '成功', $res);
    }

    public function fotangNongfuchanpin()
    {
        $uploads_dir = "uploads/".Config::get('upload.at_server')."/phpExcel/".Date('Ym');
        $file_dir = app()->getRootPath()."public/". $uploads_dir;
        if(!is_dir($file_dir)) {
            mkdir($file_dir, 0755, true);
        }

        $param = $this->request->param();
        $filename = '佛堂镇浙中农副产品物流中心_'. md5(time().__METHOD__);
        $filepath = $uploads_dir .'/'. $filename . '.csv';
        SwooleTaskService::csv()->taskType('csv')->data(['action'=>'fotangNongfuchanpinExport','param'=>['request'=>$param, 'filepath'=> $filepath]])->push();
        $res['path'] = '/'.$filepath;
        return show(200, '成功', $res);
    }

    public function houzhaiErshouche()
    {
        $uploads_dir = "uploads/".Config::get('upload.at_server')."/phpExcel/".Date('Ym');
        $file_dir = app()->getRootPath()."public/". $uploads_dir;
        if(!is_dir($file_dir)) {
            mkdir($file_dir, 0755, true);
        }

        $param = $this->request->param();
        $filename = '后宅街道二手车中心_'. md5(time().__METHOD__);
        $filepath = $uploads_dir .'/'. $filename . '.csv';
        SwooleTaskService::csv()->taskType('csv')->data(['action'=>'houzhaiErshoucheExport','param'=>['request'=>$param, 'filepath'=> $filepath]])->push();
        $res['path'] = '/'.$filepath;
        return show(200, '成功', $res);
    }

    public function shangxiMuju()
    {
        $uploads_dir = "uploads/".Config::get('upload.at_server')."/phpExcel/".Date('Ym');
        $file_dir = app()->getRootPath()."public/". $uploads_dir;
        if(!is_dir($file_dir)) {
            mkdir($file_dir, 0755, true);
        }

        $param = $this->request->param();
        $filename = '上溪镇模具城_'. md5(time().__METHOD__);
        $filepath = $uploads_dir .'/'. $filename . '.csv';
        SwooleTaskService::csv()->taskType('csv')->data(['action'=>'shangxiMujuExport','param'=>['request'=>$param, 'filepath'=> $filepath]])->push();
        $res['path'] = '/'.$filepath;
        return show(200, '成功', $res);
    }

    public function gateFactory()
    {
        $uploads_dir = "uploads/".Config::get('upload.at_server')."/phpExcel/".Date('Ym');
        $file_dir = app()->getRootPath()."public/". $uploads_dir;
        if(!is_dir($file_dir)) {
            mkdir($file_dir, 0755, true);
        }

        $param = $this->request->param();
        $filename = '闸机厂商列表_'. md5(time().__METHOD__);
        $filepath = $uploads_dir .'/'. $filename . '.csv';
        SwooleTaskService::csv()->taskType('csv')->data(['action'=>'gateFactoryExport','param'=>['request'=>$param, 'filepath'=> $filepath]])->push();
        $res['path'] = '/'.$filepath;
        return show(200, '成功', $res);
    }

    public function gate()
    {
        $uploads_dir = "uploads/".Config::get('upload.at_server')."/phpExcel/".Date('Ym');
        $file_dir = app()->getRootPath()."public/". $uploads_dir;
        if(!is_dir($file_dir)) {
            mkdir($file_dir, 0755, true);
        }

        $param = $this->request->param();
        $filename = '闸机列表_'. md5(time().__METHOD__);
        $filepath = $uploads_dir .'/'. $filename . '.csv';
        SwooleTaskService::csv()->taskType('csv')->data(['action'=>'gateExport','param'=>['request'=>$param, 'filepath'=> $filepath]])->push();
        $res['path'] = '/'.$filepath;
        return show(200, '成功', $res);
    }

    public function gateDeclare()
    {
        $uploads_dir = "uploads/".Config::get('upload.at_server')."/phpExcel/".Date('Ym');
        $file_dir = app()->getRootPath()."public/". $uploads_dir;
        if(!is_dir($file_dir)) {
            mkdir($file_dir, 0755, true);
        }

        $param = $this->request->param();
        $filename = '闸机通行记录列表_'. md5(time().__METHOD__);
        $filepath = $uploads_dir .'/'. $filename . '.csv';
        SwooleTaskService::csv()->taskType('csv')->data(['action'=>'gateDeclareExport','param'=>['request'=>$param, 'filepath'=> $filepath]])->push();
        $res['path'] = '/'.$filepath;
        return show(200, '成功', $res);
    }

    public function querycenterRygk()
    {
        $uploads_dir = "uploads/".Config::get('upload.at_server')."/phpExcel/".Date('Ym');
        $file_dir = app()->getRootPath()."public/". $uploads_dir;
        if(!is_dir($file_dir)) {
            mkdir($file_dir, 0755, true);
        }

        $param = $this->request->param();
        $filename = '人员管控状态列表_'. md5(time().__METHOD__);
        $filepath = $uploads_dir .'/'. $filename . '.csv';
        SwooleTaskService::csv()->taskType('csv')->data(['action'=>'querycenterRygkExport','param'=>['request'=>$param, 'filepath'=> $filepath]])->push();
        $res['path'] = '/'.$filepath;
        return show(200, '成功', $res);
    }

    public function personalCode()
    {
        $uploads_dir = "uploads/".Config::get('upload.at_server')."/phpExcel/".Date('Ym');
        $file_dir = app()->getRootPath()."public/". $uploads_dir;
        if(!is_dir($file_dir)) {
            mkdir($file_dir, 0755, true);
        }

        $param = $this->request->param();
        $filename = '个人防疫码列表_'. md5(time().__METHOD__);
        $filepath = $uploads_dir .'/'. $filename . '.csv';
        SwooleTaskService::csv()->taskType('csv')->data(['action'=>'personalCodeExport','param'=>['request'=>$param, 'filepath'=> $filepath]])->push();
        $res['path'] = '/'.$filepath;
        return show(200, '成功', $res);
    }

    public function getCsvProgress(){
        $param = $this->request->param();
        $path = trim($param['path'],'/');

        // 替换成work
        $workpath = str_replace('.csv','.work',$path);
        $path = public_path().$workpath;
        if(!file_exists($path)) {
            return  show(200, '成功', ['progress'=> '0' ]);
        }
        $file = fopen($path, 'r');
        $str = '';
        while (!feof($file)) 
        {     
            $str .=  fgetc($file);    
        }  
        fclose($file);
        if($str == ''){
            return  show(200, '无数据成功', ['progress'=> '100' ]);
        }
        $arr= \explode(',',$str);
        $cur = trim($arr[0]);
        $max = trim($arr[1]);

        if($max > 0){
            if($cur >= $max ){
                return  show(200, '成功', ['progress'=> '100' ]);
            }else{
                return  show(200, '成功', ['progress'=> bcdiv($cur*100,$max,2) ]);
            }
        }else{
            if( $cur >= $max ){
                return  show(200, '无数据成功', ['progress'=> '100' ]);
            }else{
                return  show(400, '错误');
            }
        }
    }
}
