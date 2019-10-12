<?php

namespace Jybtx\JwtAuth\Provider;

use Illuminate\Support\ServiceProvider;
use Jybtx\JwtAuth\Console\JWTSecretCommand;
use Jybtx\JwtAuth\JwtAuthToken;

class JwtAuthServiceProvider extends ServiceProvider
{
	
	/**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfig();
    }
    /**
     * Configure package paths.
     */
    private function configurePaths()
    {
        $this->publishes([
            __DIR__."/../config/jwt-auth.php" => config_path('jwt-auth.php'),
        ]);
    }
    /**
     * Merge configuration.
     */
    private function mergeConfig()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/jwt-auth.php', 'jwt-auth'
        );
    }
    /**
     * [singleton description]
     * @author jybtx
     * @date   2019-09-21
     * @param  string     $value [description]
     * @return [type]            [description]
     */
    private function getRegisterSingleton()
    {
        $this->app->singleton('JwtAuth', function () {
            return new JwtAuthToken();
        });
    }
    /**
     * [get Artisan Command description]
     * @author jybtx
     * @date   2019-10-09
     * @return [type]     [description]
     */
    public function getArtisanCommand()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                JWTSecretCommand::class,
            ]);
        }
    }
    /**
     * Register any application services.
     *  
     * @return void
     */
    public function register()
    {
        $this->configurePaths();        
        $this->getRegisterSingleton();
        $this->getArtisanCommand();
    }
	
}