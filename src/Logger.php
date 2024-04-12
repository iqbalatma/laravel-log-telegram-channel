<?php

namespace Iqbalatma\LaravelLogTelegramChannel;
use Iqbalatma\LaravelLogTelegramChannel\Services\TelegramHandler;
use Monolog\Logger as MonologLogger;
class Logger
{
    /**
     * @return MonologLogger
     */
    public function __invoke(): MonologLogger
    {
        return new MonologLogger(
            "iqbalatma/laravel-log-telegram-channel",
            [new TelegramHandler()]
        );
    }
}
