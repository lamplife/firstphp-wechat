<?php

declare(strict_types = 1);

/**
 * Author: 狂奔的螞蟻 <www.firstphp.com>
 * Date: 2020/6/17
 * Time: 11:09 AM
 */

namespace Firstphp\Fpwechat\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Firstphp\Fpwechat\Services\WechatService;

class WechatServiceProvider extends ServiceProvider
{

    protected $defer = false;

    protected $migrations = [
        'CreateWxappConf' => '2020_06_17_110904_create_wechat_conf_table',
    ];


    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../Config/wechat.php' => config_path('wechat.php')
        ], 'config');

        $this->publishes([
            __DIR__.'/../migrations/' => database_path('migrations')
        ], 'migrations');
    }


    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('WechatService', function () {
            $config = Config::get('wechat');
            return new WechatService($config['appid'], $config['appsecret'], $config['token'], $config['encoding_aes_key']);
        });
    }


    /**
     * Publish migration files.
     *
     * @return void
     */
    protected function migration()
    {
        foreach ($this->migrations as $class => $file) {
            if (! class_exists($class)) {
                $this->publishMigration($file);
            }
        }
    }


    /**
     * Publish a single migration file.
     *
     * @param string $filename
     * @return void
     */
    protected function publishMigration($filename)
    {
        $extension = '.php';
        $filename = trim($filename, $extension).$extension;
        $stub = __DIR__.'/../migrations/'.$filename;
        $target = $this->getMigrationFilepath($filename);
        $this->publishes([$stub => $target], 'migrations');
    }


    /**
     * Get the migration file path.
     *
     * @param string $filename
     * @return string
     */
    protected function getMigrationFilepath($filename)
    {
        if (function_exists('database_path')) {
            return database_path('/migrations/'.$filename);
        }
        return base_path('/database/migrations/'.$filename);
    }

}