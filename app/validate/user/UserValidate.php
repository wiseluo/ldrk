<?php

namespace app\validate\user;

use think\Validate;
use \behavior\IdentityCardTool;

class UserValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'    =>    ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'phone' => 'require|checkPhone',
        'id_card' => 'require|checkCardId',
        'real_name' => 'require',
        'nation' => 'require',
        'address' => 'require',
        'permanent_address' => 'require',
        'user_category_id' => 'require|integer',
        'job_status' => 'require|in:1,2',
        'users' => 'require|checkUsers',
        'charge' => 'require|in:1,2',
        'ids' => 'require',
        'gender' => 'require|in:1,2',
        'position' => 'require',
        'smscode' => 'require|length:6|number',
        'safepwd' => 'require',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'phone.require' => '手机号必填',
        'id_card.require' => '身份证必填',
        'real_name.require' => '姓名必填',
        'nation.require' => '民族必填',
        'address.require' => '现住地址必填',
        'permanent_address.require' => '户籍地址必填',
        'user_category_id.require' => '人员分类必填',
        'job_status.require' => '在职状态必填',
        'users.require' => '从业人员数据必填',
        'charge.require' => '设置状态必填',
        'ids.require' => '人员必填',
        'gender.require' => '性别必填',
        'position.require' => '工作岗位必填',
        'smscode.require' => '短信验证码必填',
        'safepwd.require' => '安全密码必填',
    ];

    protected $scene = [
        'save' => ['phone', 'id_card', 'real_name', 'nation', 'address', 'user_category_id'],
        'update' => ['phone', 'address', 'user_category_id', 'job_status'],
        'userUpdate' => ['address'],
        'smslogin' => ['phone','smscode'],
    ];

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

    public function checkCardId($value, $rule, $data)
    {
        if (IdentityCardTool::isValid($value)) {
            return true;
        } else {
            return '证件号码格式不准确';
        }
    }
    
    public function checkUsers($value, $rule, $data)
    {
        $users = json_decode($value, true);
        if(count($users) == 0){
            $rule = '从业人员数据不能为空';
        }
        $id_card = array_column($users, 'id_card');
        if(count($id_card) != count(array_unique($id_card))) {
            return '身份证号有重复';
        }
        foreach($users as $k => $v) {
            if(!isset($v['real_name']) || empty($v['real_name'])) {
                return '第'. ($v['index']+1) .'行姓名必填';
            }
            if(!isset($v['id_card']) || !IdentityCardTool::isValid($v['id_card'])) {
                return '第'. ($v['index']+1) .'行身份证号格式不准确';
            }
            if(!isset($v['phone']) || !preg_match('/^1[0-9]{10}$/', $v['phone'])) {
                return '第'. ($v['index']+1) .'行手机号必填';
            }
            if(!isset($v['address']) || $v['address'] == '') {
                return '第'. ($v['index']+1) .'行现住地址必填';
            }
            if(!isset($v['user_category_id']) || !is_numeric($v['user_category_id'])) {
                return '第'. ($v['index']+1) .'行人员分类必填';
            }
            if(!isset($v['position'])) {
                return '第'. ($v['index']+1) .'行工作岗位必填';
            }
            if(!isset($v['permanent_address'])) {
                return '第'. ($v['index']+1) .'行户籍地址必填';
            }
            if(!isset($v['nation']) || $v['nation'] == '') {
                return '第'. ($v['index']+1) .'行民族必填';
            }
        }
        if ($rule) {
            return $rule;
        } else {
            return true;
        }
    }
}
