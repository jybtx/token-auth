<?php
namespace Jybtx\TokenAuth;

use Jybtx\TokenAuth\Support\CreateToken;
use Jybtx\TokenAuth\Support\TokenValidator;
use Jybtx\TokenAuth\Support\TokenBlackList;
use Jybtx\TokenAuth\Support\AuthenticationHeader;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class JwtAuthToken
{
	use CreateToken,TokenValidator,TokenBlackList,AuthenticationHeader;

	/**
	 * 生成access_token
	 * @author jybtx
	 * @date   2019-12-06
	 * @param  [type]     $data [description]
	 * @param  [type]     $flag [唯一标志]
	 * @return [type]           [description]
	 */
	public function getCreateAccessToken( $data = null , $flag = null)
	{
	    return self::getCreateToken($data,$flag);
	}
	/**
	 * token添加进黑名单
	 * @author jybtx
	 * @date   2019-10-11
	 * @param  [type]     $token [description]
	 */
	public function TokenAddBlacklist($token=null)
	{
		$token = $token??getoken();
		return self::getAddBlacklist($token);
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
	    return self::getSetAuthenticationHeader($response,$token);
	}
	/**
	 * 获取用户信息
	 * @author jybtx
	 * @date   2019-10-12
	 * @param  [type]     $attributes [description]
	 * @return [type]                 [description]
	 */
	public function getAuthUserInformation( $attributes = null )
	{
		if ( !$this->getVerifyToken(getoken()) ) return false;
		
		if ( is_null($attributes) )
    	{
    		return self::getPayload( getoken(), true);
    	} else {
    		return self::getPayload( getoken(), true)[$attributes];
    	}
	}

    /**
     * [用户自己传递token值]
     * @Author jybtx
     * @date   2021-02-04
     * @param  [type]     $attributes [description]
     * @return [type]                 [description]
     */
    public function getUserSendToken( $attributes = null )
    {
        if ( is_null($attributes) || !$this->getVerifyToken($attributes) ) return false;

        return self::getPayload( $attributes, true );
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
		$token = trim( str_replace('null','',str_replace('Bearer ','',request()->header('Authorization'))) );
		if ( !empty($token) )
		{
			// 验证token是否有效
			if ( $this->getVerifyToken($token) ) {
				 // 验证token是否在刷新有效期内
	            $refresh = $this->verifyRefresh($token);
	            if ( !$refresh ) {
	            	throw new UnauthorizedHttpException('token-auth', 'Token has expired');
	            }
	            $user_data = self::getPayload($token, true);// 获取原token中的数据
	            self::TokenAddBlacklist($token);	            
	            return self::getCreateAccessToken($user_data);// 重新生成token
			}
		}
		throw new UnauthorizedHttpException('token-auth', 'Token not provided');
	}
}