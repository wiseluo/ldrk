<?php

namespace behavior;

use Curl\Curl;
use think\facade\Config;

class AliyunTool
{
    //图片OCR文字识别
    public function imgOcrGeneral($img_path)
    {
        $curl = new Curl();
        $url = 'http://tysbgpu.market.alicloudapi.com/api/predict/ocr_general';
        // $fp = fopen($img_path, "rb", 0);
        // $binary = fread($fp, filesize($img_path)); // 文件读取
        // fclose($fp); 
        
        if(Config::get('app.app_host') == 'dev') {
            $binary = file_get_contents('https://ldrk.jk-kj.com'. $img_path);
        }else{
            if(strstr($img_path, 'server112')) {
                $binary = file_get_contents(public_path().$img_path);
            }else if(strstr($img_path, 'server118')) {
                $img_path = str_replace('/uploads/server118', '', $img_path);
                $binary = file_get_contents('http://172.45.4.118:8080'.$img_path);
            }else if(strstr($img_path, 'server114')) {
                $img_path = str_replace('/uploads/server114', '', $img_path);
                $binary = file_get_contents('http://172.45.4.101:8080'.$img_path);
            }else if(strstr($img_path, 'server96')) {
                $img_path = str_replace('/uploads/server96', '', $img_path);
                $binary = file_get_contents('http://172.45.253.96:8080'.$img_path);
            }else if(strstr($img_path, 'server97')) {
                $img_path = str_replace('/uploads/server97', '', $img_path);
                $binary = file_get_contents('http://172.45.253.97:8080'.$img_path);
            }else if(strstr($img_path, 'server95')) {
                $img_path = str_replace('/uploads/server95', '', $img_path);
                $binary = file_get_contents('http://172.45.253.95:8080'.$img_path);
            }
        }
        if($binary == false) {
            return ['status'=> 0, 'msg'=> '图片未找到'];
        }
        $base64_img = base64_encode($binary); // 转码
        $data = [
            "image"=> $base64_img, #"图片二进制数据的base64编码/图片url"，
            "configure"=> 
                [
                    "min_size" => 16,                        #图片中文字的最小高度，单位像素
                    "output_prob" => true,                   #是否输出文字框的概率
                    "output_keypoints" => false,              #是否输出文字框角点 
                    "skip_detection" => false,                #是否跳过文字检测步骤直接进行文字识别
                    "without_predicting_direction" => false,  #是否关闭文字行方向预测
                    "language" => "sx"                        #当skip_detection为true时，该字段才生效，做单行手写识别。
                ]
        ];
        $data_json = json_encode($data);
        $curl->setHeader('Authorization', 'APPCODE '. Config::get('aliyuncloud.AppCode'));
        $curl->setHeader('Content-Type', 'application/json; charset=UTF-8');
        $curl->post($url, $data_json);

        if($curl->error) {
            test_log('AliyunTool imgOcrGeneral post error:'.$curl->response);
            return ['status'=> 0, 'msg'=> $curl->error_code . $curl->error_message];
        }else{
            $res = $curl->response;
            $result = json_decode($res, true);
            //var_dump($result);
        }
        $curl->close();
        if($result['success'] == true) {
            return ['status'=> 1, 'msg'=> '识别成功', 'data'=> $result];
        }else{
            return ['status'=> 0, 'msg'=> 'ocr识别失败'];
        }
    }

    //身份证OCR识别
    public function idcardocr($img_path)
    {
        $curl = new Curl();
        $url = 'http://yixi.market.alicloudapi.com/ocr/idcardocr';
        if(Config::get('app.app_host') == 'dev') {
            $binary = file_get_contents('https://ldrk.jk-kj.com'. $img_path);
        }else{
            if(strstr($img_path, 'server112')) {
                $binary = file_get_contents(public_path().$img_path);
            }else if(strstr($img_path, 'server118')) {
                $img_path = str_replace('/uploads/server118', '', $img_path);
                $binary = file_get_contents('http://172.45.4.118:8080'.$img_path);
            }else if(strstr($img_path, 'server114')) {
                $img_path = str_replace('/uploads/server114', '', $img_path);
                $binary = file_get_contents('http://172.45.4.101:8080'.$img_path);
            }else if(strstr($img_path, 'server96')) {
                $img_path = str_replace('/uploads/server96', '', $img_path);
                $binary = file_get_contents('http://172.45.253.96:8080'.$img_path);
            }else if(strstr($img_path, 'server97')) {
                $img_path = str_replace('/uploads/server97', '', $img_path);
                $binary = file_get_contents('http://172.45.253.97:8080'.$img_path);
            }else if(strstr($img_path, 'server95')) {
                $img_path = str_replace('/uploads/server95', '', $img_path);
                $binary = file_get_contents('http://172.45.253.95:8080'.$img_path);
            }
        }
        if($binary == false) {
            return ['status'=> 0, 'msg'=> '图片未找到'];
        }
        $base64_img = base64_encode($binary); // 转码
        $data = [
            "image"=> $base64_img, #"图片二进制数据的base64编码/图片url"，
            "side"=> 'front',
        ];
        //$data_json = json_encode($data);
        $curl->setHeader('Authorization', 'APPCODE '. Config::get('aliyuncloud.AppCode'));
        $curl->setHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
        $curl->post($url, $data);

        if($curl->error) {
            test_log('AliyunTool idcardocr post:'. $curl->error_code . $curl->error_message);
            return ['status'=> 0, 'msg'=> $curl->error_code . $curl->error_message];
        }else{
            $res = $curl->response;
            $result = json_decode($res, true);
            //var_dump($result);
        }
        $curl->close();
        // {
        //     "code": 200,
        //     "msg": "成功",
        //     "data": {
        //         "name": "骆智超",
        //         "nation": "汉",
        //         "address": "浙江省义乌市苏溪镇西山下村丁界",
        //         "idcard": "330782198910022512",
        //         "birthday": "19891002",
        //         "gender": "男",
        //         "photo": "\/9j\/4AAQSkZJRgABAQAAAQABAAD\/2wBDAAMCAgMCAgMDAwMEAwMEBQgFBQQEBQo..."
        //     },
        //     "ordersign": 1641976286
        // }
        if($result['code'] == 200) {
            return ['status'=> 1, 'msg'=> '识别成功', 'data'=> $result['data']];
        }else{
            return ['status'=> 0, 'msg'=> '身份证ocr识别失败-'. $result['msg']];
        }
    }
}