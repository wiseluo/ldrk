<?php

namespace app\validate\user;

use think\Validate;
use \behavior\IdentityCardTool;

class OwnDeclareValidate extends Validate
{
    protected $rule = [
        'real_name' => 'require|min:2|checkRealName',
        'card_type' => 'require|in:id,passport,officer',
        'id_card' => 'require|max:20|checkIdCard',
        'phone' => 'require|checkPhone',
        'sms_code' => 'require|length:6',
        'province_id' => 'require|integer',
        'province' => 'require',
        // 'city_id' => 'require|integer',
        // 'city' => 'require',
        // 'county_id' => 'require|integer',
        // 'county' => 'require',
        'leave_time' => 'require|dateFormat:Y-m-d',
        'expect_return_time' => 'require|dateFormat:Y-m-d|checkReturnTime',
        'yw_street_id' => 'require|integer',
        'yw_street' => 'require',
        'address' => 'require|min:3|checkAddress',
        'arrival_time' => 'require',
        'travel_img' => 'require',
        'has_report' => 'require|in:0,1',
        'leave_riskarea_time' => 'require',
        'arrival_transport_mode' => 'require|in:train,aircraft,car',
        'arrival_sign' => 'require|checkArrivalSign',
        'isasterisk' => 'require|in:0,1',
        //'district_id' => 'require|integer',
    ];
    
    protected $message = [
        'real_name.require' => '姓名必填',
        'real_name.min' => '姓名不允许一个字',
        'card_type.require' => '证件类型必填',
        'id_card.require' => '证件号码必填',
        'phone.require' => '手机号必填',
        'sms_code.require' => '短信验证码必填',
        'sms_code.length' => '短信验证码必须6位',
        'province_id.require' => '省市县必填选择完整',
        'province.require' => '省市县必填选择完整',
        // 'city_id.require' => '省市县必填选择完整',
        // 'city.require' => '省市县必填选择完整',
        // 'county_id.require' => '省市县必填选择完整',
        // 'county.require' => '省市县必填选择完整',
        'leave_time.require' => '离义时间必填',
        'expect_return_time.require' => '预计返义时间必填',
        'yw_street_id.require' => '来义居住镇街必填',
        'yw_street.require' => '来义居住镇街必填',
        'address.require' => '详细地址必填',
        'address.min' => '详细地址不小于三个字',
        'arrival_time.require' => '来义时间必填',
        'travel_img.require' => '行程码图片必填',
        'has_report.require' => '是否有48小时内核酸报告必填',
        'leave_riskarea_time.require' => '离开中高风险地区时间必填',
        'arrival_transport_mode.require' => '来义交通方式必填',
        'arrival_sign.require' => '车次/航班/车牌必填',
        'isasterisk.require' => '行程码是否带星号必填',
        //'district_id.require' => '城市必填',
    ];
    
    protected $scene = [
        'leaveDeclare' => ['real_name', 'card_type', 'id_card', 'phone', 'sms_code', 'province_id', 'province', 'city_id', 'city', 'county_id', 'county', 'leave_time', 'expect_return_time'],
        'comeDeclare' => ['real_name', 'card_type', 'id_card', 'phone', 'sms_code', 'province_id', 'province', 'city_id', 'city', 'county_id', 'county', 'yw_street_id', 'yw_street', 'address', 'arrival_time', 'travel_img', 'has_report'],
        'riskareaDeclare' => ['real_name', 'card_type', 'id_card', 'phone', 'sms_code', 'province_id', 'province', 'city_id', 'city', 'county_id', 'county', 'leave_riskarea_time', 'yw_street_id', 'yw_street', 'address', 'arrival_time', 'arrival_transport_mode', 'arrival_sign', 'travel_img', 'has_report'],
        'barrierDeclare' => ['real_name', 'card_type', 'id_card', 'phone', 'sms_code', 'district_id', 'yw_street_id', 'yw_street', 'isasterisk'],
        'quarantine' => ['real_name', 'card_type', 'id_card', 'phone', 'sms_code'],
        'other' => ['real_name', 'card_type', 'id_card', 'phone', 'sms_code'],
        'lastLeave' => ['card_type', 'real_name', 'id_card'],
    ];

    public function checkRealName($value, $rule, $data)
    {
        $match = "/^[\x{4e00}-\x{9fa5}A-Za-z0-9_·]+$/u"; //匹配中文数字字母
        $result = preg_match($match, $value);
        if($result == 0) {
            return '姓名不能包含特殊字符';
        }else{
            if($data['card_type'] == 'id') { //身份证类型
                $match = '/\d+|\w+/';  //中国籍包含字母、数字
                $result = preg_match($match, $value);
                if($result) {
                    return '姓名不能包含字母、数字';
                }
            }
            return true;
        }
    }
    
    public function checkPhone($value, $rule, $data)
    {
        $match = '/^1[0-9]{10}$/';
        $result = preg_match($match, $value);
        if($result) {
            return true;
        }else{
            return '请填写正确的手机号码';
        }
    }

    public function checkIdCard($value, $rule, $data)
    {
        if($data['card_type'] == 'id') { //身份证类型
            if (IdentityCardTool::isValid($value)) {
                return true;
            } else {
                return '身份证号码格式不准确';
            }
        }else{
            $match = "/^[\x{4e00}-\x{9fa5}A-Za-z0-9_]+$/u"; //匹配中文数字字母
            $result = preg_match($match, $value);
            if($result == 0) {
                return '证件号不能包含特殊字符';
            }else{
                return true;
            }
        }
        return true;
    }

    public function checkAddress($value, $rule, $data)
    {
        $match = "/^[\x{4e00}-\x{9fa5}A-Za-z0-9_]+$/u"; //匹配中文数字字母
        $result = preg_match($match, $value);
        if($result == 0) {
            return '地址不能包含特殊字符';
        }else{
            return true;
        }
    }

    public function checkArrivalSign($value, $rule, $data)
    {
        $match = "/^[\x{4e00}-\x{9fa5}A-Za-z0-9_]+$/u"; //匹配中文数字字母
        $result = preg_match($match, $value);
        if($result == 0) {
            return '车次/航班/车牌不能包含特殊字符';
        }else{
            return true;
        }
    }
    
    public function checkReturnTime($value, $rule, $data)
    {
        if(strtotime($value) < strtotime($data['leave_time'])) {
            return '返义时间要大于等于离义时间';
        }
        return true;
    }
}
