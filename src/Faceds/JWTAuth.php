<?php

namespace Jybtx\JwtAuth\Facades;

use Illuminate\Support\Facades\Facade;

class JWTAuth extends Facade
{
	
	/**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'JwtAuth';
    }
}