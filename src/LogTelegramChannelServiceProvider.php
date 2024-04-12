<?php

namespace Iqbalatma\LaravelLogTelegramChannel;

use Illuminate\Support\ServiceProvider;

class LogTelegramChannelServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot():void
    {
        $this->publishes([
            __DIR__.'/config/log_telegram_channel.php' => config_path("log_telegram_channel.php")
        ]);
    }

    /**
     * @return void
     */
    public function register():void
    {
        $this->mergeConfigFrom(__DIR__.'/config/log_telegram_channel.php','log_telegram_channel');
    }
}
