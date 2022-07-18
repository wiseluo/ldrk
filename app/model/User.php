<?php

namespace app\model;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\model\concern\SoftDelete;

/**
 * Class User
 * @package app\model\user
 */
class User extends BaseModel
{
    use ModelTrait;
    use SoftDelete;
    /**
     * @var string
     */
    protected $pk = 'id';

    protected $name = 'user';

    protected $autoWriteTimestamp = true;
    
    protected $deleteTime = 'delete_time';

    protected $type = [
        'delete_time'  =>  'timestamp',
    ];

    protected $hidden = [
        'add_ip', 'account', 'last_time', 'last_ip', 'pwd'
    ];

    public function getGenderList()
    {
        return [0=> '未知', 1=> '男', 2=> '女'];
    }

    public function getCardTypeList()
    {
        return ['id'=> '身份证号', 'passport'=> '护照号', 'officer'=> '军官证号','other'=>'其他'];
    }

    public function getDeclareTypeList()
    {
        return ['leave'=> '外出申报', 'come'=> '来返义申报', 'riskarea'=> '中高风险地区自主申报','return'=>'反义(旧)'];
    }

    public function getPositionTypeList()
    {
        return ['stay'=> '在义乌', 'leave'=> '离开义乌'];
    }

    public function getAgeAttr($value, $data)
    {
        if($data['birthday'] == null) {
            return '';
        }
        //获得出生年月日的时间戳
        $age = strtotime($data['birthday']);
        if($age === false) {
            return '';
        }
        //获得今日的时间戳
        $now = strtotime("now");
        list($y1,$m1,$d1) = explode("-",date("Y-m-d",$age));
        list($y2,$m2,$d2) = explode("-",date("Y-m-d",$now));
        $age = $y2 - $y1;
        if((int)($m2.$d2) < (int)($m1.$d1))
            $age -= 1;
        return $age;
    }

    public function getGenderTextAttr($value, $data){
        if(isset($data['gender']) && $data['gender'] != null){
            $arr = $this->getGenderList();
            return $arr[$data['gender']];
        }
        return '';
    }

    public function getCardTypeTextAttr($value, $data){
        if( isset($data['card_type']) && $data['card_type'] != null){
            $arr = $this->getCardTypeList();
            return $arr[$data['card_type']];
        }
        return '';
    }

    public function getDeclareTypeTextAttr($value, $data){
        if( isset($data['declare_type']) && $data['declare_type'] != null){
            $arr = $this->getDeclareTypeList();
            return $arr[$data['declare_type']];
        }
        return '';
    }

    public function getPositionTypeTextAttr($value, $data){
        if( isset($data['position_type']) && $data['position_type'] != null){
            $arr = $this->getPositionTypeList();
            return $arr[$data['position_type']];
        }
        return '';
    }

}
