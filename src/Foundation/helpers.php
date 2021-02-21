<?php

use Jybtx\TokenAuth\JwtAuthToken;


if ( !function_exists( 'authUser' ) ) {
	/**
	 * 获取用户信息
	 * @author jybtx
	 * @date   2019-11-25
	 * @param  [type]     $attributes [對應的參數]
	 * @return [type]                 [description]
	 */
	function authUser( $attributes = NULL )
	{
		$tokenAuth = new JwtAuthToken;
		return $tokenAuth->getAuthUserInformation($attributes);
	}
}

if ( !function_exists('get_token_data') )
{
    /**
     * [返回用户传递的token值]
     * @Author jybtx
     * @date   2021-02-04
     * @param  [type]     $attributes [description]
     * @return [type]                 [description]
     */
    function get_token_data( $attributes  = NULL )
    {
        $tokenAuth = new JwtAuthToken;
        return $tokenAuth->getUserSendToken( $attributes );
    }
}


if ( !function_exists('gettl') ) {
	/**
	 * 获取token过期时间
	 * @author jybtx
	 * @date   2019-11-25
	 * @return [type]     [description]
	 */
	function gettl()
	{
		return config('token-auth.ttl') * 60;
	}
}


if ( !function_exists('getoken') ) {
	/**
	 * 获取用户token
	 * @author jybtx
	 * @date   2019-11-25
	 * @return [type]     [description]
	 */
	function getoken()
	{
        return trim( str_replace('null','',str_replace('Bearer','',request()->header('Authorization'))) );
	}
}