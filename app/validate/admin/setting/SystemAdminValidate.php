<?php
namespace app\validate\admin\setting;

use think\Validate;

class SystemAdminValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'    =>    ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'gov_id' => 'require|integer',
        'account' => 'require',
        'phone' => 'require|checkPhone',
        'real_name' => 'require',
        'pwd' => 'require|length:6,20|alphaNum',
        'conf_pwd' => 'require|confirm:pwd',
        'roles' => 'require|checkRoles',
        'status' => 'require|in:0,1',
        'admins' => 'require|checkAdmins',
        'imgcode' => 'require|length:4',
        'smscode' => 'require|length:6|number',
        'ids' => 'require',
        'safepwd' => 'require'
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'gov_id.require' => '所属机构必填',
        'account.require' => '请填写管理员账号',
        'phone.require' => '请填写手机号',
        'real_name.require' => '请输管理员姓名',
        'pwd.require' => '请输入密码',
        'pwd.length' => '密码必须6-20位',
        'pwd.alphaNum' => '密码必须为字母和数字',
        'conf_pwd.require' => '请输入确认密码',
        'conf_pwd.confirm' => '两次输入的密码不相同',
        'roles.require' => '请选择管理员身份',
        'status.require' => '状态必填',
        'admins.require' => '管理员数据必填',
        'imgcode.require' => '验证码必填',
        'imgcode.length' => '验证码4位',
        'smscode.require' => '短信验证码必填',
        'ids.require' => '必须选中一个管理员',
        'safepwd.require' => '安全密码必填',
    ];

    protected $scene = [
        'login' => ['account', 'pwd', 'imgcode'],
        'phoneBinding' => ['phone', 'smscode'],
        'save' => ['phone', 'real_name', 'roles'],
        'update' => ['phone', 'roles', 'status'],
        'adminBatchSave' => ['admins'],
        'govAdmin' => ['gov_id'],
        'batchStatus' => ['ids', 'status'],
        'setSafepwd' => ['phone', 'smscode','safepwd'],
        'smslogin' => ['phone','smscode'],
    ];

    public function checkPhone($value, $rule, $data)
    {
        $match = '/^1[0-9]{10}$/';
        $result = preg_match($match, $value);
        if($result) {
            return true;
        }else{
            return '账号必须是正确的手机号';
        }
    }

    public function checkRoles($value, $rule, $data)
    {
        $roles = explode(',', $value);
        if(count($roles) == 0){
            $rule = '角色不能为空';
        }
        foreach($roles as $k => $v){
            if(!ctype_digit((string) $v)) {
                $rule = '角色id必须为整数';
                return;
            }
        }
        if ($rule) {
            return $rule;
        } else {
            return true;
        }
    }
    
    public function checkAdmins($value, $rule, $data)
    {
        $admins = json_decode($value, true);
        if(count($admins) == 0){
            $rule = '管理员数据不能为空';
        }
        $phones = array_column($admins, 'phone');
        if(count($phones) != count(array_unique($phones))) {
            return '手机号有重复';
        }
        foreach($admins as $k => $v) {
            if(!isset($v['phone']) || !preg_match('/^1[0-9]{10}$/', $v['phone'])) {
                return '第'. ($v['index']+1) .'行手机号必填';
            }
            if(!isset($v['real_name']) || empty($v['real_name'])) {
                return '第'. ($v['index']+1) .'行姓名必填';
            }
            if(!isset($v['gov_id']) || !is_numeric($v['gov_id'])) {
                return '第'. ($v['index']+1) .'行机构id必填且必须是整数';
            }
            if(!isset($v['level']) || !in_array($v['level'], [1,2,3])) {
                return '第'. ($v['index']+1) .'行级别必填';
            }
        }
        if ($rule) {
            return $rule;
        } else {
            return true;
        }
    }
}
