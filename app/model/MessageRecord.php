<?php

namespace app\model;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
/**
 *  消息记录Model
 * Class SmsRecord
 * @package app\model\sms
 */
class MessageRecord extends BaseModel
{
    use ModelTrait;

    /**
     * 数据表主键
     * @var string
     */
    protected $pk = 'id';

    /**
     * 模型名称
     * @var string
     */
    protected $name = 'message_record';

    protected $autoWriteTimestamp = true;

    protected $status = [0 => '未发送', 1 => '成功', 2 => '失败'];
    protected $isread = [0 => '未读', 1 => '已读'];

    protected function getStatusTextAttr($value, $data)
    {
        return $this->status[$data['status']];
    }

    protected function getIsreadTextAttr($value, $data)
    {
        return $this->isread[$data['isread']];
    }
}
