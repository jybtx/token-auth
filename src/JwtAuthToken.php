<?php
namespace Jybtx\JwtAuth;

use Jybtx\JwtAuth\Support\CreateToken;
use Jybtx\JwtAuth\Support\TokenValidator;
use Jybtx\JwtAuth\Support\TokenBlackList;
use Jybtx\JwtAuth\Support\AuthenticationHeader;

class JwtAuthToken
{
	use CreateToken,TokenValidator,TokenBlackList,AuthenticationHeader;

	/**
	 * 生成access_token
	 * @author jybtx
	 * @date   2019-10-11
	 * @param  array      $data [description]
	 * @return [type]           [description]
	 */
	public function getCreateAccessToken(array $data=[])
	{
		return $this->getCreateToken($data);
	}
	/**
	 * 生成refresh_token
	 * @author jybtx
	 * @date   2019-10-11
	 * @param  array      $data [description]
	 * @return [type]           [description]
	 */
	public function getCreateRefreshToken(array $data=[])
	{
		return $this->getCreateToken($data);
	}
	/**
	 * token添加进黑名单
	 * @author jybtx
	 * @date   2019-10-11
	 * @param  [type]     $token [description]
	 */
	public function TokenAddBlacklist($token)
	{
		return $this->getAddBlacklist($token);
	}
	/**
	 * 设置头信息
	 * @author jybtx
	 * @date   2019-10-11
	 * @param  [type]     $response [description]
	 * @param  [type]     $token    [description]
	 */
	public function setAuthenticationHeader($response, $token = null)
	{
		return $this->setAuthenticationHeader($response,$token);
	}
	/**
	 * 获取用户信息
	 * @author jybtx
	 * @date   2019-10-12
	 * @param  [type]     $attributes [description]
	 * @return [type]                 [description]
	 */
	public function getAuthUserInfomation( $attributes = null )
	{
		if ( is_null($attributes) )
    	{
    		return $this->getPayload( trim( str_replace('Bearer','',request()->header('Authorization')) ) , true);
    	} else {
    		return $this->getPayload(trim( str_replace('Bearer','',request()->header('Authorization')) ), true)[$attributes];
    	}
	}
}