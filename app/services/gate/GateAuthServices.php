<?php
declare (strict_types=1);

namespace app\services\applet;

use app\services\user\BaseServices;
use app\dao\UserAuthDao;
use crmeb\exceptions\UserException;
use think\facade\Cache;
use crmeb\utils\AppletJwtAuth;

class GateAuthServices extends BaseServices
{
    public function __construct(UserAuthDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * 获取授权信息
     * @param $token
     */
    public function parseToken($token)
    {
        if (!$token || $token === 'undefined') {
            throw new UserException('请登录', 41000);
        }
        $jwtAuth = app()->make(AppletJwtAuth::class);
        //验证token
        try {
            $res = $jwtAuth->verifyToken($token);
            $id = $res->jti->id;
        } catch (\Throwable $e) {
            throw new UserException('无效token-'. $e->getMessage(), 41000);
        }
        //获取人员信息
        $userInfo = $this->dao->get($id);
        if (!$userInfo || !$userInfo->id) {
            throw new UserException('用户状态错误', 41000);
        }
        return $userInfo->hidden(['pwd', 'status'])->toArray();
    }

}
