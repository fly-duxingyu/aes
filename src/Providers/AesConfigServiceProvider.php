<?php

namespace Duxingyu\Aes\Providers;

use Illuminate\Support\ServiceProvider;

class AesConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //获取配置文件
        $config_path = config_path() . '/aes.php';
        if (file_exists($config_path)) {
            // 合并配置文件
            $this->mergeConfigFrom(
                $config_path,
                'aesConfig'
            );
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Config path.
        $config_path = config_path() . '/aes.php';
        if (file_exists($config_path)) {
            // Publish config.
            $this->publishes(
                [$config_path => config_path('aesConfig.php')],
                'aesConfig'
            );
        }

    }
}
