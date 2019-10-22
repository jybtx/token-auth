<?php

use Jybtx\TokenAuth\JwtAuthToken;

if ( !function_exists( 'tokenAuth' ) ) {
	function tokenAuth( $attributes = NULL )
	{
		$tokenAuth = new JwtAuthToken;
		return $tokenAuth->getAuthUserInfomation($attributes);
	}
}