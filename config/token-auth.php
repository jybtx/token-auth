<?php

return [

	/*
    |--------------------------------------------------------------------------
    | JWT Authentication Secret
    |--------------------------------------------------------------------------
    |
    | Don't forget to set this in your .env file, as it will be used to sign
    | your tokens. A helper command is provided for this:
    | `php artisan jwt:key`
    |
    */
    'secret' => env('JWT_SECRET_KEY',''),

    /*
    |--------------------------------------------------------------------------
    | JWT time to live
    |--------------------------------------------------------------------------
    |
    | Specify the length of time (in minutes) that the token will be valid for.
    | Defaults to 2 hour.
    |
    | You can also set this to null, to yield a never expiring token.
    | Some people may want this behaviour for e.g. a mobile app.
    | This is not particularly recommended, so make sure you have appropriate
    | systems in place to revoke the token if necessary.
    | Notice: If you set this to null you should remove 'exp' element from 'required_claims' list.
    |
    */
    'ttl' => env('JWT_ACCESS_TTL', 60 * 2 ),

    /*
    |--------------------------------------------------------------------------
    | Refresh time to live
    |--------------------------------------------------------------------------
    |
    | Specify the length of time (in minutes) that the token can be refreshed
    | within. I.E. The user can refresh their token within a 15 days.
    |
    | You can also set this to null, to yield an infinite refresh time.
    | Some may want this instead of never expiring tokens for e.g. a mobile app.
    | This is not particularly recommended, so make sure you have appropriate
    | systems in place to revoke the token if necessary.
    |
    */
    'refresh_ttl' => env('JWT_REFRESH_TTL', 60 * 60 * 15 ),

    /*
     |
     |--------------------------------------------------------------------------
     |   Do you want to turn on persistent caching
     |--------------------------------------------------------------------------
     |  If you want to cache your keys in the cache, you can turn on this method.
     |  True means you need to cache your generated keys in your memory,
     |  and false means you don't need to cache your keys in memory.
     |  This value only true or false.
     |
     */
    'cache_open'   => true,

    /*
    |--------------------------------------------------------------------------
    |   Key value of persistent cache
    |--------------------------------------------------------------------------
    |
    | This key is the key value in the cache. This key can be obtained from
    | the cache when you need it.
    | The key needs to be enabled to determine whether cache is required.
    */

    'cache_key'  => env('JWT_CACHE_KEY','jwt_cache_key'),

];