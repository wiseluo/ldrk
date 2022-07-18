<?php
declare (strict_types=1);

namespace app\services\admin;

use app\services\admin\BaseServices;
use app\dao\UserDao;
use crmeb\services\CacheService;
use think\exception\ValidateException;
use think\facade\Config;

/**
 *
 * Class LoginServices
 * @package app\services\user
 */
class LoginServices extends BaseServices
{

    /**
     * LoginServices constructor.
     * @param LoginDao $dao
     */
    public function __construct(UserDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * H5账号登陆
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function login($account, $password, $spread)
    {
        $user = $this->dao->getOne(['account|phone' => $account]);
        if ($user) {
            if ($user->pwd !== md5((string)$password))
                throw new ValidateException('账号或密码错误');
            if ($user->pwd === md5('123456'))
                throw new ValidateException('请修改您的初始密码，再尝试登录！');
        } else {
            throw new ValidateException('账号或密码错误');
        }
        if (!$user['status'])
            throw new ValidateException('已被禁止，请联系管理员');

        //更新用户信息
        $this->updateUserInfo(['code' => $spread], $user);
        $token = $this->createToken((int)$user['uid'], 'api');
        if ($token) {
            return ['token' => $token['token'], 'expires_time' => $token['params']['exp']];
        } else
            throw new ValidateException('登录失败');
    }

    /**
     * 更新用户信息
     * @param $user
     * @param $uid
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function updateUserInfo($user, $userInfo)
    {
        $data = [];
        $data['nickname'] = !isset($user['nickname']) || !$user['nickname'] ? $userInfo->nickname : $user['nickname'];
        $data['avatar'] = !isset($user['headimgurl']) || !$user['headimgurl'] ? $userInfo->avatar : $user['headimgurl'];
        $data['phone'] = !isset($user['phone']) || !$user['phone'] ? $userInfo->phone : $user['phone'];
        $data['last_time'] = time();
        $data['last_ip'] = app()->request->ip();
        if (!$this->dao->update($userInfo['uid'], $data, 'uid')) {
            throw new ValidateException('修改信息失败');
        }
        return true;
    }

    /**
     * 重置密码
     * @param $account
     * @param $password
     */
    public function reset($account, $password)
    {
        $user = $this->dao->getOne(['account|phone' => $account]);
        if (!$user) {
            throw new ValidateException('用户不存在');
        }
        if (!$this->dao->update($user['uid'], ['pwd' => md5((string)$password)], 'uid')) {
            throw new ValidateException('修改密码失败');
        }
        return true;
    }

    /**
     * 手机号登录
     * @param $phone
     * @param $spread
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function mobile($phone, $spread, $user_type = 'h5')
    {
        //数据库查询
        $user = $this->dao->getOne(['phone' => $phone]);
        if (!$user) {
            $user = $this->register($phone, '123456', $spread, $user_type);
            if (!$user) {
                throw new ValidateException('用户登录失败,无法生成新用户,请稍后再试!');
            }
        }

        if (!$user->status)
            throw new ValidateException('已被禁止，请联系管理员');

        // 设置推广关系
        $this->updateUserInfo(['code' => $spread], $user);

        $token = $this->createToken((int)$user['uid'], 'api');
        if ($token) {
            return ['token' => $token['token'], 'expires_time' => $token['params']['exp']];
        } else {
            throw new ValidateException('登录失败');
        }
    }

    /**
     * 切换登录
     * @param $user
     * @param $from
     */
    public function switchAccount($user, $from)
    {
        if ($from === 'h5') {
            $where = [['phone', '=', $user['phone']], ['user_type', '<>', 'h5']];
            $login_type = 'wechat';
        } else {
            //数据库查询
            $where = [['account|phone', '=', $user['phone']], ['user_type', '=', 'h5']];
            $login_type = 'h5';
        }
        $switch_user = $this->dao->getOne($where);
        if (!$switch_user) {
            return app('json')->fail('用户不存在,无法切换');
        }
        if (!$switch_user->status) {
            return app('json')->fail('已被禁止，请联系管理员');
        }
        $edit_data = ['login_type' => $login_type];
        if (!$this->dao->update($switch_user['uid'], $edit_data, 'uid')) {
            throw new ValidateException('修改新用户登录类型出错');
        }
        $token = $this->createToken((int)$switch_user['uid'], 'api');
        if ($token) {
            return ['token' => $token['token'], 'expires_time' => $token['params']['exp']];
        } else {
            throw new ValidateException('切换失败');
        }
    }

    /**
     * 用户绑定手机号
     * @param $user
     * @param $phone
     * @param $step
     * @return mixed
     */
    public function userBindindPhone(int $uid, $phone, $step)
    {
        $userInfo = $this->dao->get($uid);
        if (!$userInfo) {
            throw new ValidateException('用户不存在');
        }
        if ($this->dao->getOne([['phone', '=', $phone], ['user_type', '<>', 'h5']])) {
            throw new ValidateException('此手机已经绑定，无法多次绑定！');
        }
        if ($userInfo->phone) {
            throw new ValidateException('您的账号已经绑定过手机号码！');
        }
        $data = [];
        if ($this->dao->getOne(['account' => $phone, 'phone' => $phone, 'user_type' => 'h5'])) {
            if (!$step) return ['msg' => 'H5已有账号是否绑定此账号上', 'data' => ['is_bind' => 1]];
        } else {
            $data['account'] = $phone;
        }
        $data['phone'] = $phone;
        if ($this->dao->update($userInfo['uid'], $data, 'uid') || $userInfo->phone == $phone)
            return ['msg' => '绑定成功', 'data' => []];
        else
            throw new ValidateException('绑定失败');
    }

    /**
     * 用户绑定手机号
     * @param $user
     * @param $phone
     * @param $step
     * @return mixed
     */
    public function updateBindindPhone(int $uid, $phone)
    {
        $userInfo = $this->dao->get($uid);
        if (!$userInfo) {
            throw new ValidateException('用户不存在');
        }
        if ($userInfo->phone == $phone) {
            throw new ValidateException('新手机号和原手机号相同，无需修改');
        }
        if ($this->dao->getOne([['phone', '=', $phone]])) {
            throw new ValidateException('此手机已经注册');
        }
        $data = [];
        $data['phone'] = $phone;
        if ($this->dao->update($userInfo['uid'], $data, 'uid'))
            return ['msg' => '修改成功', 'data' => []];
        else
            throw new ValidateException('修改失败');
    }
}
