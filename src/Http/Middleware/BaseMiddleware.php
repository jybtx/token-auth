<?php

namespace Jybtx\TokenAuth\Http\Middleware;

use Jybtx\TokenAuth\JwtAuthToken;
use Illuminate\Support\Facades\Cache;
use Jybtx\TokenAuth\Support\CreateToken;
use Jybtx\TokenAuth\Support\TokenValidator;
use Jybtx\TokenAuth\Support\TokenBlackList;
use Jybtx\TokenAuth\Support\AuthenticationHeader;

abstract class BaseMiddleware
{
	use TokenValidator,AuthenticationHeader,TokenBlackList,CreateToken;
    /**
     * [checkTokenRefreshTimeForRestApi description]
     * @author jybtx
     * @date   2020-05-06
     * @return [type]     [description]
     */
    public function checkTokenRefreshTimeForRestApi()
    {
        return $this->verifyRefresh( getoken() );
    }
    /**
     * [Check token value of user REST API]
     * @author jybtx
     * @date   2019-12-16
     * @return [type]     [description]
     */
    public function checkTokenForRestApi()
    {
        return $this->getVerifyToken( getoken() );
    }
	/**
     * Set the authentication header.
     *
     * @param  \Illuminate\Http\Response|\Illuminate\Http\JsonResponse  $response
     * @param  string|null  $token
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function setAuthenticationHeaders($response, $token = null)
    {
        return $this->getSetAuthenticationHeader($response, $token);
    }

    /**
     * [Check the blacklist token value of the user REST API]
     * @author jybtx
     * @date   2019-12-16
     * @return [type]     [description]
     */
    public function checkTokenIsInBlacklistForApi()
    {
        return Cache::has( getoken() );
    }
}