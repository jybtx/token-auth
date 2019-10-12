<?php

use Jybtx\JwtAuth\JwtAuthToken;

if ( !function_exists( 'jybtx' ) ) {
	function jybtx( $attributes = NULL )
	{
		return '5555';
		// return JwtAuthToken::getAuthUserInfomation($attributes);		
	}
}