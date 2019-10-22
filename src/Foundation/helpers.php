<?php

use Jybtx\TokenAuth\JwtAuthToken;

/**
 * 获取用户信息
 */
if ( !function_exists( 'authUser' ) ) {
	function authUser( $attributes = NULL )
	{
		$tokenAuth = new JwtAuthToken;
		return $tokenAuth->getAuthUserInfomation($attributes);
	}
}

/**
 * 获取token过期时间
 */
if ( !function_exists('gettl') ) {
	function gettl()
	{
		return config('token-auth.ttl') * 60;
	}
}

/**
 * 获取用户token
 */
if ( !function_exists('getoken') ) {
	function getoken()
	{
        return trim( str_replace('Bearer','',request()->header('Authorization')) );
	}
}