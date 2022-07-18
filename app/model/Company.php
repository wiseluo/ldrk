<?php

namespace app\model;

use crmeb\basic\BaseModel;
use think\model\concern\SoftDelete;
use crmeb\traits\ModelTrait;
use app\dao\CompanyClassifyDao;

//企业
class Company extends BaseModel
{
    use ModelTrait;
    use SoftDelete;

    protected $pk = 'id';
    protected $name = 'company';
    protected $autoWriteTimestamp = true;
    protected $deleteTime = 'delete_time';

    public function getCompanyQrcodeArrAttr($value, $data){
        if(isset($data['company_qrcode'])){
            return [
                [
                    'name' => '默认',
                    'url'  => str_replace('_qrcode.png', '_qrcode_qy_v2.png', $data['company_qrcode']).'?time='.time(),
                    'width' => 300,
                    'height' => 534,
                ],
                [
                    'name' => '样式1',
                    'url'  => $data['company_qrcode'],
                    'width' => 300,
                    'height' => 300,
                ]
            ];
        }else{
            return [];
        }
    }

    // public static function getCompanyClassifyList()
    // {
    //     return ['company'=> '工业企业', 'lawoffice'=> '律师事务所',
    //      'logistics'=> '快递物流企业','business'=>'经营性场所',
    //      'build'=>'建筑工地','education'=>'教育培训机构','other'=>'其他',
    //      'gov'=> '机关、事业单位和国有企业',
    //      'importcompany' => '进口流通型企业',
    //      'nursing' => '养老机构',
    //      'nearroads' => '国省道附近经营性场所',
    //     //  'stateown' => '国有企业',
    //     //  'units' => '事业单位'
    //     ];
    // }

    // public function getCompanyClassifyNameAttr($value, $data){
    //     if(isset($data['company_classify']) && $data['company_classify'] != '' ){
    //         $arr = self::getCompanyClassifyList();
    //         return $arr[$data['company_classify']];
    //     }
    //     return '';
    // }

    //企业类型
    public function getClassifyNameAttr($value,$data) {
        if(isset($data['classify_id'])){
            $res = app()->make(CompanyClassifyDao::class)->get($data['classify_id']);
            if($res) {
                return $res['classify_name'];
            } else {
                return 0;
            }
        } else{
            return 0;
        }
    }

}
