<?php


namespace Aes\Providers;

use Aes\Php\AesPhp;
use Illuminate\Support\ServiceProvider;

class AesProvider extends ServiceProvider
{
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
        // Config path.
        $config_path = __DIR__ . '/../../config/aes.php';

        // Publish config.
        $this->publishes(
            [$config_path => config_path('aes.php')],
            'aes'
        );
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //获取配置文件
        $config_path = __DIR__ . '/../../config/aes.php';
        // 合并配置文件
        $this->mergeConfigFrom(
            $config_path,
            'aes'
        );
        $this->app->singleton('aes', function ($app) {
            return AesPhp::init($app['config']['aes']['KEY']);
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