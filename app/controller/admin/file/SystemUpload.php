<?php
namespace app\controller\admin\file;

use app\controller\admin\AuthController;
use app\services\admin\system\attachment\SystemUploadServices;
use think\facade\App;

/**
 * 文件上传类
 * Class SystemUpload
 * @package app\controller\admin\file
 */
class SystemUpload extends AuthController
{
    protected $service;

    public function __construct(App $app, SystemUploadServices $service)
    {
        parent::__construct($app);
        $this->service = $service;
    }

    /**
     * 上传附件
     * @param int $upload_type
     * @param int $type
     * @return mixed
     */
    public function attach()
    {
        $res = $this->service->upload('attach', 'file');
        return show(200, '上传成功', ['src' => $res]);
    }

    /**
     * 上传临时文件
     * @param int $upload_type
     * @param int $type
     * @return mixed
     */
    public function tmp()
    {
        $res = $this->service->upload('tmp', 'file');
        return show(200, '上传成功', ['src' => $res]);
    }

    //base64编码上传图片
    public function base64Img() {
        // 获取上传文件
        $file = request()->post('file');
        if (!$file) {
            return show(400, '文件必填');
        }
        $base64_img = trim($file);
        if(preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_img, $result)) {
            $type = $result[2];
            if(in_array($type, array('png','jpg','jpeg','gif','bmp'))) {
                $filename = uniqid() .'.'. $type;
                $path = make_path('attach', 2, true);
                $src = '/uploads' .$path .'/'. $filename;
                $file_path = app()->getRootPath() .'public'. $src;
                if(file_put_contents($file_path, base64_decode(str_replace($result[1], '', $base64_img)))) {
                    return show(200, '上传成功', ['src' => $src]);
                }else{
                    return show(400, '图片上传失败');
                }
            }else{
                return show(400, '图片上传类型错误');
            }
        }else{
            return show(400, '参数格式错误');
        }
    }
}
