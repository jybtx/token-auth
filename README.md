# token-auth
一个token认证的扩展包

## Installation

### Composer
Execute the following command to get the latest version of the package:

```terminal
composer require jybtx/token-auth
```
### Laravel

#### >= laravel5.5

ServiceProvider will be attached automatically

#### Other

In your `config/app.php` add `Jybtx\TokenAuth\Provider\TokenAuthServiceProvider::class` to the end of the `providers` array:

```php
'providers' => [
    ...
    Jybtx\TokenAuth\Providers\TokenAuthServiceProvider::class,
],
'aliases'  => [
    ...
    "TokenAuth" => Jybtx\TokenAuth\Facades\TokenAuthFace::class,
]
```
#### Publish the config

Run the following command to publish the package config file:

```shell
php artisan vendor:publish --provider "Jybtx\TokenAuth\Provider\TokenAuthServiceProvider"
```
### create token Secret key
```shell
php artisan token:generate
```
This will update your .env file with something like JWT_SECRET_KEY=base64:foobar

## Usage
Create the TestController
```php
<?php

use TokenAuth;
class TestController extends Controller
{
	public function refresh()
	{
		$token = TokenAuth::getRefreshToken();
        return $this->respondWithToken($token);
	}
    public function getToken()
    {
    	$data = [
    		'name'   => 'joe',
    		'age'    => 18,
    		'sex'    => 'girl',
    		'like'   => 'sport'
    	];
    	$flag = 'user-name'; // 用户的唯一标识
    	$token = TokenAuth::getCreateAccessToken( $data, $flag );
    	return $this->respondWithToken($token);
    }
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => gettl()
        ]);
    }
    public function me()
    {
        return authUser();
    }
    public function logout()
    {
        TokenAuth::TokenAddBlacklist();
        return response()->json(['status'=>'success','message' => 'Successfully logged out']);
    }

}
```
### Middleware
```php
<?php

namespace Jybtx\TokenAuth\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Jybtx\TokenAuth\Support\CreateToken;
use Jybtx\TokenAuth\Support\TokenValidator;
use Jybtx\TokenAuth\Support\TokenBlackList;
use Jybtx\TokenAuth\Support\AuthenticationHeader;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

abstract class BaseMiddleware
{
    use TokenValidator,AuthenticationHeader
        ,TokenBlackList,CreateToken;
    /**
     * [Check the token value of the user's web page]
     * @author jybtx
     * @date   2019-12-16
     * @return [type]     [description]
     */
    public function checkForToken()
    {
        if ( !$this->getVerifyToken( getoken() ) ) 
        {
            throw new UnauthorizedHttpException('token-auth', 'Token not provided');
        }
    }
    /**
     * [checkTokenRefreshTimeForRestApi description]
     * @author jybtx
     * @date   2020-05-06
     * @return [type]     [description]
     */
    public function checkTokenRefreshTimeForRestApi()
    {
        return $this->verifyRefresh( getoken() );
    }
    /**
     * [Check token value of user REST API]
     * @author jybtx
     * @date   2019-12-16
     * @return [type]     [description]
     */
    public function checkTokenForRestApi()
    {
        return $this->getVerifyToken( getoken() );
    }
	/**
     * Set the authentication header.
     *
     * @param  \Illuminate\Http\Response|\Illuminate\Http\JsonResponse  $response
     * @param  string|null  $token
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function setAuthenticationHeaders($response, $token = null)
    {
        return $this->setAuthenticationHeader($response, $token);
    }

    /**
     * Check whether token is in blacklist
     *
     */
    public function checkTokenIsInBlacklist()
    {
        if ( Cache::has( getoken() ) )
        {
            throw new UnauthorizedHttpException('token-auth', 'Token expired');
        }
    }
    /**
     * [Check the blacklist token value of the user REST API]
     * @author jybtx
     * @date   2019-12-16
     * @return [type]     [description]
     */
    public function checkTokenIsInBlacklistForApi()
    {
        return Cache::has( getoken() );
    }
}
```
```php
<?php

use Jybtx\TokenAuth\Http\Middleware\BaseMiddleware;

class xxxxMiddleware extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /**
         * 验证token是否在黑名单中
         */
        if ( $this->checkTokenIsInBlacklistForApi() ) return response()->json(['status'=>100,'message'=>"token 无效请重新登录!"]);
        /**
         * 检查token是否有效
         * token在有效期内重新更新token值
         * 设置响应头
         */
        if ( !$this->checkTokenForRestApi() ) {
            if ( $this->checkTokenRefreshTimeForRestApi() ) {
                return $this->setAuthenticationHeaders($next($request));
            } else {
                return response()->json(['status'=>100,'message'=>"token 无效请重新登录！"]);
            }
        }
        return $next($request);
    }
}
```
### helps function
get user all information
```php
authUser()
```
get users token
```php
getoken()
```

get config ttl time
```php
gettl()
```

## License

The MIT License (MIT)
