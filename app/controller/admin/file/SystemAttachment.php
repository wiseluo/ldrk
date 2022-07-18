<?php
namespace app\controller\admin\file;

use app\controller\admin\AuthController;
use app\services\admin\system\attachment\SystemAttachmentServices;
use think\facade\App;
use crmeb\exceptions\UploadException;
/**
 * 图片管理类
 * Class SystemAttachment
 * @package app\controller\admin\file
 */
class SystemAttachment extends AuthController
{
    protected $service;

    public function __construct(App $app, SystemAttachmentServices $service)
    {
        parent::__construct($app);
        $this->service = $service;
    }

    /**
     * 显示列表
     * @return mixed
     */
    public function index()
    {
        $where = $this->request->getMore([
            ['pid', 0]
        ]);
        return $this->success($this->service->getImageList($where));
    }

    /**
     * 删除指定资源
     *
     * @param string $ids
     * @return \think\Response
     */
    public function delete()
    {
        [$ids] = $this->request->postMore([
            ['ids', '']
        ], true);
        $this->service->del($ids);
        return $this->success('删除成功');
    }

    /**
     * 图片上传
     * @param int $upload_type
     * @param int $type
     * @return mixed
     */
    public function upload($upload_type = 0, $type = 0)
    {
        [$pid, $file] = $this->request->postMore([
            ['pid', 0],
            ['file', 'file'],
        ], true);
        try {
            $res = $this->service->upload((int)$pid, $file, $upload_type, $type);
        } catch (\Exception $e) {
            throw new UploadException($e->getMessage());
        }
        return $this->success('上传成功', ['src' => $res]);
    }

    /**
     * 移动图片
     * @return mixed
     */
    public function moveImageCate()
    {
        $data = $this->request->postMore([
            ['pid', 0],
            ['images', '']
        ]);
        $this->service->move($data);
        return $this->success('移动成功');
    }

    /**
     * 修改文件名
     * @param $id
     * @return mixed
     */
    public function update($id)
    {
        $realName = $this->request->post('real_name', '');
        if (!$realName) {
            return $this->fail('文件名称不能为空');
        }
        $this->service->update($id, ['real_name' => $realName]);
        return $this->success('修改成功');
    }

}
