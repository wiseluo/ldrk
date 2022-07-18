<?php

namespace behavior;

use Curl\Curl;
use think\facade\Config;
use behavior\ErrorTool;

//数管中心工具类
class SgzxTool
{
    
    //全省健康码基本信息查询
    public function getByqsjkmxxcx($id_card)
    {
        // test_log('start getByqsjkmxxcx id_card:'.$id_card);
        $curl = new Curl();
        // 目前反向代理,为了测试和正式环境都能调用
        if(Config::get('app.app_host') == 'dev') { //测试服务器
            $url = "http://localhost:8083/ESBWeb/servlets/qsjkmxxcx@1.0";
        }else{
            $url = "http://172.45.7.23/ESBWeb/servlets/qsjkmxxcx@1.0";
        }
        $curl->get($url, [
            'sfzh' => $id_card
        ]);
        if ($curl->error) {
            test_log('getByqsjkmxxcx-'. $curl->error_code . ': ' . $curl->error_message);
            return ['status'=> 0, 'msg'=> $curl->error_code . ': ' . $curl->error_message];
        } else {
            $res = $curl->response;
            
            $result = json_decode($res, true);
        }
        $curl->close();
        // test_log('getByqsjkmxxcx-'. $curl->response);
        if($result['code'] == "00") {
            $datas = json_decode($result['datas'], true);
            if($datas['success'] == true) {
                if($datas['data'] == null || count($datas['data']) == 0) {
                    return ['status'=> 0, 'msg'=> '不存在'];
                }
                $last_item = $this->_getLastJkmItem($datas['data']);
                return ['status'=> 1, 'msg'=> '成功', 'data'=> $last_item ];
            }else{
                return ['status'=> 0, 'msg'=> $datas['errMsg']];
            }
        }else{
            test_log('getByqsjkmxxcx-'. $curl->response);
            return ['status'=> 0, 'msg'=> $result['msg']];
        }
    }

    // {
    //     "success": true,
    //     "data": [{
    //         "sfzh": "370306196910220535",
    //         "id": "33010093472025",
    //         "xm": "曹庆忠",
    //         "sjh": "13864301601",
    //         "mffd": "杭州市",
    //         "mlx": "本域内通行码",
    //         "mzt": "绿码",
    //         "hmcmyy": "解除 - 原全省重点人群(含密接)【省数据局接口】",
    //         "scffsj": "2021-08-10T08:49:17.000+0000",
    //         "scsqsj": "2021-08-10T08:49:17.000+0000",
    //         "zjlx": "IDENTITY_CARD",
    //         "zjgxsj": "2021-08-20T04:42:50.000+0000",
    //         "source": "杭州自建",
    //         "dbrsfzh": null
    //     }, {
    //         "sfzh": "370306196910220535",
    //         "id": "03133f0f6a5ebb8666d237142d0abe10",
    //         "xm": "曹庆忠",
    //         "sjh": "13864301601",
    //         "mffd": "温州市",
    //         "mlx": "本域内通行码",
    //         "mzt": "绿码",
    //         "hmcmyy": "",
    //         "scffsj": "2021-10-21T04:23:40.000+0000",
    //         "scsqsj": "2021-10-21T04:23:40.000+0000",
    //         "zjlx": "IDENTITY_CARD",
    //         "zjgxsj": "2021-10-21T04:23:40.000+0000",
    //         "source": "阿里自建root",
    //         "dbrsfzh": null
    //     }],
    //     "errorCode": null,
    //     "errorMsg": null,
    //     "extraData": null,
    //     "traceId": null
    // }

    private function _getLastJkmItem($data){
        $last_item = [];
        foreach($data as $key => $item){
            if(count($last_item) == 0){
                $last_item = $item;
            }
            // 时间
            if( strtotime($item['zjgxsj']) > strtotime($last_item['zjgxsj']) ){
                $last_item = $item;
            }
        }
        return $last_item;
    }
    private function _getLastJkmPhoneRealCardIdItem($data,$phone){
        $last_item = [];
        $id_card_arr = [];
        $id_card_map = [];

        $age_map = [];
        $age_arr = [];
        $cur_year = Date("Y");
        
        foreach($data as $key => $item){
            $item_year = substr($item['sfzh'],6,4);
            $age = $cur_year - $item_year;
            array_push($age_arr,$age);
            $age_map[$age] = $item;
            $id_card_map[$item['sfzh']] = $item;
            if(count($last_item) == 0){
                $last_item = $item;
            }
            array_push($id_card_arr,$item['sfzh']);
            // // 比较年份，因为存在 孩子爸妈替孩子 填写 爸妈的年份值较小
            
            // $last_item_year = substr($last_item['sfzh'],6,4);
            // if( $item_year < $last_item_year ){
            //     // 爸妈的年份值较小
            //     $last_item = $item;
            // }
        }
        if(count(array_unique($id_card_arr)) > 1){
            // 选择15—60岁
            // 将age_arr 排序，去除大于60岁 ，取小于60岁最大的那个
            $age_arr = array_unique($age_arr);
            sort($age_arr);
            foreach($age_arr as $key => $value){
                if($value > 60){
                    continue;
                }
                $last_item = $age_map[$value]; // 取
            }
            // test_log('手机号'.$phone.'健康码中有多个身份证信息:'.json_encode(array_unique($id_card_arr)));
            // test_log('当前获取到:'.json_encode($last_item));
        }
        return $last_item;
    }

    //省新冠疫苗预防接种信息查询
    public function getBysxgymyfjzxxcx($id_card)
    {
        // test_log('start getBysxgymyfjzxxcx id_card:'.$id_card);
        $curl = new Curl();
        // 目前反向代理,为了测试和正式环境都能调用
        if(Config::get('app.app_host') == 'dev') { //测试服务器
            $url = "http://localhost:8083/ESBWeb/servlets/sxgymyfjzxxcx@1.0";
        }else{
            $url = "http://172.45.7.23/ESBWeb/servlets/sxgymyfjzxxcx@1.0";
        }
        $curl->get($url, [
            'idcardNo' => $id_card
        ]);
        if ($curl->error) {
            test_log('getBysxgymyfjzxxcx-'. $curl->error_code . ': ' . $curl->error_message);
            return ['status'=> 0, 'msg'=> $curl->error_code . ': ' . $curl->error_message];
        } else {
            $res = $curl->response;
            //test_log($res);
            $result = json_decode($res, true);
        }
        $curl->close();
        if($result['code'] == "00") {
            if(isset($result['datas']['data'])) {
                return ['status'=> 1, 'msg'=> $result['msg'], 'data'=> $result['datas']['data']];
            }
            return ['status'=> 0, 'msg'=> $result['msg']];
        }else{
            return ['status'=> 0, 'msg'=> $result['msg']];
        }
    }

    // 义乌省核酸检测接口
    public function getByshsjcjk($real_name, $id_card)
    {
        // test_log('start getByshsjcjk id_card:'.$id_card);
        $curl = new Curl();
        // 目前反向代理,为了测试和正式环境都能调用
        if(Config::get('app.app_host') == 'dev') { //测试服务器
            $url = "http://localhost:8083/ESBWeb/servlets/shsjcjk@1.0";
        }else{
            $url = "http://172.45.7.23/ESBWeb/servlets/shsjcjk@1.0";
        }
        // $curl->get($url, [
        //     'patientname' => $real_name,
        //     'sfzh' => $id_card
        // ]);

        $curl->get($url, [
            'idcardNo' => $id_card
        ]);

        if ($curl->error) {
            test_log('getByshsjcjk-'. $curl->error_code . ': ' . $curl->error_message);
            return ['status'=> 0, 'msg'=> $curl->error_code . ': ' . $curl->error_message];
        } else {
            $res = $curl->response;
            //var_dump($res);
            $result = json_decode($res, true);
        }
        $curl->close();
        if($result['code'] == "00") {
            return ['status'=> 1, 'msg'=> $result['msg'], 'data'=> $result['datas']];
        }else{
            return ['status'=> 0, 'msg'=> $result['msg']];
        }
    }
    
    //义乌核酸检测接口 ----此接口已废弃，改用getByshsjcjk
    public function getByywhsjcjk($real_name, $id_card)
    {
        // test_log('start getByywhsjcjk id_card:'.$id_card);
        //$curl = new Curl();
        // 目前反向代理,为了测试和正式环境都能调用
        if(Config::get('app.app_host') == 'dev') { //测试服务器
            $url = "http://localhost:8083/api/common";
        }else{
            $url = "http://172.45.188.8:8177/api/common";
        }
        $idcard = [
            'Person' => $real_name,
            'IDCard' => $id_card,
        ];
        $data = [
            'token' => 'f9563e318d5bda9af60dbca6c87d0291',
            'business' => 'COVID1002',
            'data' => $idcard,
        ];
        $res = $this->http_post_data($url, $data);
        //var_dump($res);
        $result = json_decode($res,true);
        if(isset($result['Code']) && $result['Code'] == "200") {
            if(isset($result['Data']['biz_data'])){
                return ['status'=> 1, 'msg'=> $result['Message'], 'data'=> $result['Data']['biz_data']['ReportDetails']];
            }else{
                return ['status'=> 0, 'msg'=> '数据为空'];
            }
        }else{
            test_log('getByywhsjcjk-'. $res);
            return ['status'=> 0, 'msg'=> $result['Message']];
        }
    }

    public function phoneToJkm($phone){
        // 目前反向代理,为了测试和正式环境都能调用
        $curl = new Curl();
        if(Config::get('app.app_host') == 'dev') { //测试服务器
            $url = "http://localhost:8083/ESBWeb/servlets/sjhcxjkm@1.0";
        }else{
            $url = "http://172.45.7.23/ESBWeb/servlets/sjhcxjkm@1.0";
        }
        $curl->get($url, [
            'sjh' => $phone
        ]);
        if ($curl->error) {
            test_log('getPhoneToJkm-'. $curl->error_code . ': ' . $curl->error_message);
            return ['status'=> 0, 'msg'=> $curl->error_code . ': ' . $curl->error_message];
        } else {
            // test_log('getPhoneToJkmv1-'. $curl->response);
            $res = $curl->response;
            $result = json_decode($res, true);
        }
        $curl->close();
        if($result['code'] == "00") {
            $datas = json_decode($result['datas'], true);
            if($datas['success'] == true) {
                if($datas['data'] == null || count($datas['data']) == 0) {
                    return ['status'=> 0, 'msg'=> '不存在'];
                }
                $last_item = $this->_getLastJkmPhoneRealCardIdItem($datas['data'],$phone);
                return ['status'=> 1, 'msg'=> '成功', 'data'=> $last_item];
            }else{
                return ['status'=> 0, 'msg'=> $datas['errorMsg']];
            }
        }else{
            if(isset($result['code']) && $result['code'] == "18"){
                system_error_log(__METHOD__,'手机号查询身份证接口超量',$result['msg']);
            }
            test_log('getPhoneToJkmv1-'. $curl->response);
            return ['status'=> 0, 'msg'=> $result['msg']];
        }
    }

    public function phoneToJkmV2($phone){
        // 目前反向代理,为了测试和正式环境都能调用
        $curl = new Curl();
        if(Config::get('app.app_host') == 'dev') { //测试服务器
            $url = "http://localhost:8083/ESBWeb/servlets/sjhcxjkm@1.0";
        }else{
            $url = "http://172.45.7.23/ESBWeb/servlets/sjhcxjkm@1.0";
        }
        $curl->get($url, [
            'sjh' => $phone
        ]);
        if ($curl->error) {
            test_log('getPhoneToJkm-'. $curl->error_code . ': ' . $curl->error_message);
            return ['status'=> 0, 'msg'=> $curl->error_code . ': ' . $curl->error_message];
        } else {
            
            $res = $curl->response;
            $result = json_decode($res, true);
        }
        $curl->close();
        if( isset($result['code']) && $result['code'] == "00") {
            $datas = json_decode($result['datas'], true);
            if($datas['success'] == true) {
                if($datas['data'] == null || count($datas['data']) == 0) {
                    return ['status'=> 0, 'msg'=> '不存在'];
                }
                $last_item = $this->_getCardIdArr($datas['data'],$phone);
                return ['status'=> 1, 'msg'=> '成功', 'data'=> $last_item];
            }else{
                return ['status'=> 0, 'msg'=> $datas['errorMsg']];
            }
        }else{
            if(isset($result['code']) && $result['code'] == "18"){
                system_error_log(__METHOD__,'手机号查询身份证接口超量',$result['msg']);
            }
            test_log('getPhoneToJkmv2-'. $curl->response);
            return ['status'=> 0, 'msg'=> $result['msg']];
        }
    }

    private function _getCardIdArr($data,$phone){
        $id_card_map = [];
        foreach($data as $key => $item){
            $id_card_map[$item['sfzh']]['sfzh'] = $item['sfzh'];
            $id_card_map[$item['sfzh']]['xm'] = $item['xm'];
            $id_card_map[$item['sfzh']]['sjh'] = $item['sjh'];
        }
        return array_values($id_card_map);
    }


    function http_post_data($url = '', $param = '')
    {
        if (empty($url) || empty($param)) {
            return false;
        }
        $postUrl = $url;
        $curlPost = $param;
        // 初始化curl
        $curl = curl_init();
        $header[] = "Content-type: application/json;charset=utf-8";//设置http报文头text/xml
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_URL, $postUrl);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        // 要求结果为字符串且输出到屏幕上
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        // post提交方式
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($curlPost));
        // 运行curl
        $data = curl_exec($curl);
        curl_close($curl);
    
        return $data;
    }
    
    public function enterpriseInfo($credit_code)
    {
        // 目前反向代理,为了测试和正式环境都能调用
        $curl = new Curl();
        if(Config::get('app.app_host') == 'dev') { //测试服务器
            $url = "http://localhost:8083/ESBWeb/servlets/hz_enterpriseInfo@1.0";
        }else{
            $url = "http://172.45.7.23/ESBWeb/servlets/hz_enterpriseInfo@1.0";
        }
        $curl->get($url, ['uniscId'=>$credit_code,'entType'=>'E']);
        if ($curl->error) {
            test_log('enterpriseInfo-'. $curl->error_code . ': ' . $curl->error_message);
            return ['status'=> 0, 'msg'=> $curl->error_code . ': ' . $curl->error_message];
        } else {
            $res = $curl->response;
            //test_log('enterpriseInfo-'. $res);
            $result = json_decode($res, true);
        }
        $curl->close();
        if($result['code'] == '00') {
            if(isset($result['datas'][0])) {
                return ['status'=> 1, 'msg'=> '成功', 'data'=> $result['datas'][0]];
            }
            return ['status'=> 0, 'msg'=> $result['msg']];
        }else{
            test_log('enterpriseInfo-error:'.$res);
            return ['status'=> 0, 'msg'=> $result['msg']];
        }
    }

}
