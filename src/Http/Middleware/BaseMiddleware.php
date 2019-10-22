<?php

namespace Jybtx\TokenAuth\Http\Middleware;

use TokenAuth;
use Illuminate\Http\Request;
use Jybtx\TokenAuth\TokenValidator;

abstract class BaseMiddleware
{
	use TokenValidator;

	public function checkForToken(Request $request)
	{
		if ( !$this->getVerifyToken( $request->header('Authorization') ) ) {
			throw new UnauthorizedHttpException('jwt-auth', 'Token not provided');
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
        $token = $token ?: JwtAuth::getRefreshToken();
        $response->headers->set('Authorization', 'Bearer '.$token);

        return $response;
    }
}