<?php

use Jybtx\TokenAuth\JwtAuthToken;

if ( !function_exists( 'authUser' ) ) {
	function authUser( $attributes = NULL )
	{
		$tokenAuth = new JwtAuthToken;
		return $tokenAuth->getAuthUserInfomation($attributes);
	}
}

if ( !function_exists('gettl') ) {
	function gettl()
	{
		return config('token-auth.ttl') * 60;
	}
}

if ( !function_exists('getoken') ) {
	function getoken()
	{
		$token = request()->header('Authorization');
        return trim( str_replace('Bearer','',$token) );
	}
}