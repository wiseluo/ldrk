<?php

namespace app\validate\applet;

use think\Validate;
use \behavior\IdentityCardTool;

class UserValidate extends Validate
{
    protected $rule = [
        'id_card' => 'require|checkCardId',
        'phone' => 'require|checkPhone',
        'sms_code' => 'require|length:6',
        'check_frequency' => 'require|in:2,7,14,28,70',
        'province_id' => 'require|integer',
        'province' => 'require',
        'city_id' => 'require|integer',
        'city' => 'require',
        'county_id' => 'require|integer',
        'county' => 'require',
        'yw_street_id' => 'require|integer',
        'yw_street' => 'require',
        'arrival_time' => 'require|dateFormat:Y-m-d',
    ];
    
    protected $message = [
        'id_card.require' => '身份证必填',
        'phone.require' => '手机号必填',
        'sms_code.require' => '短信验证码必填',
        'sms_code.length' => '短信验证码必须6位',
        'check_frequency.require' => '检测频率必填',
        'province_id.require' => '省市县必须选择完整',
        'province.require' => '省市县必须选择完整',
        'city_id.require' => '省市县必须选择完整',
        'city.require' => '省市县必须选择完整',
        'county_id.require' => '省市县必须选择完整',
        'county.require' => '省市县必须选择完整',
        'yw_street_id.require' => '所在镇街必填',
        'yw_street.require' => '所在镇街必填',
        'arrival_time.require' => '抵义时间必填',
    ];
    
    protected $scene = [
        'xcmVerify' => ['phone', 'sms_code'],
        'replaceXcmVerify' => ['id_card', 'phone', 'sms_code'],
        'checkFrequency' => ['staff_classify_id'],
        'subInfo' => ['province_id', 'province', 'city_id', 'city', 'county_id', 'county', 'yw_street_id', 'yw_street', 'arrival_time']
    ];

    public function checkPhone($value, $rule, $data)
    {
        if(preg_match('/^1[0-9]{10}$/', $value)) {
            return true;
        }else{
            return '手机号不正确';
        }
    }
    
    public function checkCardId($value, $rule, $data)
    {
        if (IdentityCardTool::isValid($value)) {
            return true;
        } else {
            return '证件号码格式不准确';
        }
    }
}
