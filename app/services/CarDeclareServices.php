<?php
declare (strict_types=1);

namespace app\services;

use think\facade\Db;
use Curl\Curl;
use app\model\CarDeclare;
use app\model\CarTravelLog;
use think\facade\Log;

class CarDeclareServices
{
    public function getTravelFromApi($param)
    {

        $save['is_warning'] = 0;
        $save['api_result'] = 1;
        app()->make(CarDeclare::class)->where(['id'=>$param['id']])->update($save);
        app()->make(CarTravelLog::class)->save(['plate_number'=>$param['plate_number'],'data'=>[]]);

        return;


        $curl = new Curl();
        $curl->get('https://hcaq.sinoiov.com/yiwu/vehicle/validate',[
            'vno'   => $param['plate_number'],
            'color' => 2, // 黄牌
        ]);
        $resdata = $curl->response;
        // $resdata = '{"status":"0","message":"success","result":{"flag":true,"data":{"绍兴市上虞区":[{"endGpsTime":1640069780000,"areaCode":"330604","parkTime":11284000,"lng":120.712535,"addr":"浙江省绍兴市上虞区苏宁北门西南168米","beginGpsTime":1640058496000,"lat":30.127215},{"endGpsTime":1640337277000,"areaCode":"330604","parkTime":9743000,"lng":120.71268666666667,"addr":"浙江省绍兴市上虞区苏宁北门西152米","beginGpsTime":1640327534000,"lat":30.1273},{"endGpsTime":1640462841000,"areaCode":"330604","parkTime":49533000,"lng":120.71263166666667,"addr":"浙江省绍兴市上虞区苏宁北门西南163米","beginGpsTime":1640413308000,"lat":30.127116666666666},{"endGpsTime":1640635733000,"areaCode":"330604","parkTime":46376000,"lng":120.7126,"addr":"浙江省绍兴市上虞区苏宁北门西南175米","beginGpsTime":1640589357000,"lat":30.126933333333334},{"endGpsTime":1640763256000,"areaCode":"330604","parkTime":18285000,"lng":120.71243166666666,"addr":"浙江省绍兴市上虞区苏宁北门西176米","beginGpsTime":1640744971000,"lat":30.127265}],"宁波市镇海区":[{"endGpsTime":1640499605000,"areaCode":"330211","parkTime":3298000,"lng":121.57084666666667,"addr":"浙江省宁波市镇海区宁波铝工精密机械设备有限公司北102米","beginGpsTime":1640496307000,"lat":29.96055},{"endGpsTime":1640501707000,"areaCode":"330211","parkTime":1672000,"lng":121.5702,"addr":"浙江省宁波市镇海区宁波铝工精密机械设备有限公司西北141米","beginGpsTime":1640500035000,"lat":29.96085}]}}}';
        $res = json_decode($resdata,true);

        // if($res){
            try{
                if(isset($res['result']['data'])){
                    $list_data = $res['result']['data'];
    
                    // travel_route 为主要的行程城市
                    // travel_data 为 行程城市中最近的一次停车情况
                    $save = [];
                    $save['travel_route'] = '';
    
                    $travel_data = [];
                    foreach($list_data as $city_conuty => $value){
                        $save['travel_route'] .= $city_conuty.',';
                        $last_item = [];
                        foreach($value as $key2 => $value2){
                            if( count($last_item) == 0){
                                $last_item = $value2;
                            }
                            if($value2['endGpsTime'] > $last_item['endGpsTime']){
                                $last_item = $value2;
                            }
                        }
                        $item = [];
                        $item['city_conuty'] = $city_conuty;
                        $item['addr'] = $last_item['addr'];
                        $item['beginGpsTime'] = Date('Y-m-d H:i:s',$last_item['beginGpsTime'] /1000);
                        $item['endGpsTime'] = Date('Y-m-d H:i:s',$last_item['endGpsTime'] /1000);
    
    
                        array_push($travel_data,$item);
                    }
                    
                    
                    $save['is_warning'] = $res['result']['flag'] == true ? 1 : 0;
                    $save['api_result'] = 1;
                    $save['travel_route'] = trim($save['travel_route'],',');
                    $save['travel_data']  = json_encode($travel_data);
    
                    app()->make(CarDeclare::class)->where(['id'=>$param['id']])->update($save);
                    app()->make(CarTravelLog::class)->save(['plate_number'=>$param['plate_number'],'data'=>$resdata]);
                }else{
    
                    // $this->getGreenTravelFromApi($param);
    
                    $save['is_warning'] = $res['result']['flag'] == true ? 1 : 0;
                    $save['api_result'] = 1;
                    app()->make(CarDeclare::class)->where(['id'=>$param['id']])->update($save);
                    app()->make(CarTravelLog::class)->save(['plate_number'=>$param['plate_number'],'data'=>$resdata]);
                }
            } catch (\Exception $e){
                test_log('getTravelFromApi error:'.$e->getMessage());
                Log::error($e->getMessage());
            }
        // }else{
        //     test_log('黄牌查询：非正常返回');
        // }
    }
    public function getGreenTravelFromApi($param)
    {
        $curl = new Curl();
        $curl->get('https://hcaq.sinoiov.com/yiwu/vehicle/validate',[
            'vno'   => $param['plate_number'],
            'color' => 1, // 绿牌
        ]);
        $resdata = $curl->response;
        // $resdata = '{"status":"0","message":"success","result":{"flag":true,"data":{"绍兴市上虞区":[{"endGpsTime":1640069780000,"areaCode":"330604","parkTime":11284000,"lng":120.712535,"addr":"浙江省绍兴市上虞区苏宁北门西南168米","beginGpsTime":1640058496000,"lat":30.127215},{"endGpsTime":1640337277000,"areaCode":"330604","parkTime":9743000,"lng":120.71268666666667,"addr":"浙江省绍兴市上虞区苏宁北门西152米","beginGpsTime":1640327534000,"lat":30.1273},{"endGpsTime":1640462841000,"areaCode":"330604","parkTime":49533000,"lng":120.71263166666667,"addr":"浙江省绍兴市上虞区苏宁北门西南163米","beginGpsTime":1640413308000,"lat":30.127116666666666},{"endGpsTime":1640635733000,"areaCode":"330604","parkTime":46376000,"lng":120.7126,"addr":"浙江省绍兴市上虞区苏宁北门西南175米","beginGpsTime":1640589357000,"lat":30.126933333333334},{"endGpsTime":1640763256000,"areaCode":"330604","parkTime":18285000,"lng":120.71243166666666,"addr":"浙江省绍兴市上虞区苏宁北门西176米","beginGpsTime":1640744971000,"lat":30.127265}],"宁波市镇海区":[{"endGpsTime":1640499605000,"areaCode":"330211","parkTime":3298000,"lng":121.57084666666667,"addr":"浙江省宁波市镇海区宁波铝工精密机械设备有限公司北102米","beginGpsTime":1640496307000,"lat":29.96055},{"endGpsTime":1640501707000,"areaCode":"330211","parkTime":1672000,"lng":121.5702,"addr":"浙江省宁波市镇海区宁波铝工精密机械设备有限公司西北141米","beginGpsTime":1640500035000,"lat":29.96085}]}}}';
        $res = json_decode($resdata,true);
        if($res){
            try{
                if(isset($res['result']['data'])){
                    $list_data = $res['result']['data'];

                    // travel_route 为主要的行程城市
                    // travel_data 为 行程城市中最近的一次停车情况
                    $save = [];
                    $save['travel_route'] = '';

                    $travel_data = [];
                    foreach($list_data as $city_conuty => $value){
                        $save['travel_route'] .= $city_conuty.',';
                        $last_item = [];
                        foreach($value as $key2 => $value2){
                            if( count($last_item) == 0){
                                $last_item = $value2;
                            }
                            if($value2['endGpsTime'] > $last_item['endGpsTime']){
                                $last_item = $value2;
                            }
                        }
                        $item = [];
                        $item['city_conuty'] = $city_conuty;
                        $item['addr'] = $last_item['addr'];
                        $item['beginGpsTime'] = Date('Y-m-d H:i:s',$last_item['beginGpsTime'] /1000);
                        $item['endGpsTime'] = Date('Y-m-d H:i:s',$last_item['endGpsTime'] /1000);


                        array_push($travel_data,$item);
                    }
                    
                    
                    $save['is_warning'] = $res['result']['flag'] == true ? 1 : 0;
                    $save['api_result'] = 1;
                    $save['travel_route'] = trim($save['travel_route'],',');
                    $save['travel_data']  = json_encode($travel_data);

                    app()->make(CarDeclare::class)->where(['id'=>$param['id']])->update($save);
                    app()->make(CarTravelLog::class)->save(['plate_number'=>$param['plate_number'],'data'=>$resdata]);
                }else{

                    $save['is_warning'] = $res['result']['flag'] == true ? 1 : 0;
                    $save['api_result'] = 1;
                    app()->make(CarDeclare::class)->where(['id'=>$param['id']])->update($save);
                    app()->make(CarTravelLog::class)->save(['plate_number'=>$param['plate_number'],'data'=>$resdata]);
                }
            } catch (\Exception $e){
                test_log('getGreenTravelFromApi error:'.$e->getMessage());
                Log::error($e->getMessage());
            }
        }else{
            test_log('绿牌查询：非正常返回');
        }
    }
}
