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
        $payload = self::getPayload($token);
        if ( !$payload ) return false;

        // 验证签名是否合法
        $legal = self::verifySign($token);
        if ( !$legal ) return false;

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
	public static function getSetAuthenticationHeader($response, $token = null)
	{
        if ( $token )
        {
            if ( self::verifyRefresh( $token ) ) {
                $user_data =  self::getPayload($token, true);// 获取原token中的数据;
                self::getAddBlacklist($token);
                $token = self::getCreateToken($user_data);
            } else {
                return FALSE;
            }
        } else {
            if ( self::verifyRefresh( getoken() ) ) {
                $user_data = self::getPayload(getoken(), true);// 获取原token中的数据;
                self::getAddBlacklist( (string)getoken() );
                $token = self::getCreateToken($user_data);
            } else {
                return FALSE;
            }
        }
        $response->headers->set('Authorization', 'Bearer '.$token);
        return $response;
	}
}