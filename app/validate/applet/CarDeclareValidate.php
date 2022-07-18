<?php

namespace app\validate\applet;

use think\Validate;
use \behavior\IdentityCardTool;

class CarDeclareValidate extends Validate
{
    protected $rule = [
        'real_name' => 'require|min:2',
        'id_card' => 'require|max:20',
        'phone' => 'require',
        'plate_number' => 'require',
    ];
    
    protected $message = [
        'real_name.require' => '姓名必填',
        'real_name.min' => '姓名不允许一个字',
        'id_card.require' => '证件号码必填',
        'phone.require' => '手机号必填',
        'plate_number.require' => '车牌号必填',
    ];
    
    protected $scene = [
        'post' => ['real_name', 'id_card', 'phone', 'plate_number']
        
    ];

}
