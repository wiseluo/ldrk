<?php

namespace app\services\admin;

use app\dao\TemplateMessageDao;
use app\services\admin\BaseServices;

/**
 * 模板消息
 * Class TemplateMessageServices
 */
class TemplateMessageServices extends BaseServices
{
    /**
     * 模板消息
     * TemplateMessageServices constructor.
     * @param TemplateMessageDao $dao
     */
    public function __construct(TemplateMessageDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * 获取模板消息列表
     */
    public function indexService($param)
    {
        return $this->dao->getTemplateList($param);
    }

    public function readService($id)
    {
        $data = $this->dao->get($id)->append(['receiver_text']);
        if($data) {
            return ['status' => 1, 'msg'=> '成功', 'data'=> $data];
        }else{
            return ['status' => 0, 'msg'=> '不存在'];
        }
    }

    /**
     * 获取模板消息内容
     */
    public function getContentService($tempkey)
    {
        return $this->dao->value(['tempkey' => $tempkey], 'content');
    }

    public function updateService($param, $id)
    {
        $data = [
            'content'=> $param['content'],
        ];
        try {
            $this->dao->update($id, $data);
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => '操作失败-'. $e->getMessage()];
        }
    }
}
