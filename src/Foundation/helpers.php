<?php

use Jybtx\JwtAuth\JwtAuthToken;

if ( !function_exists( 'tokenAuth' ) ) {
	function tokenAuth( $attributes = NULL )
	{
		return JwtAuthToken::getAuthUserInfomation($attributes);		
	}
}