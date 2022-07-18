<?php

namespace app\controller;

use think\facade\Db;
use \behavior\QrcodeTool;
use think\facade\Config;
use think\facade\Cache;
use Swoole\Coroutine;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use app\services\PlaceServices;
use app\services\CompanyTaskServices;
use app\services\PersonalCodeTaskServices;


class BuildAppletCodeController
{

    public function buildAppletCodeByUserId(){
        $param = request()->param();
        $uniqid = isset($param['uniqid']) ? $param['uniqid'] : '0';
        // $jkm_mzt = isset($param['jkm_mzt']) ? $param['jkm_mzt'] : '绿码';
        $qrcode_color = isset($param['qrcode_color']) ? $param['qrcode_color'] : '';
        if(!in_array($qrcode_color,['green','yellow','red'])){
            $qrcode_color = 'green';
        }
        $qrcode_color = 'red';
        // if($jkm_mzt == '绿码') {
        //     $qrcode_color = 'green';
        // }else if($jkm_mzt == '黄码') {
        //     $qrcode_color = 'yellow';
        // }else if($jkm_mzt == '红码') {
        //     $qrcode_color = 'red';
        // }else{
        //     $qrcode_color = 'green';
        // }
        $filename = $uniqid.'_'.$qrcode_color.'.png';
        $uploads_dir = "uploads/".Config::get('upload.at_server')."/usercode/".Date('Ym')."/".substr($uniqid,0,2);
        $file_dir = app()->getRootPath()."public/". $uploads_dir;
        $path = $file_dir.'/'.$filename;
        if(!is_dir($file_dir)) {
            mkdir($file_dir, 0755, true);
        }
        $url = base64_encode($uniqid);

        // $command = sprintf("  qrcode '%s' > %s ",$url,$path);
        // $value   = rtrim(shell_exec($command));
        // // test_log("qrcode 'homepage: https://github.com/skip2/go-qrcode'");

        //启动协程
        Coroutine::create(function () use ($url,$qrcode_color,$path) {
            if($qrcode_color == 'green') {
                $foregroundColor = new Color(94, 163, 108);
            }else if($qrcode_color == 'yellow') {
                $foregroundColor = new Color(251, 144, 29);
            }else if($qrcode_color == 'red') {
                $foregroundColor = new Color(255, 0, 0);
            }else{
                $foregroundColor = new Color(94, 163, 108);
            }
            $writer = new PngWriter();
            // Create QR code
            $qrCode = QrCode::create($url)
                ->setEncoding(new Encoding('UTF-8'))
                ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
                ->setSize(300)
                ->setMargin(10)
                ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
                ->setForegroundColor($foregroundColor)
                ->setBackgroundColor(new Color(255, 255, 255));

            $result = $writer->write($qrCode);
            $result->saveToFile($path);
        });
        echo $uploads_dir.'/'.$filename;
        return show(200,'ok',$uploads_dir.'/'.$filename);
        // $param = request()->param();
    }

    // 场所码加水印
    public function watermark()
    {
        $file_path = request()->param('file_path', '');
        $version = request()->param('version', '');
        $name = request()->param('name', '');
        if($file_path == '' || $version == '' || $name == '') {
            return show(400, '参数错误');
        }
        app()->make(PlaceServices::class)->placeQrcodeWatermarkHandleService($file_path, $version, $name);
        return show(200,'成功', $file_path);
    }
    
    // 企业码加水印
    public function watermarkCompany()
    {
        $file_path = request()->param('file_path', '');
        $version = request()->param('version', '');
        $name = request()->param('name', '');
        if($file_path == '' || $version == '' || $name == '') {
            return show(400, '参数错误');
        }
        app()->make(CompanyTaskServices::class)->companyQrcodeWatermarkHandleService($file_path, $version, $name);
        return show(200,'成功', $file_path);
    }

    // 校园码加水印
    public function watermarkSchool()
    {
        $file_path = request()->param('file_path', '');
        $version = request()->param('version', '');
        $name = request()->param('name', '');
        if($file_path == '' || $version == '' || $name == '') {
            return show(400, '参数错误');
        }
        app()->make(SchoolTaskServices::class)->schoolQrcodeWatermarkHandleService($file_path, $version, $name);
        return show(200,'成功', $file_path);
    }
    
    // 个人码加水印
    public function watermarkPersonal()
    {
        $file_path = request()->param('file_path', '');
        $version = request()->param('version', '');
        $name = request()->param('name', '');
        if($file_path == '' || $version == '' || $name == '') {
            return show(400, '参数错误');
        }
        app()->make(PersonalCodeTaskServices::class)->personalQrcodeWatermarkHandleService($file_path, $version, $name);
        return show(200,'成功', $file_path);
    }
}