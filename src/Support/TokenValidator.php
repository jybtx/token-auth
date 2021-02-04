<?php

namespace Jybtx\TokenAuth\Support;

use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha512;

trait TokenValidator
{
	/**
	 * [验证token是否有效,默认验证exp,iat时间]
	 * @author jybtx
	 * @date   2019-10-11
	 * @param  [string]     $token [需要验证的token]
	 * @return [type]            [description]
	 */
	public static function getVerifyToken($token)
    {
        // 验证token是否合法
        $payload = self::getPayload($token);        
        if ( !$payload ) return false;

        // 验证签名是否合法
        $legal = self::verifySign($token);

        if ( $legal != TRUE ) return false;
        
        // 签发时间大于当前服务器时间验证失败
        if (isset($payload['iat']) && $payload['iat'] > time()) return false;

        // 生效时间大于当前服务器时间验证失败
        if (isset($payload['nbf']) && $payload['nbf'] > time()) return false;

        // 当前服务器时间大于过期时间验证失败
        if (isset($payload['exp']) && time() > $payload['exp']) return false;

        return true;
    }
    /**
     * 验证签名
     * @param string $token
     * @return bool
     */
    public static function verifySign($token)
    {
        $signer = new Sha512();
        $parse  = new Parser();
        $parse  = $parse->parse($token);
        $result = $parse->verify($signer, self::getCacheSecretKey() );// 验证成功返回true 失败false
        return $result;
    }
    /**
     * 获取token中的Payload字段
     * @param string $token        token
     * @param string $only_data    只返回自定义字段(默认只返回所有字段)
     * @return bool|array
     */
    public static function getPayload(string $token, $only_data=false)
    {
        if ( !$token )  return false;
        
        $tokens = explode('.', $token);
        if (count($tokens) != 3) return false;

        list($base64header, $base64payload, $sign) = $tokens;
        $payload = json_decode(base64_decode($base64payload));
        $data = [];
        if ( $only_data ) {
            if ( isset($payload->data) ) {
                if ( is_string($payload->data) ) {
                    $data = json_decode($payload->data, JSON_OBJECT_AS_ARRAY);
                }else{
                    $data = (array) $payload->data;
                }
            }
        }else{
            $data = (array) $payload;
        }
        return $data;
    }
}