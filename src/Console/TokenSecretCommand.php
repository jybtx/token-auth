<?php

namespace Jybtx\TokenAuth\Console;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\Cache;

class TokenSecretCommand extends Command
{
	/**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'token:generate
				    {--show : Display the key instead of modifying files}';

	/**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set the JWTAuth secret key used to sign the tokens';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    	$key = $this->generateRandomKey();
    	if ($this->option('show')) {
            $this->comment($key);
            return;
        }
        if (file_exists($path = $this->envPath()) === false) {
            return $this->displayKey($key);
        }
        if (Str::contains(file_get_contents($path), 'JWT_SECRET_KEY') === false) {
            // create new entry
            file_put_contents($path, PHP_EOL."JWT_SECRET_KEY=$key".PHP_EOL, FILE_APPEND);
        } else {
            // update existing entry
            file_put_contents($path, str_replace(
                'JWT_SECRET_KEY='.$this->laravel['config']['token-auth.secret'],
                'JWT_SECRET_KEY='.$key, file_get_contents($path)
            ));
        }
        $this->cacheSecretKey($key);
        $this->displayKey($key);
    }
    /**
     * Generate a random key for the application.
     *
     * @return string
     */
    protected function generateRandomKey()
    {
        return 'base64:'.base64_encode(
            strtoupper(bin2hex(Encrypter::generateKey($this->laravel['config']['app.cipher'])))
        );
    }
    /**
     * Display the key.
     *
     * @param  string  $key
     *
     * @return void
     */
    protected function displayKey(string $key)
    {
        $this->laravel['config']['token-auth.secret'] = $key;
        $this->info("token-auth secret set successfully.");
    }
    /**
     * Get the .env file path.
     *
     * @return string
     */
    protected function envPath()
    {
        if (method_exists($this->laravel, 'environmentFilePath')) {
            return $this->laravel->environmentFilePath();
        }
        // check if laravel version Less than 5.4.17
        if (version_compare($this->laravel->version(), '5.4.17', '<')) {
            return $this->laravel->basePath().DIRECTORY_SEPARATOR.'.env';
        }
        return $this->laravel->basePath('.env');
    }
    /**
     * [永久存储生成的key]
     * @Author jybtx
     * @date   2020-11-18
     * @param  string     $attributes [description]
     * @return [type]                 [description]
     */
    protected function cacheSecretKey(string $attributes)
    {
        if ( $this->laravel['config']['token-auth.cache_open'] ) {
            Cache::put($this->laravel['config']['token-auth.cache_key'],$attributes);
        }
    }
}