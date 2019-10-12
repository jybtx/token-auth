<?php

namespace Jybtx\JwtAuth\Http\Middleware;

use Illuminate\Http\Request;
use Jybtx\JwtAuth\TokenValidator;
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
        $token = $token ?: $this->auth->refresh();
        $response->headers->set('Authorization', 'Bearer '.$token);

        return $response;
    }
}