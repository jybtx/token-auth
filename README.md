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
    Jybtx\TokenAuth\Provider\TokenAuthServiceProvider::class,
],
'aliases'  => [
    ...
    "TokenAuth": Jybtx\TokenAuth\Facades\TokenAuthFace::class,
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

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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
    	$token = TokenAuth::getCreateAccessToken($data);
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
use Jybtx\TokenAuth\JwtAuthToken;
use Illuminate\Support\Facades\Cache;
use Jybtx\TokenAuth\Support\TokenValidator;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

abstract class BaseMiddleware
{
	use TokenValidator;

	public function checkForToken()
	{
		if ( !$this->getVerifyToken( getoken() ) ) {
			throw new UnauthorizedHttpException('token-auth', 'Token not provided');
		}
	}
	/**
     * Set the authentication header
     */
    public function setAuthenticationHeader($response, $token = null)
    {
        $token = $token ?: JwtAuthToken::getRefreshToken();
        $response->headers->set('Authorization', 'Bearer '.$token);

        return $response;
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
}
```
### helps function
get user all information
```php
authUser()
```

get config ttl time
```php
gettl()
```
