<?php

namespace app\controller\user;

use crmeb\basic\BaseController;
use \behavior\SmsTool;
use app\services\user\AliyunServices;
use \behavior\AliyunTool;

class Test extends BaseController
{
    public function index()
    {
        try {
            var_dump(strtotime('2021-01-01') + 86399);
            
        } catch (\Throwable $e) {
            $e->getMessage();
        }
    }
    
    public function sendcs()
    {
        $smsTool = new SmsTool();
        $res =$smsTool->sendSms('13625896500', '您好：您于预约了核酸检测，请准时参加。');
        //var_dump($res);
        if($res['status'] == 1) {
            return show(200, '发送sms成功');
        }else{
            return show(400, '发送sms失败');
        }
    }

    //图片ocr识别
    public function imgOcr()
    {
        $img_path = request()->param('img_path', '');
        $aliyunTool = new AliyunTool();
        $res = $aliyunTool->imgOcrGeneral($img_path);
        return show(200, '操作成功', $res);
    }
    
    //身份证ocr识别
    public function idcardOcr()
    {
        $img_path = request()->param('img_path', '');
        $aliyunTool = new AliyunTool();
        $res = $aliyunTool->idcardocr($img_path);
        return show(200, '操作成功', $res);
    }
}

