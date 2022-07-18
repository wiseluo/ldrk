<?php
namespace app\controller\admin;

use app\controller\admin\AuthController;
use think\facade\App;
use app\services\admin\ExportServices;
use crmeb\services\SwooleTaskService;
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

    public function owndeclare()
    {
        $param = $this->request->param();
        $param['list_type'] = $this->request->param('list_type', '');
        $param['week_cycle_no'] = $this->request->param('week_cycle_no', '');
        $param['report_result'] = $this->request->param('report_result', '');
        $param['page'] = 1;
        $param['size'] = 10000;
        $filename = '';
        if($param['list_type'] == 'leave') {
            $filename = '外出申报_' . date('YmdHis', time());
            SwooleTaskService::export()->taskType('export')->data(['action'=>'owndeclareListData','param'=>['request'=>$param, 'export_fun'=> 'leaveOwndeclareListExport', 'filename'=> $filename]])->push();
        }else if($param['list_type'] == 'come') {
            $filename = '来返义申报_' . date('YmdHis', time());
            SwooleTaskService::export()->taskType('export')->data(['action'=>'owndeclareListData','param'=>['request'=>$param, 'export_fun'=> 'comeOwndeclareListExport', 'filename'=> $filename]])->push();
        }else if($param['list_type'] == 'riskarea') {
            $filename = '中高风险地区申报_' . date('YmdHis', time());
            SwooleTaskService::export()->taskType('export')->data(['action'=>'owndeclareListData','param'=>['request'=>$param, 'export_fun'=> 'riskareaOwndeclareListExport', 'filename'=> $filename]])->push();
        }else if($param['list_type'] == 'barrier') {
            $filename = '卡口申报_' . date('YmdHis', time());
            SwooleTaskService::export()->taskType('export')->data(['action'=>'owndeclareListData','param'=>['request'=>$param, 'export_fun'=> 'barrierOwndeclareListExport', 'filename'=> $filename]])->push();
        }else if($param['list_type'] == 'quarantine') {
            $filename = '防疫隔离申报_' . date('YmdHis', time());
            SwooleTaskService::export()->taskType('export')->data(['action'=>'owndeclareListData','param'=>['request'=>$param, 'export_fun'=> 'quarantineOwndeclareListExport', 'filename'=> $filename]])->push();
        }
        $res['path'] = '/phpExcel/' . $filename . '.xlsx';
        return show(200, '成功', $res);
    }

    public function dataerrorLeave()
    {
        $param = $this->request->param();
        $param['declare_type'] = 'leave';
        $param['page'] = 1;
        $param['size'] = 10000;
        $filename = '异常信息外出申报异常_' . date('YmdHis', time());
        SwooleTaskService::export()->taskType('export')->data(['action'=>'dataerrorListData','param'=>['request'=>$param, 'export_fun'=> 'dataerrorLeaveListExport', 'filename'=> $filename]])->push();
        $res['path'] = '/phpExcel/' . $filename . '.xlsx';
        return show(200, '成功', $res);
    }

    public function dataerrorCome()
    {
        $param = $this->request->param();
        $param['declare_type'] = 'come';
        $param['page'] = 1;
        $param['size'] = 10000;
        $filename = '异常信息来返义申报异常_' . date('YmdHis', time());
        SwooleTaskService::export()->taskType('export')->data(['action'=>'dataerrorListData','param'=>['request'=>$param, 'export_fun'=> 'dataerrorComeListExport', 'filename'=> $filename]])->push();
        $res['path'] = '/phpExcel/' . $filename . '.xlsx';
        return show(200, '成功', $res);
    }

    public function dataerrorRiskarea()
    {
        $param = $this->request->param();
        $param['declare_type'] = 'riskarea';
        $param['page'] = 1;
        $param['size'] = 10000;
        $filename = '异常信息中高风险申报异常_' . date('YmdHis', time());
        SwooleTaskService::export()->taskType('export')->data(['action'=>'dataerrorListData','param'=>['request'=>$param, 'export_fun'=> 'dataerrorRiskareaListExport', 'filename'=> $filename]])->push();
        $res['path'] = '/phpExcel/' . $filename . '.xlsx';
        return show(200, '成功', $res);
    }

    public function dataerrorTodayMany()
    {
        $param = $this->request->param();
        $filename = '异常信息短期申报_' . date('YmdHis', time());
        SwooleTaskService::export()->taskType('export')->data(['action'=>'dataerrorTodayManyListExport','param'=>['request'=>$param, 'filename'=> $filename]])->push();
        $res['path'] = '/phpExcel/' . $filename . '.xlsx';
        return show(200, '成功', $res);
    }

    public function dataerrorOcr()
    {
        $param = $this->request->param();
        $param['list_type'] = 'data_error'; 
        $param['page'] = 1;
        $param['size'] = 10000;
        $filename = '异常信息行程码识别异常_' . date('YmdHis', time());
        SwooleTaskService::export()->taskType('export')->data(['action'=>'dataerrorOcrListExport','param'=>['request'=>$param, 'filename'=> $filename]])->push();
        $res['path'] = '/phpExcel/' . $filename . '.xlsx';
        return show(200, '成功', $res);
    }
    
    public function dataerrorTravelAsterisk()
    {
        $param = $this->request->param();
        $param['list_type'] = 'data_error'; 
        $param['page'] = 1;
        $param['size'] = 10000;
        $filename = '异常信息行程码带星_' . date('YmdHis', time());
        SwooleTaskService::export()->taskType('export')->data(['action'=>'dataerrorTravelAsteriskListExport','param'=>['request'=>$param, 'filename'=> $filename]])->push();
        $res['path'] = '/phpExcel/' . $filename . '.xlsx';
        return show(200, '成功', $res);
    }
    
    public function dataerrorJkmmzt()
    {
        $param = $this->request->param();
        $param['list_type'] = 'data_error'; 
        $param['page'] = 1;
        $param['size'] = 10000;
        $filename = '异常信息非绿码记录_' . date('YmdHis', time());
        SwooleTaskService::export()->taskType('export')->data(['action'=>'dataerrorJkmmztListExport','param'=>['request'=>$param, 'filename'=> $filename]])->push();
        $res['path'] = '/phpExcel/' . $filename . '.xlsx';
        return show(200, '成功', $res);
    }
    
    public function datawarningBackouttime()
    {
        $param = $this->request->param();
        $param['warning_type'] = 'backouttime';
        $param['page'] = 1;
        $param['size'] = 10000;
        $filename = '未按时返义预警信息_' . date('YmdHis', time());
        SwooleTaskService::export()->taskType('export')->data(['action'=>'datawarningListData','param'=>['request'=>$param, 'export_fun'=> 'datawarningBackouttimeListExport', 'filename'=> $filename]])->push();
        $res['path'] = '/phpExcel/' . $filename . '.xlsx';
        return show(200, '成功', $res);
    }

    public function datawarningRiskarea()
    {
        $param = $this->request->param();
        $param['warning_type'] = 'riskarea';
        $param['page'] = 1;
        $param['size'] = 10000;
        $filename = '中高风险密接预警信息_' . date('YmdHis', time());
        SwooleTaskService::export()->taskType('export')->data(['action'=>'datawarningListData','param'=>['request'=>$param, 'export_fun'=> 'datawarningRiskareaListExport', 'filename'=> $filename]])->push();
        $res['path'] = '/phpExcel/' . $filename . '.xlsx';
        return show(200, '成功', $res);
    }
    
    public function userList()
    {
        return show(400, '数据量较大，暂时不支持导出，等后端数据源改为从库后，方可支持导出');
        $param = $this->request->param();
        $param['user_type'] = $this->request->param('user_type', '');
        $param['status'] = $this->request->param('status', '');
        $param['keyword'] = $this->request->param('keyword', '');
        $param['declare_type'] = $this->request->param('declare_type', '');
        $param['position_type'] = $this->request->param('position_type', '');
        $param['page'] = 1;
        $param['size'] = 10000;
        $filename = '人员信息_' . date('YmdHis', time());
        SwooleTaskService::export()->taskType('export')->data(['action'=>'userListExport','param'=>['request'=>$param, 'filename'=> $filename]])->push();
        $res['path'] = '/phpExcel/' . $filename . '.xlsx';
        return show(200, '成功', $res);
    }

    public function statisticFromwhere()
    {
        $param = $this->request->param();
        $param['statistic_type'] = 'fromwhere';
        $param['page'] = 1;
        $param['size'] = 10000;
        $filename = '来源地统计_' . date('YmdHis', time());
        SwooleTaskService::export()->taskType('export')->data(['action'=>'statisticListData','param'=>['request'=>$param, 'export_fun'=> 'statisticFromwhereListExport', 'filename'=> $filename]])->push();
        $res['path'] = '/phpExcel/' . $filename . '.xlsx';
        return show(200, '成功', $res);
    }

    public function statisticYwstreet()
    {
        $param = $this->request->param();
        $param['statistic_type'] = 'ywstreet';
        $param['page'] = 1;
        $param['size'] = 10000;
        $filename = '来义街道统计_' . date('YmdHis', time());
        SwooleTaskService::export()->taskType('export')->data(['action'=>'statisticListData','param'=>['request'=>$param, 'export_fun'=> 'statisticYwstreetListExport', 'filename'=> $filename]])->push();
        $res['path'] = '/phpExcel/' . $filename . '.xlsx';
        return show(200, '成功', $res);
    }

    public function statisticAge()
    {
        $param = $this->request->param();
        $param['statistic_type'] = 'age';
        $param['page'] = 1;
        $param['size'] = 10000;
        $filename = '年龄段统计_' . date('YmdHis', time());
        SwooleTaskService::export()->taskType('export')->data(['action'=>'statisticListData','param'=>['request'=>$param, 'export_fun'=> 'statisticAgeListExport', 'filename'=> $filename]])->push();
        $res['path'] = '/phpExcel/' . $filename . '.xlsx';
        return show(200, '成功', $res);
    }

    public function unqualifiedCompany()
    {
        $param = $this->request->param();
        $param['list_type'] = 'data_error'; 
        $param['page'] = 1;
        $param['size'] = 10000;
        $filename = '不合格企业_' . date('YmdHis', time());
        SwooleTaskService::export()->taskType('export')->data(['action'=>'unqualifiedCompanyExport','param'=>['request'=>$param, 'filename'=> $filename]])->push();
        $res['path'] = '/phpExcel/' . $filename . '.xlsx';
        return show(200, '成功', $res);
    }
}
