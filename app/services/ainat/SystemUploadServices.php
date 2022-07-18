<?php
declare (strict_types=1);

namespace app\services\ainat;

use app\services\ainat\BaseServices;
use crmeb\exceptions\AdminException;
use crmeb\services\UploadService;

/**
 *
 * Class SystemUploadServices
 */
class SystemUploadServices extends BaseServices
{
    /**
     * 文件上传
     * @param string $path_name
     * @param string $file
     * @return mixed
     */
    public function upload(string $path_name, string $file)
    {
        $upload_type = 1; // 本地保存
        try {
            $path = make_path($path_name, 2, true);
            $upload = UploadService::init($upload_type);
            $res = $upload->to($path)->setAuthThumb(false)->validate()->move($file);
            if ($res === false) {
                throw new AdminException($upload->getError(), 400);
            } else {
                //$fileInfo = $upload->getUploadInfo();
                //$fileType = pathinfo($fileInfo['name'], PATHINFO_EXTENSION);
                return $res->filePath;
            }
        } catch (\Exception $e) {
            throw new AdminException($e->getMessage(), 400);
        }
    }

}
