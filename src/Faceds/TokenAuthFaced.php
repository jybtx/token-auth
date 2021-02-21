<?php

namespace Jybtx\TokenAuth\Faceds;


use Illuminate\Support\Facades\Facade;

class TokenAuthFaced extends Facade
{
	
	/**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'TokenAuth';
    }
}