<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Encryption\Encrypter;

class ApiKeyGenerateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api-token:generate
                    {--fetch : Fetch current API token}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set the API token';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $key = $this->generateRandomToken();

        if ($this->option('fetch')) {
            return $this->line('<comment>'.$this->laravel['config']['app.api_token'].'</comment>');
        }

        // Next, we will replace the API token in the environment file.
        // secure random byte generator and is later base64 encoded for storage.
        if (! $this->setTokenInEnvironmentFile($key)) {
            return;
        }

        $this->laravel['config']['app.api_token'] = $key;

        $this->info('API token set successfully. Please use below API token for your application.');

        $this->line('<comment>'.$key.'</comment>');
    }

    /**
     * Generate a random key for the application.
     *
     * @return string
     */
    protected function generateRandomToken()
    {
        return bin2hex(random_bytes(20));
    }

    /**
     * Set the API token in the environment file.
     *
     * @param  string  $key
     * @return bool
     */
    protected function setTokenInEnvironmentFile($key)
    {
        $this->writeNewEnvironmentFileWith($key);

        return true;
    }

    /**
     * Write a new environment file with the given key.
     *
     * @param  string  $key
     * @return void
     */
    protected function writeNewEnvironmentFileWith($key)
    {
        file_put_contents($this->laravel->environmentFilePath(), preg_replace(
            $this->tokenReplacementPattern(),
            'API_TOKEN='.$key,
            file_get_contents($this->laravel->environmentFilePath())
        ));
    }

    /**
     * Get a regex pattern that will match env API_TOKEN with any random key.
     *
     * @return string
     */
    protected function tokenReplacementPattern()
    {
        $escaped = preg_quote('='.$this->laravel['config']['app.api_token'], '/');

        return "/^API_TOKEN{$escaped}/m";
    }
}
