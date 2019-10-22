<?php
namespace Jybtx\TokenAuth;

use Jybtx\TokenAuth\Support\CreateToken;
use Jybtx\TokenAuth\Support\TokenValidator;
use Jybtx\TokenAuth\Support\TokenBlackList;
use Jybtx\TokenAuth\Support\AuthenticationHeader;

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
    		return $this->getPayload(  str_replace('Bearer ','',request()->header('Authorization') ) , true);
    	} else {
    		return $this->getPayload( str_replace('Bearer ','',request()->header('Authorization') ), true)[$attributes];
    	}
	}
	/**
	 * 获取刷新token信息
	 * @author jybtx
	 * @date   2019-10-13
	 * @param  [type]     $attributes [description]
	 * @return [type]                 [description]
	 */
	public function getRefreshToken()
	{
		$token = str_replace('Bearer ','',request()->header('Authorization'));
		// 验证token是否有效
		if ( $this->getVerifyToken($token) ) {
			 // 验证token是否在刷新有效期内
            $refresh = $this->verifyRefresh($token);
            if ( !$refresh ) {
            	throw new UnauthorizedHttpException('token-auth', 'Token has expired');
            }
            $user_data = $this->getPayload($token, true);// 获取原token中的数据
            return $this->getCreateToken($user_data);// 重新生成token
		}
		throw new UnauthorizedHttpException('token-auth', 'Token not provided');
	}
}