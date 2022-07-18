<?php

namespace app\services\ainat;

use app\dao\AinatAdminDao;
use app\services\ainat\BaseServices;
use crmeb\exceptions\AuthException;
use crmeb\services\CacheService;
use crmeb\utils\ApiErrorCode;
use crmeb\utils\AinatJwtAuth;
use Firebase\JWT\ExpiredException;

/**
 * admin授权service
 * Class AdminAuthServices
 */
class AdminAuthServices extends BaseServices
{
    public function __construct(AinatAdminDao $dao)
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
        $jwtAuth = app()->make(AinatJwtAuth::class);
        //设置解析token
        try {
            [$id, $type] = $jwtAuth->parseToken($token);
        } catch (\Throwable $e) {
            throw new AuthException(ApiErrorCode::ERR_LOGIN_INVALID);
        }
        //检测token是否过期
        $md5Token = md5($token);
        if (!$cacheService->hasToken($md5Token) || !($cacheToken = $cacheService->getTokenBucket($md5Token))) {
            throw new AuthException(ApiErrorCode::ERR_LOGIN);
        }
        //是否超出有效次数
        if (isset($cacheToken['invalidNum']) && $cacheToken['invalidNum'] >= 3) {
            if (!request()->isCli()) {
                $cacheService->clearToken($md5Token);
            }
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
            throw new AuthException(ApiErrorCode::ERR_LOGIN_INVALID);
        }

        //获取管理员信息
        $adminInfo = $this->dao->get($id);
        if (!$adminInfo || !$adminInfo->id) {
            if (!request()->isCli()) {
                $cacheService->clearToken($md5Token);
            }
            throw new AuthException(ApiErrorCode::ERR_LOGIN_STATUS);
        }
        $adminInfo->type = $type;
        //$adminInfo->role_code;
        $data = $adminInfo->hidden(['pwd', 'is_del', 'status'])->toArray();
        // Log::error( 'ddd:'.json_encode($data) );
        return $data;
    }

}
