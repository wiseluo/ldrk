<?php

namespace app\model;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use app\model\MessageRecord;
use think\facade\Config;

/**
 * Class User
 * @package app\model\user
 */
class OwnDeclare extends BaseModel
{
    use ModelTrait;
    
    protected $pk = 'id';

    protected $name = 'declare';

    protected $autoWriteTimestamp = true;
    
    public function getRiskLevelList()
    {
        return ['low'=> '系低风险', 'middling'=> '系中风险', 'high'=> '系高风险'];
    }

    public function getArrivalTransportModeList()
    {
        return ['train'=> '火车', 'aircraft'=> '飞机', 'car'=> '汽车'];
    }

    public function getRiskLevelTextAttr($value, $data){
        $arr = $this->getRiskLevelList();
        return $arr[$data['risk_level']];
    }

    public function getArrivalTransportModeTextAttr($value, $data){
        if(isset($data['arrival_transport_mode']) && $data['arrival_transport_mode'] != '') {
            $arr = $this->getArrivalTransportModeList();
            return $arr[$data['arrival_transport_mode']];
        }
        return '';
    }

    public function getDestinationAttr($value, $data){
        if(isset($data['province'])){
            return $data['province'].$data['city'].$data['county'].$data['street'];
        }
        return '';
    }

    public function getTravelImgAttr($value, $data){
        if(isset($data['travel_img'])  ){
            if( strstr($data['travel_img'],'server112') || 
                strstr($data['travel_img'],'server114') ||  
                strstr($data['travel_img'],'server118') ||
                strstr($data['travel_img'],'server96') ||
                strstr($data['travel_img'],'server97') ||
                strstr($data['travel_img'],'server95')

                ){
                if(Config::get('app.app_host') == 'dev'){
                    return 'https://yqfk.yw.gov.cn'.$data['travel_img'];
                }else{
                    return $data['travel_img'];
                }
            }
            return $data['travel_img'];
        }
        return '';
    }


    public function getErrorInfosAttr($value, $data){
        $error_info = '';
        if(isset($data['id_verify_result']) && $data['id_verify_result'] == 2){
            $error_info .= '身份证信息有误';
        }
        if(isset($data['ocr_result']) && $data['ocr_result'] == 2){
            $error_info .= ',行程码识别有误';
        }
        return trim($error_info,',');
    }

    public function getCardTypeList()
    {
        return ['id'=> '身份证', 'passport'=> '护照', 'officer'=> '军官证'];
    }

    public function getCardTypeTextAttr($value, $data){
        if(isset($data['card_type'])){
            $arr = $this->getCardTypeList();
            return $arr[$data['card_type']];
        }
        return '';
    }

    public function getWarningFromTextAttr($value, $data){
        if(isset($data['declare_type'] ) && $data['declare_type'] == 'riskarea'){
            return '自主申报';
        }
        if(isset($data['is_warning'] ) && $data['is_warning'] > 0){
            if($data['declare_type'] == 'riskarea'){
                return '自主申报';
            }
            if($data['declare_type'] != 'riskarea'){
                return '系统判定';
            }
        }
        return '';
    }

    // todo
    public function getWarningRuleTextAttr($value, $data){
        return '【此处待优化，假数据】202109.10-2021.09.20 途经城市苏州为中高风险地区';
    }

    public function getIsToOaAttr($value, $data){
        if(isset($data['control_state'] )){
            if( $data['control_state'] != ''){
                return '已推送';
            }
            return '未获取';
        }
        return '';
    }
}
