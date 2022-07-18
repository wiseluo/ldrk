<?php

namespace app\validate\ainat;

use think\Validate;

class CompareTaskValidate extends Validate
{
    protected $rule = [
        'sign' => 'require',
        'name' => 'require',
    ];
    
    protected $message = [
        'sign.require' => '标记必填',
        'name.require' => '任务名称必填',
    ];
    
    protected $scene = [
        'save' => ['sign', 'name'],
    ];
    
}
