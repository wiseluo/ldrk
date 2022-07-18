<?php

namespace app\model;

use crmeb\basic\BaseModel;
use think\model\concern\SoftDelete;
use crmeb\traits\ModelTrait;

//学校
class School extends BaseModel
{
    use ModelTrait;
    use SoftDelete;

    protected $pk = 'id';
    protected $name = 'school';
    protected $autoWriteTimestamp = true;
    protected $deleteTime = 'delete_time';

    public function getSchoolQrcodeArrAttr($value, $data)
    {
        if (isset($data['school_qrcode'])) {
            return [
                [
                    'name' => '默认',
                    'url'  => str_replace('_qrcode.png', '_qrcode_xx_v2.png', $data['school_qrcode']),
                    'width' => 300,
                    'height' => 534,
                ],
                [
                    'name' => '样式1',
                    'url'  => $data['school_qrcode'],
                    'width' => 300,
                    'height' => 300,
                ]
            ];
        } else {
            return [];
        }
    }
}
