<?php

declare (strict_types=1);

namespace app\dao;

use app\dao\BaseDao;
use app\model\PlaceDeclare;
use think\facade\Db;
use app\dao\PlaceDeclareNodeDao;

class PlaceDeclareDao extends BaseDao
{
    
    protected function setModel(): string
    {
        return PlaceDeclare::class;
    }

    public function getList($param)
    {
        $whereclose = null;
        $where = $this->_param_where($param, $whereclose);
        if(isset($param['node_id']) && $param['node_id'] > 0) {
            $node = app()->make(PlaceDeclareNodeDao::class)->get($param['node_id']);
            return $this->getModel()::suffix($node['suffix'])
                ->where($where)
                ->where($whereclose)
                ->append(['xcm_result_text'])
                ->order('id desc')
                ->paginate($param['size'])
                ->toArray();
        }else{
            return $this->getModel()::where($where)
                ->where($whereclose)
                ->append(['xcm_result_text'])
                ->order('id desc')
                ->paginate($param['size'])
                ->toArray();
        }
    }

    private function _param_where($param,&$whereclose)
    {
        $where = [];
        //分段导出
        if(isset($param['_where_id_lg']) && $param['_where_id_lg'] > 0) {
            $where[] = ['id', '<', $param['_where_id_lg']];
        }
        if( isset($param['place_code']) && $param['place_code'] != '') {
            $where[] = ['place_code', '=', $param['place_code']];
        }
        if( isset($param['place_name']) && $param['place_name'] != '') {
            $where[] = ['place_name', '=', $param['place_name']];
        }
        if( isset($param['place_short_name']) && $param['place_short_name'] != '') {
            $where[] = ['place_short_name', '=', $param['place_short_name']];
        }
        if( isset($param['link_man']) && $param['link_man'] != '') {
            $where[] = ['link_man', '=', $param['link_man']];
        }
        if( isset($param['link_phone']) && $param['link_phone'] != '') {
            $where[] = ['link_phone', '=', $param['link_phone']];
        }
        if( isset($param['real_name']) && $param['real_name'] != '') {
            $where[] = ['real_name', '=', $param['real_name']];
        }
        if( isset($param['id_card']) && $param['id_card'] != '') {
            $where[] = ['id_card', '=', $param['id_card']];
        }
        if( isset($param['phone']) && $param['phone'] != '') {
            $where[] = ['phone', '=', $param['phone']];
        }
        if( isset($param['vaccination']) && $param['vaccination'] != '') {
            $where[] = ['vaccination', '=', $param['vaccination']];
        }
        if( isset($param['yw_street']) && $param['yw_street'] != '') {
            $where[] = ['yw_street', '=', $param['yw_street']];
        }
        if( isset($param['yw_street_id']) && $param['yw_street_id']  > 0) {
            $where[] = ['yw_street_id', '=', $param['yw_street_id']];
        }
        if( isset($param['jkm_mzt']) && $param['jkm_mzt'] != '') {
            $where[] = ['jkm_mzt', '=', $param['jkm_mzt']];
        }
        if( isset($param['hsjc_result']) && $param['hsjc_result'] != '') {
            $where[] = ['hsjc_result', '=', $param['hsjc_result']];
        }
        if( isset($param['xcm_result']) && $param['xcm_result'] != '') {
            $where[] = ['xcm_result', '=', $param['xcm_result']];
        }

        if( isset($param['start_datetime']) && $param['start_datetime'] != '' ) {
			$whereclose = function($query) use ($param){
                $start_date = Date('Y-m-d',strtotime($param['start_datetime']));
                $end_date = Date('Y-m-d',strtotime($param['end_datetime']));
                $query->whereTime('create_date','between',[$start_date, $end_date]);
				$query->whereTime('create_datetime','between',[$param['start_datetime'], $param['end_datetime']]);
			};
        }else{
            if( isset($param['start_date']) && $param['start_date']) {
                $whereclose = function($query) use ($param){
                    $query->whereTime('create_date','between',[$param['start_date'], $param['end_date']]);
                };
            }
        }
        if( isset($param['ryxx_result']) && $param['ryxx_result']  != '') {
            $where[] = ['ryxx_result', '=', $param['ryxx_result']];
        }
        // if( isset($param['end_date']) && $param['end_date']) {
        //     $where[] = ['create_time', '<=', strtotime($param['end_date'].' 23:59:59') ];
        // }
        if(isset($param['list_type']) && $param['list_type'] != '') {
            switch($param['list_type']) {
                case 'abnormal':
                    $where[] = ['ryxx_result', 'in', ['行程卡带星人员','红码人员','黄码人员','2+14暂赋黄码','外地返义后未做核酸','居家健康观察','居家隔离','日常健康监测','未按规定检测核酸人员','集中医学观察','集中隔离']];
                    break;
                case 'code':
                    $where[] = ['jkm_mzt', '<>', '绿码'];
                    break;
                case 'mail':
                    $where[] = ['code', '=', ''];
                    break;
                case 'high_speed':
                    $where[] = ['code', '=', ''];
                    break;
                case 'hs_test':
                    $where[] = ['code', '=', ''];
                    break;
            }
        }
        return $where;
    }

    public function getAbnormalList($param)
    {
        $whereclose = null;
        $where = $this->_param_where($param,$whereclose);
        // $where[] = ['ryxx_result', '<>', ''];
        $where[] = ['ryxx_result', 'in', ['行程卡带星人员','红码人员','黄码人员','2+14暂赋黄码','外地返义后未做核酸','居家健康观察','居家隔离','日常健康监测','未按规定检测核酸人员','集中医学观察','集中隔离']];
        
        return $this->getModel()::where($where)
                ->where($whereclose)
                ->append(['xcm_result_text'])
                ->order('id desc')
                ->paginate($param['size'])
                ->toArray();
    }

    public function getCodeList($param)
    {
        $whereclose = null;
        $where = $this->_param_where($param,$whereclose);
        $where[] = ['jkm_mzt', '<>', '绿码'];
        
        return $this->getModel()::where($where)
                ->where($whereclose)
                ->append(['xcm_result_text'])
                ->order('id desc')
                ->paginate($param['size'])
                ->toArray();
    }

    public function getTodayScanNum($place_code)
    {
        $where = [];
        $where[] = ['place_code', '=', $place_code];
        $where[] = ['create_time', '>', strtotime(date('Y-m-d'))];
        return $this->getModel()::where($where)->count('id');
    }

    public function getYesterdayScanNum($place_code)
    {
        $where = [];
        $where[] = ['place_code', '=', $place_code];
        $where[] = ['date', '=', Date('Y-m-d',strtotime(' -1 day'))];
        return Db::connect('mysql_slave')->name('place_declare_date_nums')->where($where)->value('total_nums');
    }


}
