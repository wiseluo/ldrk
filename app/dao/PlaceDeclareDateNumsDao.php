<?php

declare (strict_types=1);

namespace app\dao;

use app\dao\BaseDao;
use app\model\PlaceDeclareDateNums;
use think\facade\Db;

class PlaceDeclareDateNumsDao extends BaseDao
{
    
    protected function setModel(): string
    {
        return PlaceDeclareDateNums::class;
    }

    public function getPlaceClassifyDateNums(){
        $date_arr = [];
        for($i=14;$i>0;$i--){
            $date_arr[] = Date('Y-m-d', strtotime(' -'.$i.' day'));
        }
        $data = $this->_getPlaceDateNumsGroup('place_classify',$date_arr);
        $data_map = [];
        foreach($data as $key => $value){
            $data_map[$value['date'].'_'.$value['title']] = $value;
        }
        $return_data = $this->_buildData('place_classify',$data_map,$date_arr);
        return $return_data;
    }

    public function getPlaceTypeDateNums(){
        $date_arr = [];
        for($i=14;$i>0;$i--){
            $date_arr[] = Date('Y-m-d', strtotime(' -'.$i.' day'));
        }
        $data = $this->_getPlaceDateNumsGroup('place_type',$date_arr);
        $data_map = [];
        foreach($data as $key => $value){
            $data_map[$value['date'].'_'.$value['title']] = $value;
        }
        $return_data = $this->_buildData('place_type',$data_map,$date_arr);
        return $return_data;
    }
    
    public function getPlaceStreetDateNums(){
        $date_arr = [];
        for($i=14;$i>0;$i--){
            $date_arr[] = Date('Y-m-d', strtotime(' -'.$i.' day'));
        }
        $data = $this->_getPlaceDateNumsGroup('yw_street',$date_arr);
        $data_map = [];
        foreach($data as $key => $value){
            $data_map[$value['date'].'_'.$value['title']] = $value;
        }
        $return_data = $this->_buildData('yw_street',$data_map,$date_arr);
        return $return_data;
    }

    public function getPlaceDateNumsByName($name=''){
        $date_arr = [];
        for($i=14;$i>0;$i--){
            $date_arr[] = Date('Y-m-d', strtotime(' -'.$i.' day'));
        }
        $group_by = 'date'; //
        $data = $this->_getPlaceDateNumsGroup($group_by,$date_arr);
        $data_map = [];
        foreach($data as $key => $value){
            $data_map[$value['date']] = $value;
        }
        return $this->_buildData($name,$data_map,$date_arr,'date');
    }


    private function _getPlaceDateNumsGroup($group_by='place_classify',$date_arr=[]){
        if(count($date_arr) == 0){
            for($i=14;$i>0;$i--){
                $date_arr[] = Date('Y-m-d', strtotime(' -'.$i.' day'));
            }
        }
        $where[] = ['date','in',$date_arr];
        if($group_by == 'date'){
            $data = Db::connect('mysql_slave')->name('place_declare_date_nums_group')->alias('ddn')
                ->field('
                    ddn.date,
                    ddn.title,
                    sum(total_nums) as total_nums,
                    sum(jkm_green_nums) as jkm_green_nums,
                    sum(jkm_yellow_nums) as jkm_yellow_nums,
                    sum(jkm_red_nums) as jkm_red_nums,
                    sum(jkm_unknow_nums) as jkm_unknow_nums,
                    sum(xcm_green_nums) as xcm_green_nums,
                    sum(xcm_yellow_nums) as xcm_yellow_nums,
                    sum(xcm_red_nums) as xcm_red_nums,
                    sum(xcm_unknow_nums) as xcm_unknow_nums,
                    sum(vaccination_0_nums) as vaccination_0_nums,
                    sum(vaccination_1_nums) as vaccination_1_nums,
                    sum(vaccination_2_nums) as vaccination_2_nums,
                    sum(vaccination_3_nums) as vaccination_3_nums,
                    sum(ryxx_empty_nums) as ryxx_empty_nums,
                    sum(ryxx_notempty_nums) as ryxx_notempty_nums,
                    sum(hsjc_1_day_nums) as hsjc_1_day_nums,
                    sum(hsjc_2_day_nums) as hsjc_2_day_nums,
                    sum(hsjc_3_day_nums) as hsjc_3_day_nums,
                    sum(hsjc_7_day_nums) as hsjc_7_day_nums,
                    sum(hsjc_14_day_nums) as hsjc_14_day_nums,
                    sum(hsjc_outer_14_day_nums) as hsjc_outer_14_day_nums,
                    sum(hsjc_unknow_nums) as hsjc_unknow_nums
                ')
                ->where($where)
                ->where('group_by','=','yw_street')
                ->group('date')
                ->select()
                ->toArray();
        }else{
            $where[] = ['group_by','=',$group_by];
            $data = Db::connect('mysql_slave')->name('place_declare_date_nums_group')->alias('ddn')
                // ->field('*')
                ->where($where)
                ->select()
                ->toArray();
        }


        return $data;
    }



    private function _buildData($type='yw_street',$data_map=[],$date_arr=[],$data_map_key_type='date_name'){
        $header = [
            'title' => '',
            'title_arr' => [],
            'date_arr' => $date_arr,
            'value_map' => [
                'total_nums' => '扫码数',
                'jkm_green_nums' => '绿码数',
                'jkm_yellow_nums' => '黄码数',
                'jkm_red_nums' => '红码数',
                'jkm_unknow_nums' => '未知数(健康码)',
                'xcm_green_nums' => '绿色数(行程码)',
                'xcm_yellow_nums' => '黄码数(行程码)',
                'xcm_red_nums' => '红码数(行程码)',
                'xcm_unknow_nums' => '未知数(行程码)',
                'vaccination_0_nums' => '接种0针数',
                'vaccination_1_nums' => '接种1针数',
                'vaccination_2_nums' => '接种2针数',
                'vaccination_3_nums' => '接种3针数',
                'ryxx_empty_nums' => '人员信息正常数',
                'ryxx_notempty_nums' => '人员信息异常数',
                'hsjc_1_day_nums' => '24小时内核酸人数',
                'hsjc_2_day_nums' => '48小时内核酸人数',
                'hsjc_3_day_nums' => '72小时内核酸人数',
                'hsjc_7_day_nums' => '7天内核酸人数',
                'hsjc_14_day_nums' => '14天内核酸人数',
                'hsjc_outer_14_day_nums' => '超14天内核酸人数',
                'hsjc_unknow_nums' => '未知数(核酸)',
            ]
        ];
        $is_need_sum = 0;
        switch($type){
            case 'yw_street':
                $yw_street_data = Db::name('district')->where('pid','=',2832)->select()->toArray();
                $yw_street_arr = array_column($yw_street_data,'name');
                $header['title'] = '街道';
                $header['title_arr'] = $yw_street_arr;
                $is_need_sum = 1;
                break;
            case 'place_classify':
                $place_classify_data = Db::name('place_classify')->select()->toArray();
                $place_classify_name_arr = array_column($place_classify_data,'cname');
                $header['title'] = '分类';
                $header['title_arr'] = $place_classify_name_arr;
                $is_need_sum = 1;
                break;
            case 'place_type':
                $place_type_data = Db::name('place_type')->select()->toArray();
                $place_type_arr = array_column($place_type_data,'place_type');
                $header['title'] = '行业';
                $header['title_arr'] = $place_type_arr;
                $is_need_sum = 1;
                break;
            case 'jkm':
                $header['title'] = '健康码';
                $header['title_arr'] = ['健康码合计'];
                $is_need_sum = 0;
                break;
            case 'hsjc':
                $header['title'] = '核酸检测';
                $header['title_arr'] = ['核酸检测合计'];
                $is_need_sum = 0;
                break;
            case 'ymjz':
                $header['title'] = '疫苗接种';
                $header['title_arr'] = ['疫苗接种合计'];
                $is_need_sum = 0;
                break;
            case 'xcm':
                $header['title'] = '行程码';
                $header['title_arr'] = ['行程码合计'];
                $is_need_sum = 0;
                break;
            case 'gkzz':
                $header['title'] = '管控状态';
                $header['title_arr'] = ['管控状态合计'];
                $is_need_sum = 0;
                break;
            case 'hour':
                $hour_arr = [];
                for($i=0;$i<24;$i++){
                    array_push($hour_arr,sprintf("%02d",$i).'');
                }
                $header['title'] = '小时';
                $header['title_arr'] = $hour_arr;
                $is_need_sum = 0;
                break;


        }
        $list = $this->_build_list($date_arr,$header['title_arr'],$data_map,$is_need_sum,$data_map_key_type);
        $return['header'] = $header;
        $return['list'] = array_values($list);
        return $return;
    }

    private function _build_list($date_arr=[],$title_arr=[],$data_map=[],$is_need_sum=0,$data_map_key_type='date_name'){
        $list = [];
        $empty_item = [
            'total_nums'=>'0',
            'jkm_green_nums'=>'0',
            'jkm_yellow_nums'=>'0',
            'jkm_red_nums'=>'0',
            'jkm_unknow_nums'=>'0',
            'xcm_green_nums'=>'0',
            'xcm_yellow_nums'=>'0',
            'xcm_red_nums'=>'0',
            'xcm_unknow_nums'=>'0',
            'vaccination_0_nums'=>'0',
            'vaccination_1_nums'=>'0',
            'vaccination_2_nums'=>'0',
            'vaccination_3_nums'=>'0',
            'ryxx_empty_nums'=>'0',
            'ryxx_notempty_nums'=>'0',
            'hsjc_1_day_nums'=>'0',
            'hsjc_2_day_nums'=>'0',
            'hsjc_3_day_nums'=>'0',
            'hsjc_7_day_nums'=>'0',
            'hsjc_14_day_nums'=>'0',
            'hsjc_outer_14_day_nums'=>'0',
            'hsjc_unknow_nums'=>'0',
        ];
        foreach($date_arr as $key => $value){
            $sum_item = $empty_item;
            foreach($title_arr as $key2 => $value2){
                $list[$value]['date'] = $value;
                if($data_map_key_type == 'date_name'){
                    if(isset($data_map[$value.'_'.$value2])){
                        if($is_need_sum){
                            $this->sum_item_value($sum_item,$data_map[$value.'_'.$value2]);
                        }
                        $list[$value]['list'][] = ['title'=>$value2,'value'=>$data_map[$value.'_'.$value2]];
                    }else{
                        $list[$value]['list'][] = ['title'=>$value2,'value'=>$empty_item];
                    }
                }
                if($data_map_key_type == 'date_hour'){
                    if(isset($data_map[$value.'_'.$value2])){
                        if($is_need_sum){
                            $this->sum_item_value($sum_item,$data_map[$value.'_'.$value2]);
                        }
                        $list[$value]['list'][] = ['title'=>$value2,'value'=>$data_map[$value.'_'.$value2]];
                    }else{
                        $list[$value]['list'][] = ['title'=>$value2,'value'=>$empty_item];
                    }
                }
                if($data_map_key_type == 'date'){
                    if(isset($data_map[$value])){
                        if($is_need_sum){
                            $this->sum_item_value($sum_item,$data_map[$value]);
                        }
                        $list[$value]['list'][] = ['title'=>$value2,'value'=>$data_map[$value]];
                    }else{
                        $list[$value]['list'][] = ['title'=>$value2,'value'=>$empty_item];
                    }
                }

            }
            if($is_need_sum){
                // 这天的合计
                $list[$value]['date'] = $value;
                $list[$value]['list'][] = ['title'=>'合计','value'=>$sum_item];
            }
        }
        return $list;
    }

    private function sum_item_value(&$sum_item,$value){
        $sum_item['total_nums'] += $value['total_nums'];
        $sum_item['jkm_green_nums'] += $value['jkm_green_nums'];
        $sum_item['jkm_yellow_nums'] += $value['jkm_yellow_nums'];
        $sum_item['jkm_red_nums'] += $value['jkm_red_nums'];
        $sum_item['jkm_unknow_nums'] += $value['jkm_unknow_nums'];
        $sum_item['xcm_green_nums'] += $value['xcm_green_nums'];
        $sum_item['xcm_yellow_nums'] += $value['xcm_yellow_nums'];
        $sum_item['xcm_red_nums'] += $value['xcm_red_nums'];
        $sum_item['xcm_unknow_nums'] += $value['xcm_unknow_nums'];
        $sum_item['vaccination_0_nums'] += $value['vaccination_0_nums'];
        $sum_item['vaccination_1_nums'] += $value['vaccination_1_nums'];
        $sum_item['vaccination_2_nums'] += $value['vaccination_2_nums'];
        $sum_item['vaccination_3_nums'] += $value['vaccination_3_nums'];
        $sum_item['ryxx_empty_nums'] += $value['ryxx_empty_nums'];
        $sum_item['ryxx_notempty_nums'] += $value['ryxx_notempty_nums'];
        $sum_item['hsjc_1_day_nums'] += $value['hsjc_1_day_nums'];
        $sum_item['hsjc_2_day_nums'] += $value['hsjc_2_day_nums'];
        $sum_item['hsjc_3_day_nums'] += $value['hsjc_3_day_nums'];
        $sum_item['hsjc_7_day_nums'] += $value['hsjc_7_day_nums'];
        $sum_item['hsjc_14_day_nums'] += $value['hsjc_14_day_nums'];
        $sum_item['hsjc_outer_14_day_nums'] += $value['hsjc_outer_14_day_nums'];
        $sum_item['hsjc_unknow_nums'] += $value['hsjc_unknow_nums'];
    }

}
