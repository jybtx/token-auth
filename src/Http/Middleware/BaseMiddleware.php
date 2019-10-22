<?php

namespace Jybtx\TokenAuth\Http\Middleware;

use Illuminate\Http\Request;
use Jybtx\TokenAuth\JwtAuthToken;
use Jybtx\TokenAuth\Support\TokenValidator;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

abstract class BaseMiddleware
{
	use TokenValidator;

	public function checkForToken(Request $request)
	{
		if ( !$this->getVerifyToken( getoken() ) ) {
			throw new UnauthorizedHttpException('token-auth', 'Token not provided');
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
    protected function setAuthenticationHeader($response, $token = null)
    {
        $token = $token ?: JwtAuthToken::getRefreshToken();
        $response->headers->set('Authorization', 'Bearer '.$token);

        return $response;
    }
}