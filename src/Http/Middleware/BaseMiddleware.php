<?php

namespace Jybtx\TokenAuth\Http\Middleware;

use Illuminate\Http\Request;
use Jybtx\TokenAuth\JwtAuthToken;
use Illuminate\Support\Facades\Cache;
use Jybtx\TokenAuth\Support\TokenValidator;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

abstract class BaseMiddleware
{
	use TokenValidator;
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
     * [Check token value of user REST API]
     * @author jybtx
     * @date   2019-12-16
     * @return [type]     [description]
     */
    public function checkTokenForRestApi()
    {
        if ( !$this->getVerifyToken( getoken() ) ) 
        {
            return false;
        }
    }
	/**
     * Set the authentication header.
     *
     * @param  \Illuminate\Http\Response|\Illuminate\Http\JsonResponse  $response
     * @param  string|null  $token
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function setAuthenticationHeader($response, $token = null)
    {
        $token = $token ?: JwtAuthToken::getRefreshToken();
        $response->headers->set('Authorization', 'Bearer '.$token);

        return $response;
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