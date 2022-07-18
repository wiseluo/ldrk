<?php

namespace app\validate\ainat;

use think\Validate;
use \behavior\IdentityCardTool;

class NatCompareValidate extends Validate
{
    protected $rule = [
        'sign' => 'require',
        'id_card' => 'require|checkCardId',
    ];
    
    protected $message = [
        'sign.require' => '标记必填',
        'id_card.require' => '证件号必填',
    ];
    
    protected $scene = [
        'index' => ['sign'],
        'compare' => ['sign'],
        'compareProgress' => ['sign'],
        'actualHsjc' => ['id_card'],
    ];
    
    public function checkCardId($value, $rule, $data)
    {
        if (IdentityCardTool::isValid($value)) {
            return true;
        } else {
            return '证件号码格式不准确';
        }
    }
}
