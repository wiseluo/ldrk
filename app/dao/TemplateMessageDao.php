<?php

namespace app\dao;

use app\dao\BaseDao;
use app\model\TemplateMessage;

/**
 * 模板消息
 * Class TemplateMessageDao
 * @package app\dao\other
 */
class TemplateMessageDao extends BaseDao
{
    /**
     * 设置模型
     * @return string
     */
    protected function setModel(): string
    {
        return TemplateMessage::class;
    }

    /**
     * 获取模板消息列表
     */
    public function getTemplateList($param)
    {
        $where = [];

        return $this->getModel()::where($where)
            ->append(['receiver_text'])
            ->paginate($param['size'])
            ->toArray();
    }

}
