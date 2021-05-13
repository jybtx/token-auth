<?php

namespace Jybtx\TokenAuth\Support;

use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;

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
        Redis::set(md5($token),$token,$expiresAt); // 缓存一个月
        return true;
    }
}