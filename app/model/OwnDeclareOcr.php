<?php

namespace app\model;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;

/**
 * Class User
 * @package app\model\user
 */
class OwnDeclareOcr extends BaseModel
{
    use ModelTrait;
    
    protected $pk = 'id';

    protected $name = 'declare_ocr';

    protected $autoWriteTimestamp = true;
    
    public function getTravelImgAttr($value, $data){
        if(isset($data['travel_img'])  ){
            if( strstr($data['travel_img'],'server112') || 
                strstr($data['travel_img'],'server114') || 
                strstr($data['travel_img'],'server118') ||
                strstr($data['travel_img'],'server96') ||
                strstr($data['travel_img'],'server97') ||
                strstr($data['travel_img'],'server95')
                ){
                return 'https://yqfk.yw.gov.cn'.$data['travel_img'];
            }
            return $data['travel_img'];
        }
        return '';
    }
}
