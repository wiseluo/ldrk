<?php

namespace app\services\admin\system\admin;

use app\dao\system\admin\AdminAuthDao;
use app\services\admin\BaseServices;
use crmeb\exceptions\AuthException;
use crmeb\services\CacheService;
use crmeb\utils\ApiErrorCode;
use crmeb\utils\JwtAuth;
use Firebase\JWT\ExpiredException;
use think\facade\Log;

/**
 * admin授权service
 * Class AdminAuthServices
 * @package app\services\system\admin
 */
class AdminAuthServices extends BaseServices
{
    /**
     * 构造方法
     * AdminAuthServices constructor.
     * @param AdminAuthDao $dao
     */
    public function __construct(AdminAuthDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * 获取Admin授权信息
     * @param string $token
     * @return array
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function parseToken(string $token): array
    {
        /** @var CacheService $cacheService */
        $cacheService = app()->make(CacheService::class);

        if (!$token || $token === 'undefined') {
            throw new AuthException(ApiErrorCode::ERR_LOGIN);
        }
        /** @var JwtAuth $jwtAuth */
        $jwtAuth = app()->make(JwtAuth::class);
        //设置解析token
        try {
            [$id, $type] = $jwtAuth->parseToken($token);
        } catch (\Throwable $e) {
            throw new AuthException(ApiErrorCode::ERR_LOGIN_INVALID);
        }
        //检测token是否过期
        $md5Token = md5($token);
        if (!$cacheService->hasToken($md5Token) || !($cacheToken = $cacheService->getTokenBucket($md5Token))) {
            $this->authFailAfter($id, $type);
            throw new AuthException(ApiErrorCode::ERR_LOGIN);
        }
        //是否超出有效次数
        if (isset($cacheToken['invalidNum']) && $cacheToken['invalidNum'] >= 3) {
            if (!request()->isCli()) {
                $cacheService->clearToken($md5Token);
            }
            $this->authFailAfter($id, $type);
            throw new AuthException(ApiErrorCode::ERR_LOGIN_INVALID);
        }


        //验证token
        try {
            $jwtAuth->verifyToken();
            $cacheService->setTokenBucket($md5Token, $cacheToken, $cacheToken['exp']);
        } catch (ExpiredException $e) {
            $cacheToken['invalidNum'] = isset($cacheToken['invalidNum']) ? $cacheToken['invalidNum']++ : 1;
            $cacheService->setTokenBucket($md5Token, $cacheToken, $cacheToken['exp']);
        } catch (\Throwable $e) {
            if (!request()->isCli()) {
                $cacheService->clearToken($md5Token);
            }
            $this->authFailAfter($id, $type);
            throw new AuthException(ApiErrorCode::ERR_LOGIN_INVALID);
        }

        //获取管理员信息
        $adminInfo = $this->dao->get($id);
        if (!$adminInfo || !$adminInfo->id) {
            if (!request()->isCli()) {
                $cacheService->clearToken($md5Token);
            }
            $this->authFailAfter($id, $type);
            throw new AuthException(ApiErrorCode::ERR_LOGIN_STATUS);
        }
        $adminInfo->type = $type;
        $adminInfo->role_code;
        $data = $adminInfo->hidden(['pwd', 'is_del', 'status'])->toArray();
        // Log::error( 'ddd:'.json_encode($data) );
        return $data;
    }

    /**
     * token验证失败后事件
     */
    protected function authFailAfter($id, $type)
    {
        try {
            $postData = request()->post();
            $rule = trim(strtolower(request()->rule()->getRule()));
            $method = trim(strtolower(request()->method()));

        } catch (\Throwable $e) {
        }
    }

}
