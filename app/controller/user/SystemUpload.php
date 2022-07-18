<?php
namespace app\controller\user;

use think\facade\App;
use crmeb\basic\BaseController;
use crmeb\services\UploadService;
use crmeb\exceptions\UserException;
use think\facade\Config;

/**
 * 文件上传类
 * Class SystemUpload
 */
class SystemUpload extends BaseController
{
    protected $service;

    public function __construct(App $app, UploadService $service)
    {
        parent::__construct($app);
        $this->service = $service;
    }

    /**
     * 上传临时文件
     * @param int $upload_type
     * @param int $type
     * @return mixed
     */
    public function tmp()
    {
        $upload_type = 1; // 本地保存
        try {
            $path = make_path('tmp', 2, true);
            $upload = UploadService::init($upload_type);
            $res = $upload->to($path)->setAuthThumb(false)->validate()->move('file');
            if ($res === false) {
                throw new UserException($upload->getError(), 400);
            } else {
                //$fileInfo = $upload->getUploadInfo();
                //$fileType = pathinfo($fileInfo['name'], PATHINFO_EXTENSION);s
                return show(200, '上传成功', ['src' => $res->filePath]);
            }
        } catch (\Exception $e) {
            throw new UserException($e->getMessage(), 400);
        }
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


            $img_len = strlen($base64_img);
            $file_size = $img_len - ($img_len / 8) * 2;
            if ($file_size > (1024 * 1024 * 5)) {
                return show(400, '图片大于5M');
            }

            $type = $result[2];
            if(in_array($type, array('png','jpg','jpeg','gif','bmp'))) {
                $filename = uniqid() .'.'. $type;
                $path = make_path(Config::get('upload.at_server') . '/attach', 2, true);
                $src = '/uploads'. $path .'/'. $filename;
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
