<?php

namespace app\controller;

use app\dao\PlaceDao;
use think\facade\Db;
use Curl\Curl;
use think\facade\Cache;
use crmeb\services\SwooleTaskService;
use think\facade\Config;
use app\services\MessageServices;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use think\facade\Env;
use \behavior\WechatAppletTool;
use Swoole\Coroutine;
class FixController
{



    public function date_nums_fix_classify(){
        $param = request()->param();
        try{
            // $companyDao = app()->make(CompanyDao::class);
            // $CompanyStaffSlaveDao = app()->make(CompanyStaffSlaveDao::class);
            $index = 1;
            if(isset($param['from_id']) && $param['from_id'] > 0){
                $from_id = $param['from_id'];
            }else{
                $from_id = 0;
            }
            
            while($index == 1) {
                $list = Db::connect('mysql_slave')->name('place_declare_date_nums')->alias('ddn')
                            ->field('ddn.id,ddn.place_code,p.place_classify,p.place_type')
                            ->leftJoin('place p', 'p.code=ddn.place_code')
                            ->where('ddn.id','>',$from_id)
                            ->limit(200)->select()->toArray();
                if(count($list) == 0) {
                    $index = 0;
                }else{
                    $num = count($list);
                    foreach($list as $k => $v) {
                        Db::name('place_declare_date_nums')->where('id','=',$v['id'])->update(['place_classify'=>$v['place_classify'],'place_type'=>$v['place_type']]);
                        if($num == $k + 1){
                            // test_log('本次最后一个id为:'.$v['id']);
                            $from_id = $v['id']; // 下一批的开始id
                        }
                    }
                }
            }
        }catch(\Exception $e) {
            test_log('date_nums_fix_classify error:'. $e->getMessage());
        }
    }




    public function company_user_count_fix(){
        $data = Db::name('company')->where('fix_tag','=',0)->field('id')->limit(1000)->select();
        foreach($data as $key => $value){
            // 重新计算企业人数数值
            SwooleTaskService::company()->taskType('company')->data(['action'=>'taskCompanyCheckFrequencyCountByCompanyId','param'=> ['company_id' => $value['id']]])->push();
            Db::name('company')->where('id','=',$value['id'])->update(['fix_tag'=>1]);
        }
        echo ('已触发'.count($data));
        $num = Db::name('company')->where('fix_tag','=',0)->count();
        echo ('还剩'.$num);
    }



    public function xiaoshangpincheng_short_name(){
        $data = Db::name('yw_xiaoshangpincheng')->whereNull('short_name')->where('is_get_short_name','=',0)->limit(2000)->select();

        foreach( $data as $key => $value ){
            // 正则查找
            $match = '/(篁园服装市场|国际商贸城){1}(.*?)(号商位|号){1}/';
            $route_res = preg_match($match, $value['dom'], $route);
            if($route) {
                // $travel_route = str_replace('||', '', $route[2]);
                $short_name[$key] = $route[1].$route[2].'号';
                Db::name('yw_xiaoshangpincheng')->where('UNISCID','=',$value['UNISCID'])->update(['short_name'=>$short_name[$key],'is_get_short_name'=>1]);
            }else{
                Db::name('yw_xiaoshangpincheng')->where('UNISCID','=',$value['UNISCID'])->update(['is_get_short_name'=>2]);
            }
        }

        echo ('已创建'.count($data));

    }

    
    public function xiaoshangpincheng_to_place(){

        $data = Db::name('yw_xiaoshangpincheng')->whereNotNull('short_name')->where('is_to_place','=',0)->limit(800)->select();

        $xiaoshangpincheng_unicode_arr = [];

        $no_phone_unicode_arr = [];
        foreach($data as $key => $value){
            if($value['short_name'] != '' && mb_strlen($value['TEL']) == 11){
                array_push($xiaoshangpincheng_unicode_arr,$value['UNISCID']);
                
                $code[$key] = randomCode(12);
                $item = [
                    'code' => $code[$key],
                    'applet_qrcode' => '',
                    'name' => $value['ENTNAME'],
                    'short_name' => $value['short_name'],
                    'yw_street_id' => 0,
                    'yw_street' => '',
                    'addr' => $value['dom'],
                    'superior_gov' => '小商品城',
                    'place_classify' => 'company',
                    'place_type_id' => 31,
                    'place_type' => '市场',
                    'community' => '',
                    'link_man' => $value['LEREP'],
                    'link_phone' => $value['TEL'],
                    'credit_code' => $value['UNISCID'],
                    'source' => 'admin',
                    'remark' => '',
                    'fix_tag' => 99
                ];
                $res[$key] = app()->make(PlaceDao::class)->save($item);
                // echo $res[$key]->id."\n";
            }else{
                array_push($no_phone_unicode_arr,$value['UNISCID']);
            }
        }
        if(count($no_phone_unicode_arr)){
            Db::name('yw_xiaoshangpincheng')->where('UNISCID','in',$no_phone_unicode_arr)->update(['is_to_place'=>2]);
        }

        if(count($xiaoshangpincheng_unicode_arr)){
            Db::name('yw_xiaoshangpincheng')->where('UNISCID','in',$xiaoshangpincheng_unicode_arr)->update(['is_to_place'=>1]);

            echo ('已创建'.count($xiaoshangpincheng_unicode_arr));
        }else{
            echo '无';
        }
    }

    public function guojiyoujian_to_place()
    {
        $data = Db::name('yw_guojiyoujian')->select();
        foreach($data as $key => $value) {
            $code[$key] = randomCode(12);
            $item = [
                'code' => $code[$key],
                'applet_qrcode' => '',
                'name' => $value['name'],
                'short_name' => $value['name'],
                'yw_street_id' => 0,
                'yw_street' => '',
                'addr' => '',
                'superior_gov' => '国际邮件',
                'place_classify' => 'company',
                'place_type_id' => 31,
                'place_type' => '其他',
                'community' => '',
                'link_man' => '',
                'link_phone' => '',
                'credit_code' => '',
                'source' => 'admin',
                'remark' => '国际邮件码',
                'fix_tag' => 44,
            ];
            $res[$key] = app()->make(PlaceDao::class)->save($item);
        }
    }

    // 手动每次50条
    public function reset_user_hsjc_data_by_api(){
        $all_nums = Db::name('user')->where('hsjc_result','=','阴性')->whereNull('hsjc_time')->count();
        $data = Db::name('user')->where('hsjc_result','=','阴性')->whereNull('hsjc_time')->limit(50)->select();
        foreach($data as $key => $value){
            SwooleTaskService::company()->taskType('company')->data(['action'=>'setUserFysjService','param'=> ['id' => $value['id']]])->push();
        }
        var_dump('一共还有'.$all_nums);
    }



    public function reset_user_hsjc_data(){
        $data = Db::name('user')->where('hsjc_result','=','阴性')->whereNull('hsjc_time')->where('csmsb_sign','<>','')->select();
        foreach($data as $key => $value){
            $place_declare[$key] = Db::name('place_declare')->where('sign','=',$value['csmsb_sign'])->find();
            if( (string)$place_declare[$key]['hsjc_time'] != ''){
                $res[$key] = Db::name('user')->where('id','=',$value['id'])->update(['hsjc_time'=>$place_declare[$key]['hsjc_time']]);

                echo $res[$key];
            }
        }
    }


    // public function place_link_phone_to_user_id(){
    //     $data = Db::name('place')->alias('p')
    //             ->field('p.*')
    //             ->where('user_id','=',0)
    //             ->select()->toArray();
    //     // dump($data);
    //     foreach($data as $key => $value){
    //         $user[$key] = Db::name('user')->where('phone','=',$value['link_phone'])->find();
    //         if($user[$key]){
    //             $value['user_phone'] = $user[$key]['phone'];
    //             $res[$key] = Db::name('place')->where('id','=',$value['id'])->update(['user_id'=>$user[$key]['id']]);
    //             echo ($res[$key]);
    //         }
    //     }
    // }

    // public function place_user_phone_neq_phone(){
    //     $data = Db::name('place')->alias('p')
    //             ->field('p.*')
    //             ->where('fix_tag','=',0)
    //             ->where('user_id','>',0)
    //             ->limit(1000)
    //             ->select()
    //             ->toArray();

    //     foreach($data as $key => $value){
    //         $user[$key] = Db::name('user')->field('phone,real_name')->where('id','=',$value['user_id'])->find();
    //         if($user[$key]){
    //             if($user[$key]['phone'] != $value['link_phone']){
    //                 $res[$key] = Db::name('place')->where('id','=',$value['id'])->update(['fix_tag'=>1,'link_phone'=>$user[$key]['phone'],'link_man'=>$user[$key]['real_name']]);
    //                 echo ($res[$key])."ok";
    //             }else{
    //                 $res[$key] = Db::name('place')->where('id','=',$value['id'])->update(['fix_tag'=>1]);
    //                 echo ($res[$key]);
    //             }
    //         }
    //     }
    // }


    // public function place_qrcode_v2(){

    //     $at_server = Config::get('upload.at_server');
    //     $host_last_id = str_replace('server','',$at_server);

    //     $data = Db::name('place')->alias('p')
    //             ->field('p.*')
    //             ->where('fix_tag','=',0)
    //             ->limit(1000)
    //             ->select()
    //             ->toArray();



                
    //     foreach($data as $key => $value){
    //         if(strstr($value['applet_qrcode'], $at_server)) {
    //             SwooleTaskService::place()->taskType('place')->data(['action'=>'placeQrcodeWatermarkService','param'=> ['file_path' => $value['applet_qrcode'], 'version' => 'v2', 'short_name'=> $value['short_name']]])->push();
    //             $res[$key] = Db::name('place')->where('id','=',$value['id'])->update(['fix_tag'=>$host_last_id]);
    //             echo ($res[$key]);
    //         }else{
    //             $res[$key] = Db::name('place')->where('id','=',$value['id'])->update(['fix_tag'=>1]);
    //             echo ($res[$key]);
    //         }
    //     }



    // }

    
    // public function place_create_date(){
    //     $param = request()->param();
    //     $create_date = $param['create_date'];

    //     $start_time = strtotime($param['create_date'].' 00:00:00');
    //     $end_time = strtotime($param['create_date'].' 23:59:59');
    //     $res = Db::name('place_declare')->where('create_time', '>=',$start_time )->where('create_time', '<=',$end_time )->whereNull('create_date')->limit(10000)->update(['create_date'=>$create_date]);
    //     echo $res;
    // }


    // 给场所码信息不全的发送短信
    // 完善场所码信息的短信：何允春您好，请完善您申请“义乌市合杰软件开发有限公司”的场所码资料，包括名称、所属镇街、地址等信息，务必保证信息准确有效！
    // 名称以数值开头，名称包含“*”，名称长度小于3，街道为空，地址长度小于3，这些场所码

    // 街道id为0
    public function place_info_update_yw_street_sms(){

        $data = Db::name('place')
             ->where('user_id', '>',0)
             ->where('yw_street_id', '=', 0 )
             ->select()->toArray();

        $MessageServices = app()->make(MessageServices::class);

        foreach($data as $key => $value){
            $message_data = [
                'receive_id'=> 0,
                'receive'=> $value['link_man'],
                'phone'=> $value['link_phone'],
                'source_id'=> 0,
            ];
            $param_data = [
                'link_man' => $value['link_man'],
                'place_name' => $value['name']
            ];
            $MessageServices->asyncMessage('template003', $message_data, $param_data,1);
        }

        echo '共触发'.count($data);
    }

    public function send_sms_yanshi(){
        $data = Db::name('message_record')->where('ismerge','=',1)->where('status','=',0)->limit(100)->select();
        $MessageServices = app()->make(MessageServices::class);
        foreach($data as $key => $value){
            $MessageServices->sendMessage($value);
            Db::name('message_record')->where('id','=',$value['id'])->update(['ismerge'=>2]);
        }
    }


    /**
     * 导入
     */
    public function readExcelFile()
    {
        $file = request()->param('file');
        $forscence = request()->param('forscence');
        if (!$file) {
            echo 'file参数未找到';
            return;
        }
        $filePath = app()->getRootPath() .'public/' . $file;
        if (!is_file($filePath)) {
            echo '未找到文件';
            return;
        }
        //实例化reader
        $ext = pathinfo($filePath, PATHINFO_EXTENSION);
        if (!in_array($ext, ['csv', 'xls', 'xlsx'])) {
            echo '未知的数据格式';
            return;
        }
        if ($ext === 'csv') {
            $file = fopen($filePath, 'r');
            $filePath = tempnam(sys_get_temp_dir(), 'import_csv');
            $fp = fopen($filePath, "w");
            $n = 0;
            while ($line = fgets($file)) {
                $line = rtrim($line, "\n\r\0");
                $encoding = mb_detect_encoding($line, ['utf-8', 'gbk', 'latin1', 'big5']);
                if ($encoding != 'utf-8') {
                    $line = mb_convert_encoding($line, 'utf-8', $encoding);
                }
                if ($n == 0 || preg_match('/^".*"$/', $line)) {
                    fwrite($fp, $line . "\n");
                } else {
                    fwrite($fp, '"' . str_replace(['"', ','], ['""', '","'], $line) . "\"\n");
                }
                $n++;
            }
            fclose($file) || fclose($fp);

            $reader = new Csv();
        } elseif ($ext === 'xls') {
            $reader = new Xls();
        } else {
            $reader = new Xlsx();
        }

        //导入文件首行类型,默认是注释,如果需要使用字段名称请使用name
        $importHeadType = isset($this->importHeadType) ? $this->importHeadType : 'comment';

        // $table = 'eb_place';
        $table = 'eb_imports';
        $database =  Env::get('database.database');
        $fieldArr = [];
        $list = Db::connect('mysql')->query("SELECT COLUMN_NAME,COLUMN_COMMENT FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = ? AND TABLE_SCHEMA = ?", [$table, $database]);
        
        dump($list); 
        foreach ($list as $k => $v) {
            if ($importHeadType == 'comment') {
                $fieldArr[$v['COLUMN_COMMENT']] = $v['COLUMN_NAME'];
            } else {
                $fieldArr[$v['COLUMN_NAME']] = $v['COLUMN_NAME'];
            }
        }
        dump($fieldArr);

        //加载文件
        $insert = [];
 
        if (!$PHPExcel = $reader->load($filePath)) {
            echo '未知的数据格式';
            return;
        }
        $currentSheet = $PHPExcel->getSheet(0);  //读取文件中的第一个工作表
        $allColumn = $currentSheet->getHighestDataColumn(); //取得最大的列号
        $allRow = $currentSheet->getHighestRow(); //取得一共有多少行
        $maxColumnNumber = Coordinate::columnIndexFromString($allColumn);
        $fields = [];
        for ($currentRow = 1; $currentRow <= 1; $currentRow++) {
            for ($currentColumn = 1; $currentColumn <= $maxColumnNumber; $currentColumn++) {
                $val = $currentSheet->getCellByColumnAndRow($currentColumn, $currentRow)->getValue();
                $fields[] = $val;
            }
        }
        // var_dump($fields);
        // dump($allRow);

        for ($currentRow = 2; $currentRow <= $allRow; $currentRow++) {
            $values = [];
            for ($currentColumn = 1; $currentColumn <= $maxColumnNumber; $currentColumn++) {
                $val = $currentSheet->getCellByColumnAndRow($currentColumn, $currentRow)->getValue();
                $values[] = is_null($val) ? '' : $val;
            }
            $row = [];
            $temp = array_combine($fields, $values);
            
            foreach ($temp as $k => $v) {
                
                if (isset($fieldArr[$k]) && $k !== '') {
                    $row[$fieldArr[$k]] = $v;
                }
            }
            if ($row) {
                $insert[] = $row;
            }
        }
        if($forscence == 'saveAllPlace'){
            $this->_saveAllPlace($insert);
        }else{
            echo json_encode($insert);
        }
        
    }

    private function _saveAllPlace($all_data){
        $item = [];
        foreach($all_data as $key => $value ){
            if($value['name'] != ''){
                $code[$key] = randomCode(12);
                $item = [
                    'code' => $code[$key],
                    'applet_qrcode' => '',
                    'name' => '恒风出租车'.$value['name'],
                    'short_name' => '出租车'.$value['short_name'],
                    'yw_street_id' => 0,
                    'yw_street' => '',
                    'addr' => '',
                    'superior_gov' => '恒风集团',
                    'place_classify' => 'other',
                    'place_type_id' => 31,
                    'place_type' => '其他',
                    'community' => '',
                    'link_man' => $value['link_man'],
                    'link_phone' => $value['link_phone'],
                    'credit_code' => '',
                    'source' => 'admin',
                    'remark' => $value['remark'],
                    'fix_tag' => 99
                ];
                $res[$key] = app()->make(PlaceDao::class)->save($item);
                echo $res[$key]->id."\n";
            }
        }
    }


    public function chrome_auto_cli_build_place_qrcode(){
        // $at_server = Config::get('upload.at_server');
        // // $host_last_id = str_replace('server','',$at_server);
        // $data = Db::name('place')->alias('p')
        //         ->field('p.*')
        //         ->where('fix_tag','=',99)
        //         ->limit(200)
        //         ->select()
        //         ->toArray();


        // foreach($data as $key => $value){
        //     $wechatAppletTool = new WechatAppletTool();
        //     $applet_qrcode[$key] = $wechatAppletTool->appletPlaceQrcode($value['code'], $value['short_name']);
        //     if($applet_qrcode[$key]['status'] == 0) {
        //         echo  '操作失败-'. $applet_qrcode[$key]['msg'];
        //     }else{
        //         $res[$key] = app()->make(PlaceDao::class)->update($value['id'], ['fix_tag'=>3,'applet_qrcode'=>$applet_qrcode[$key]['data'] ]);
        //         // echo $res[$key]->id."\n";
        //         echo '成功';
        //     }
        // }
        Coroutine::create(function ()  {
            $curl = new Curl();
            $res = $curl->get('http://localhost:30399/cli_build_place_qrcode',[
            ]);
            dump($res);
        });
        return redirect('/chrome_auto_cli_build_place_qrcode');
    }


    public function auto_cli_build_place_qrcode(){
        $index = 1;
        
        while($index == 1) {
            $data = Db::name('place')->alias('p')
                ->field('p.*')
                ->where('fix_tag','=',99)
                ->limit(100)
                ->select()
                ->toArray();

            if(count($data) == 0) {
                $index = 0;
            }else{
                foreach($data as $key => $value){
                    $wechatAppletTool = new WechatAppletTool();
                    $applet_qrcode[$key] = $wechatAppletTool->appletPlaceQrcode($value['code'], $value['short_name']);
                    if($applet_qrcode[$key]['status'] == 0) {
                        echo  '操作失败-'. $applet_qrcode[$key]['msg'];
                    }else{
                        $res[$key] = app()->make(PlaceDao::class)->update($value['id'], ['fix_tag'=>3,'applet_qrcode'=>$applet_qrcode[$key]['data'] ]);
                        // echo $res[$key]->id."\n";
                        echo '成功';
                    }
                }
                sleep(2);
            }
        }
        return show(200, '已触发');
    }



    public function cli_build_place_qrcode(){

        $at_server = Config::get('upload.at_server');
        // $host_last_id = str_replace('server','',$at_server);
        $data = Db::name('place')->alias('p')
                ->field('p.*')
                ->where('fix_tag','=',44)
                //->limit(20)
                ->select()
                ->toArray();


        foreach($data as $key => $value){
            $wechatAppletTool = new WechatAppletTool();
            $applet_qrcode[$key] = $wechatAppletTool->appletPlaceQrcode($value['code'], $value['short_name']);
            if($applet_qrcode[$key]['status'] == 0) {
                echo  '操作失败-'. $applet_qrcode[$key]['msg'];
            }else{
                $res[$key] = app()->make(PlaceDao::class)->update($value['id'], ['fix_tag'=>3,'applet_qrcode'=>$applet_qrcode[$key]['data'] ]);
                // echo $res[$key]->id."\n";
                echo '成功';
            }
        }
    }

    public function fix_tag_place_pic_at_118(){
        $data = Db::name('place')->alias('p')
                ->field('p.id,p.applet_qrcode')
                ->where('fix_tag','=',0)
                ->limit(1000)
                ->select()
                ->toArray();

        foreach($data as $key => $value){
            if( strstr($value['applet_qrcode'],'server118') ) {
                $res[$key] = Db::name('place')->where('id','=',$value['id'])->update(['fix_tag'=>118]);
                echo ($res[$key])."ok";
            }else{
                $res[$key] = Db::name('place')->where('id','=',$value['id'])->update(['fix_tag'=>1]);
            }
        }
    }

    public function companyStaffStreet()
    {
        $sql = 'update `eb_company_staff` cs left join eb_company c on cs.company_id=c.id set cs.yw_street_id=c.yw_street_id,cs.yw_street=c.yw_street where cs.yw_street_id is null';
        $lines = Db::execute($sql);
        return show(200, '成功', $lines);
    }


   public function company_qrcode_v2(){

        $at_server = Config::get('upload.at_server');
        $host_last_id = str_replace('server','',$at_server);

        $data = Db::name('company')->alias('c')
                ->field('c.*')
                ->where('fix_tag','=',0)
                ->limit(1000)
                ->select()
                ->toArray();

                
        foreach($data as $key => $value){
            SwooleTaskService::company()->taskType('company')->data(['action'=>'companyQrcodeWatermarkService','param'=> ['file_path' => $value['company_qrcode'], 'version' => 'v2', 'name'=> $value['name']]])->push();
            $res[$key] = Db::name('company')->where('id','=',$value['id'])->update(['fix_tag'=>22]);
            echo ($res[$key]);
        }



    }

    //发送未发送的消息
    public function send_company_sms_by_templete()
    {
        Db::name('company')->where('fix_tag','<>',0)->update(['fix_tag'=>0]);
        $MessageServices = app()->make(MessageServices::class);
        $index = 1;
        $pageSize = 800;
        //分批次发送短信
        while($index == 1) {
            $data = Db::name('company')->where('fix_tag','=',0)->limit($pageSize)->select();
            if(count($data) == 0) {
                $index = 0;
            }else{
                $id_arr = [];
                foreach($data as $key => $value) {
                    array_push($id_arr,$value['id']);
                    $message_data = [
                        'receive_id'=> $value['link_id'],
                        'receive'=> $value['link_name'],
                        'phone'=> $value['link_phone'],
                        'source_id'=> 0,
                    ];
                    $param_data = [
         
                    ];
                    $MessageServices->asyncMessage('template007', $message_data, $param_data,1);
                }
                Db::name('company')->where('id','in',$id_arr)->update(['fix_tag'=>1]);
                sleep(2);
            }
        }
        return show(200, '已触发');
    }

    public function send_delay_sms(){

        $MessageServices = app()->make(MessageServices::class);
        $index = 1;
        //分批次发送短信
        while($index == 1) {
            $data = Db::name('message_record')->where('ismerge','=',1)->where('status','=',0)->limit(800)->select();
            if(count($data) == 0) {
                $index = 0;
            }else{
                foreach($data as $key => $value){
                    $MessageServices->sendMessage($value);
                    Db::name('message_record')->where('id','=',$value['id'])->update(['ismerge'=>2]);
                }
                sleep(2);
            }
        }
        return show(200, '已触发');
    }



}