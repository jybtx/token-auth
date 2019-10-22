<?php

namespace Jybtx\TokenAuth\Support;


trait AuthenticationHeader
{

	/**
	 * 验证token是否在刷新有效期内
	 * @author jybtx
	 * @date   2019-10-11
	 * @param  [type]     $token [description]
	 * @return [type]            [description]
	 */
	public static function verifyRefresh($token)
    {

        // 验证签名是否合法
        $legal = TokenValidator::verifySign($token);        
        if ( !$legal ) return false;

        $payload = TokenValidator::getPayload($token);
        if ( !$payload ) return false;

        // 签发时间大于当前服务器时间验证失败
        if (isset($payload['iat']) && $payload['iat'] > time()) return false;

        // 生效时间大于当前服务器时间验证失败
        if (isset($payload['nbf']) && $payload['nbf'] > time()) return false;

        // 当前服务器时间大于过期时间验证失败
        if (isset($payload['ref_exp']) && time() > $payload['ref_exp']) return false;

        return true;
    }

    /**
     * 在刷新期内设置头信息
     * @author jybtx
     * @date   2019-10-11
     * @param  [type]     $response [description]
     * @param  [type]     $token    [description]
     */
	public static function setAuthenticationHeader($response, $token = null)
	{
		$token = $token ?: self::verifyRefresh($token);
        $response->headers->set('Authorization', 'Bearer '.$token);

        return $response;
	}
}