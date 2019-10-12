<?php

namespace Jybtx\JwtAuth\Support;

use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha512;
trait CreateToken
{
	/**
	 * [getCreateToken description]
	 * @author jybtx
	 * @date   2019-10-11
	 * @param  array      $data [description]
	 * @return [type]           [description]
	 */
	public static function getCreateToken($data)
    {
		$builder   = new Builder();
		$signer    = new Sha512();
		$curr_time = time();
		$token_id  = md5(uniqid('JWT').$curr_time);		
		$exp       = bcadd($curr_time, config('jwt-auth.ttl'));
		$ref_exp   = bcadd($curr_time, config('jwt-auth.refresh_ttl'));
        // 官方字段可选用
        $builder->setIssuer('admin');// 设置iss发行人
        $builder->setAudience('user');// 设置aud接收人
        $builder->setId($token_id, true);// 设置jti 该Token唯一标识
        $builder->setIssuedAt($curr_time);// 设置iat 生成token的时间
        $builder->setNotBefore($curr_time);// 设置nbf token生效时间
        $builder->setExpiration($exp);// 设置exp 过期时间
        // 设置刷新有效期
        $builder->set('ref_exp', $ref_exp);
        // 自定义数据
        if ( !empty($data) ) {
            $builder->set('data', $data);
        }
        $builder->sign($signer, str_replace('base64:','',config('jwt-auth.secret')) );// 对上面的信息使用sha256算法签名
        $token = $builder->getToken();// 获取生成的token
        $token = (string) $token;
        return $token;
    }
}