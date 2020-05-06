<?php

namespace Jybtx\TokenAuth\Http\Middleware;

use Illuminate\Http\Request;
use Jybtx\TokenAuth\JwtAuthToken;
use Illuminate\Support\Facades\Cache;
use Jybtx\TokenAuth\Support\TokenValidator;
use Jybtx\TokenAuth\Support\AuthenticationHeader;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

abstract class BaseMiddleware
{
	use TokenValidator,AuthenticationHeader;
    /**
     * [Check the token value of the user's web page]
     * @author jybtx
     * @date   2019-12-16
     * @return [type]     [description]
     */
	public function checkForToken()
	{
		if ( !$this->getVerifyToken( getoken() ) ) 
        {
			throw new UnauthorizedHttpException('token-auth', 'Token not provided');
		}
	}
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
        return $this->setAuthenticationHeader($response, $token);
    }

    /**
     * Check whether token is in blacklist
     *
     */
    public function checkTokenIsInBlacklist()
    {
        if ( Cache::has( getoken() ) )
        {
            throw new UnauthorizedHttpException('token-auth', 'Token expired');
        }
    }
    /**
     * [Check the blacklist token value of the user REST API]
     * @author jybtx
     * @date   2019-12-16
     * @return [type]     [description]
     */
    public function checkTokenIsInBlacklistForApi()
    {
        if ( Cache::has( getoken() ) )
        {
            return false;
        }
    }
}