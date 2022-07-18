<?php

namespace app\services;

use app\dao\UserDao;
use Curl\Curl;
use think\facade\Config;
use think\facade\Db;

//个人码异步任务服务
class PersonalCodeTaskServices
{

    //个人码加水印
    public function personalQrcodeWatermarkService($param)
    {
        $url = '';
        $at_server = Config::get('upload.at_server');
        if($at_server == 'dev') { //测试服务器
            $this->personalQrcodeWatermarkHandleService($param['file_path'], $param['version'], $param['name']);
            //$url = 'https://ldrk.jk-kj.com/wx_qrcode/watermark_personal';
        }else{
            if(strstr($param['file_path'], 'server112')) {
                if($at_server == 'server112') { //本服务器
                    $this->personalQrcodeWatermarkHandleService($param['file_path'], $param['version'], $param['name']);
                }else{
                    $url = 'http://172.45.4.112:30399/wx_qrcode/watermark_personal';
                }
            }else if(strstr($param['file_path'], 'server118')) {
                if($at_server == 'server118') { //本服务器
                    $this->personalQrcodeWatermarkHandleService($param['file_path'], $param['version'], $param['name']);
                }else{
                    $url = 'http://172.45.4.118:30399/wx_qrcode/watermark_personal';
                }
            }else if(strstr($param['file_path'], 'server114')) {
                if($at_server == 'server114') { //本服务器
                    $this->personalQrcodeWatermarkHandleService($param['file_path'], $param['version'], $param['name']);
                }else{
                    $url = 'http://172.45.4.101:30399/wx_qrcode/watermark_personal';
                }
            }else if(strstr($param['file_path'], 'server96')) {
                if($at_server == 'server96') { //本服务器
                    $this->personalQrcodeWatermarkHandleService($param['file_path'], $param['version'], $param['name']);
                }else{
                    $url = 'http://172.45.253.96:30399/wx_qrcode/watermark_personal';
                }
            }else if(strstr($param['file_path'], 'server97')) {
                if($at_server == 'server97') { //本服务器
                    $this->personalQrcodeWatermarkHandleService($param['file_path'], $param['version'], $param['name']);
                }else{
                    $url = 'http://172.45.253.97:30399/wx_qrcode/watermark_personal';
                }
            }else if(strstr($param['file_path'], 'server95')) {
                if($at_server == 'server95') { //本服务器
                    $this->personalQrcodeWatermarkHandleService($param['file_path'], $param['version'], $param['name']);
                }else{
                    $url = 'http://172.45.253.95:30399/wx_qrcode/watermark_personal';
                }
            }
        }
        if($url == '') {
            return true;
        }
        $curl = new Curl();
        $data = [
            'file_path' => $param['file_path'],
            'version' => $param['version'],
            'name' => $param['name'],
        ];
        $curl->post($url, $data);
        if($curl->error) {
            test_log('跨服务器生成个人码失败-'. $curl->error .': '. $curl->error_message);
        }
        return true;
    }
    
    public function personalQrcodeWatermarkHandleService($file_path, $version, $name)
    {
        try{
            $file_path = app()->getRootPath() .'public'. $file_path;
            $watermark_file_path = str_replace('_qrcode.png', '_qrcode_gr_'. $version .'.png', $file_path);
            $dst_path = app()->getRootPath() .'public/file/image/wxqrcode_personal_bg'. $version .'.png'; // 背景图
            if($version == 'v2') {
                $this->wxQrcodeMerge($file_path, $watermark_file_path, $dst_path, 215, 755);
                $this->wxQrcodeText($watermark_file_path, $watermark_file_path, $name, 120, 470);
            }
            
        } catch (\Exception $e) {
            test_log('个人防疫码加水印失败-'. $e->getMessage());
        }
    }

    //合并图像
    public function wxQrcodeMerge($fromfile, $tofile, $dst_path, $dst_x=0, $dst_y=0)
    {
        $src_path = $fromfile; // 二维码图
        //创建图片的实例
        $dst = imagecreatefromstring(file_get_contents($dst_path));
        $src = imagecreatefromstring(file_get_contents($src_path));
        // 把二维码图片的白色背景设为透明
        imagecolortransparent($src, imagecolorallocate($src, 255, 255, 255));
        //获取水印图片的宽高
        list($src_w, $src_h) = getimagesize($src_path);
        //var_dump(getimagesize($src_path));
        //将水印图片复制到目标图片上
        imagecopymerge($dst, $src, $dst_x, $dst_y, 0, 0, $src_w, $src_h, 100);
        //生成图片
        imagepng($dst, $tofile);
        //销毁
        imagedestroy($dst);
        imagedestroy($src);
    }

    //向不同格式的图片画一个字符串（也是文字水印）
    public function wxQrcodeText($fromfile, $tofile, $name, $x=0, $y=0)
    {
        //获取图片的属性，第一个宽度，第二个高度，类型1=>gif，2=>jpeg,3=>png
        list($width,$height,$type) = getimagesize($fromfile);
        //可以处理的图片类型
        $types = array(1=>"gif",2=>"jpeg",3=>"png",);
        //通过图片类型去组合，可以创建对应图片格式的，创建图片资源的GD库函数
        $createfrom = "imagecreatefrom".$types[$type];
        //通过“变量函数”去打对应的函数去创建图片的资源
        $image = $createfrom($fromfile);

        //设置字体的颜色为黑色
        $textcolor = imagecolorallocate($image, 0, 0, 0);
        $font = app()->getRootPath() .'public/file/fonts/Songti.ttc'; //字体在服务器上的绝对路径
        //设置居中字体的X轴坐标位置
        //$x = ($width-imagefontwidth(5)*strlen($string))/2;
        //设置居中字体的Y轴坐标位置
        //$y = ($height-imagefontheight(5))/1.18;

        $str_arr = explode('-', $name);

        $x = 300;
        imagefttext($image, 35, 0, $x, $y, $textcolor, $font, $str_arr[0]);
        $x = 260;
        $y = 535;
        imagefttext($image, 30, 0, $x, $y, $textcolor, $font, $str_arr[1]);
        
        //通过图片类型去组合保存对应格式的图片函数
        $output = "image".$types[$type];
        //通过变量函数去保存对应格式的图片
        $output($image,$tofile);
        imagedestroy($image);
    }
}
