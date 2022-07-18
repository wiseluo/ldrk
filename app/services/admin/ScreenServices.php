<?php
declare (strict_types=1);

namespace app\services\admin;

use app\services\user\BaseServices;
use app\dao\slave\OwnDeclareSlaveDao;
use app\dao\RiskDistrictDao;
use app\dao\DistrictDao;

class ScreenServices extends BaseServices
{
    public function declareDateService($param)
    {
        $data = [];
        $come_list = [];
        $leave_list = [];
        $riskarea_list = [];
        switch($param['date_type']) {
            case 'today' :
                $data['start_time'] = strtotime(date('Y-m-d') .' 00:00:00');
                $data['end_time'] = strtotime(date('Y-m-d') .' 23:59:59');
                $list = app()->make(OwnDeclareSlaveDao::class)->getScreenDeclareByHour($data);
                $h = date('h');
                $time_arr = [
                    '00','01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23'
                ];
                foreach($time_arr as $v) {
                    $come_item = [
                        'time'=> $v,
                        'declare_type'=> 'come',
                        'count'=> $v > $h ? null : 0,
                    ];
                    $leave_item = [
                        'time'=> $v,
                        'declare_type'=> 'leave',
                        'count'=> $v > $h ? null : 0,
                    ];
                    $riskarea_item = [
                        'time'=> $v,
                        'declare_type'=> 'riskarea',
                        'count'=> $v > $h ? null : 0,
                    ];
                    foreach($list as $n) {
                        if($v == $n['time'] && $n['declare_type'] == 'come') {
                            $come_item['count'] = $n['count'];
                        }
                        if($v == $n['time'] && $n['declare_type'] == 'leave') {
                            $leave_item['count'] = $n['count'];
                        }
                        if($v == $n['time'] && $n['declare_type'] == 'riskarea') {
                            $riskarea_item['count'] = $n['count'];
                        }
                    }
                    $come_list[] = $come_item;
                    $leave_list[] = $leave_item;
                    $riskarea_list[] = $riskarea_item;
                }
            break;
            case 'yesterday' :
                $data['start_time'] = strtotime(date('Y-m-d', strtotime('-1 day')));
                $data['end_time'] = strtotime(date('Y-m-d', strtotime('-1 day')) .' 23:59:59');
                $list = app()->make(OwnDeclareSlaveDao::class)->getScreenDeclareByHour($data);
                $time_arr = [
                    '00','01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23'
                ];
                foreach($time_arr as $v) {
                    $come_item = [
                        'time'=> $v,
                        'declare_type'=> 'come',
                        'count'=> 0,
                    ];
                    $leave_item = [
                        'time'=> $v,
                        'declare_type'=> 'leave',
                        'count'=> 0,
                    ];
                    $riskarea_item = [
                        'time'=> $v,
                        'declare_type'=> 'riskarea',
                        'count'=> 0,
                    ];
                    foreach($list as $n) {
                        if($v == $n['time'] && $n['declare_type'] == 'come') {
                            $come_item['count'] = $n['count'];
                        }
                        if($v == $n['time'] && $n['declare_type'] == 'leave') {
                            $leave_item['count'] = $n['count'];
                        }
                        if($v == $n['time'] && $n['declare_type'] == 'riskarea') {
                            $riskarea_item['count'] = $n['count'];
                        }
                    }
                    $come_list[] = $come_item;
                    $leave_list[] = $leave_item;
                    $riskarea_list[] = $riskarea_item;
                }
            break;
            case 'week' :
                $data['start_time'] = strtotime(date('Y-m-d', strtotime('-1 week +1 day')));
                $data['end_time'] = strtotime(date('Y-m-d H:i:s'));
                $list = app()->make(OwnDeclareSlaveDao::class)->getScreenDeclareByDate($data);
                $time_arr = [
                    date('m-d', strtotime('-6 day')),
                    date('m-d', strtotime('-5 day')),
                    date('m-d', strtotime('-4 day')),
                    date('m-d', strtotime('-3 day')),
                    date('m-d', strtotime('-2 day')),
                    date('m-d', strtotime('-1 day')),
                    date('m-d'),
                ];
                foreach($time_arr as $v) {
                    $come_item = [
                        'time'=> $v,
                        'declare_type'=> 'come',
                        'count'=> 0,
                    ];
                    $leave_item = [
                        'time'=> $v,
                        'declare_type'=> 'leave',
                        'count'=> 0,
                    ];
                    $riskarea_item = [
                        'time'=> $v,
                        'declare_type'=> 'riskarea',
                        'count'=> 0,
                    ];
                    foreach($list as $n) {
                        if($v == $n['time'] && $n['declare_type'] == 'come') {
                            $come_item['count'] = $n['count'];
                        }
                        if($v == $n['time'] && $n['declare_type'] == 'leave') {
                            $leave_item['count'] = $n['count'];
                        }
                        if($v == $n['time'] && $n['declare_type'] == 'riskarea') {
                            $riskarea_item['count'] = $n['count'];
                        }
                    }
                    $come_list[] = $come_item;
                    $leave_list[] = $leave_item;
                    $riskarea_list[] = $riskarea_item;
                }
            break;
        }
        return ['time_arr'=> $time_arr, 'come_list'=> $come_list, 'leave_list'=> $leave_list, 'riskarea_list'=> $riskarea_list];
    }

    public function sourceProvinceService()
    {
        $list = app()->make(OwnDeclareSlaveDao::class)->getScreenSourceProvince();
        $other = 0;
        $data = [];
        foreach($list as $k => $v) {
            if($k > 4) {
                $other += $v['count'];
            }else{
                $data[] = $v;
            }
        }
        if($other > 0) {
            $data[] = ['count'=> $other, 'province'=> '其他省'];
        }
        return $data;
    }

    public function floatingPopulationService()
    {
        return app()->make(OwnDeclareSlaveDao::class)->getFloatingPopulation();
    }
    
    public function riskareaComeService()
    {
        $list = app()->make(OwnDeclareSlaveDao::class)->getRiskareaCome();
        return ['total'=> count($list), 'data'=> $list];
    }

    public function backouttimeService(){
        $param = [];
        $list = app()->make(OwnDeclareSlaveDao::class)->backouttimeGroupByProvince();
        // var_dump($list);
        return ['total'=> count($list), 'data'=> $list];
    }

    public function riskareaProvinceService($type)
    {
        if($type == 'come') {
            $list = app()->make(OwnDeclareSlaveDao::class)->getRiskareaProvinceCome();
        }else{
            $list = app()->make(OwnDeclareSlaveDao::class)->getRiskareaProvinceLeave();
        }
        $data = [];
        foreach($list as $v) {
            $data[] = [['name'=> '义乌'], ['name'=> $v['province'], 'value'=> $v['count']]];
        }
        return $data;
    }

    public function comeYWStreetService()
    {
        $street_list = app()->make(DistrictDao::class)->getListByPid(2832); //义乌街道
        $riskarea_list = app()->make(OwnDeclareSlaveDao::class)->getRiskareaYWStreet();
        $come_list = app()->make(OwnDeclareSlaveDao::class)->getComeYWStreet();
        $list = [];
        foreach($street_list as $v) {
            $item = ['name'=> $v['name'], 'riskarea'=> 0, 'come'=> 0];
            foreach($riskarea_list as $m) {
                if($m['yw_street_id'] == $v['id']) {
                    $item['riskarea'] = $m['count'];
                }
            }
            foreach($come_list as $n) {
                if($n['yw_street_id'] == $v['id']) {
                    $item['come'] = $n['count'];
                }
            }
            $list[] = $item;
        }
        return $list;
    }

    public function getOwnDeclareNums(){
        return app()->make(OwnDeclareSlaveDao::class)->getOwnDeclareNums();
    }

    public function getControlNums(){
        return app()->make(OwnDeclareSlaveDao::class)->getControlNums();
    }

    public function getRiskarea(){
        $param = [];
        return app()->make(RiskDistrictDao::class)->getList($param);
    }

}
