<?php

namespace Jybtx\JwtAuth\Support;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

trait TokenBlackList
{
	/**
     * 将token加入黑名单
     * @param string $token
     * @return bool
     */
    public static function getAddBlacklist($token)
    {
        $expiresAt = Carbon::now()->addMonth();
        Cache::put($token,$token,$expiresAt); // 缓存一个月
        return true;
    }
}