<?php

namespace Iqbalatma\LaravelLogTelegramChannel;
use Iqbalatma\LaravelLogTelegramChannel\Services\TelegramHandler;
use Monolog\Level;
use Monolog\Logger as MonologLogger;
class Logger
{
    /**
     * @param array $configuration
     * @return MonologLogger
     */
    public function __invoke(array $configuration): MonologLogger
    {
        $level = $configuration['level'] ?? Level::Debug;
        return new MonologLogger(
            "iqbalatma/laravel-log-telegram-channel",
            [new TelegramHandler($level)]
        );
    }
}
