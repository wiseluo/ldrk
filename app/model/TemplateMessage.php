<?php

namespace app\model;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;

/**
 *  消息模板Model
 * Class TemplateMessage
 * @package app\model\other
 */
class TemplateMessage extends BaseModel
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
    protected $name = 'template_message';
    protected $autoWriteTimestamp = true;

}
