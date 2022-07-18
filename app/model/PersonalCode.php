<?php

namespace app\model;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;

class PersonalCode extends BaseModel
{
    use ModelTrait;

    protected $pk = 'id';
    protected $name = 'user_personal_code';
    protected $autoWriteTimestamp = true;

    public function getPersonalQrcodeArrAttr($value, $data){
        if(isset($data['qrcode'])){
            return [
                [
                    'name' => '默认',
                    'url'  => str_replace('_qrcode.png', '_qrcode_gr_v2.png', $data['qrcode']).'?time='.time(),
                    'width' => 300,
                    'height' => 534,
                ],
                [
                    'name' => '样式1',
                    'url'  => $data['qrcode'],
                    'width' => 300,
                    'height' => 300,
                ]
            ];
        }else{
            return [];
        }
    }
}
