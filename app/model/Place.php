<?php

namespace app\model;

use crmeb\basic\BaseModel;
use think\model\concern\SoftDelete;
use crmeb\traits\ModelTrait;

class Place extends BaseModel
{
    use ModelTrait;
    use SoftDelete;

    protected $pk = 'id';

    protected $name = 'place';

    protected $autoWriteTimestamp = true;
    
    protected $deleteTime = 'delete_time';

    public function getPlaceClassifyList()
    {
        return ['gov'=> '党政机关', 'company'=> '企业', 'unit'=> '事业单位', 'social'=> '社会组织', 'other'=> '其他'];
    }

    public function getPlaceClassifyTextAttr($value, $data){
        $arr = $this->getPlaceClassifyList();
        return $arr[$data['place_classify']];
    }

    public function getAppletQrcodeArrAttr($value, $data){
        if(isset($data['applet_qrcode'])){
            return [
                [
                    'name' => '默认',
                    'url'  => $data['applet_qrcode'],
                    'width' => 300,
                    'height' => 300,
                ],
                [
                    'name' => '样式1',
                    'url'  => str_replace('_qrcode.png', '_qrcode_v2.png', $data['applet_qrcode']).'?time='.time(),
                    'width' => 300,
                    'height' => 534,
                ]
            ];
        }else{
            return [];
        }
    }

}
