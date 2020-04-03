<?php


namespace Aes\Providers;

use Aes\Php\AesPhp;
use Illuminate\Support\ServiceProvider;

class AesProvider extends ServiceProvider
{
    protected $key;
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true; //是否延时绑定


    /**
     * Bootstrap the application services.
     * 执行文案register后执行此方法
     * @return void
     */
    public function boot()
    {
        if (!$this->key) {
            // Config path.
            $config_path = __DIR__ . '/../../config/aes.php';

            // Publish config.
            $this->publishes(
                [$config_path => config_path('aes.php')],
                'aes'
            );
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->key = isset($this->app['config']['app']['key']) && $this->app['config']['app']['key'] ? $this->app['config']['app']['key'] : '';
        if (!$this->key) {
            //获取配置文件
            $config_path = __DIR__ . '/../../config/aes.php';
            // 合并配置文件
            $this->mergeConfigFrom(
                $config_path,
                'aes'
            );
            $this->key = $this->app['config']['aes']['KEY'];
        }
        $this->app->singleton('aes', function ($app) {
            return new AesPhp($this->key);
        });

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'aes'
        ];
    }
}