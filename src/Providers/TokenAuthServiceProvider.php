<?php

namespace Jybtx\TokenAuth\Provider;

use Illuminate\Support\ServiceProvider;
use Jybtx\TokenAuth\Console\TokenSecretCommand;
use Jybtx\TokenAuth\JwtAuthToken;

class TokenAuthServiceProvider extends ServiceProvider
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
            __DIR__."/../config/token-auth.php" => config_path('token-auth.php'),
        ]);
    }
    /**
     * Merge configuration.
     */
    private function mergeConfig()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/token-auth.php', 'token-auth'
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
        $this->app->singleton('TokenAuth', function () {
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
                TokenSecretCommand::class,
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