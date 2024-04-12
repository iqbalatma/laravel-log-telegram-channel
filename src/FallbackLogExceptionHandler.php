<?php

namespace Iqbalatma\LaravelLogTelegramChannel;

use Exception;
use Illuminate\Support\Facades\Log;
use JetBrains\PhpStorm\NoReturn;

/**
 * this class handle exception on this log to stop and fallback log to another channel to stop infinity loop
 */
class FallbackLogExceptionHandler
{
    /**
     * @param Exception $e
     * @return void
     */
    #[NoReturn] public static function handle(Exception $e): void
    {
        Log::channel(config("log_telegram_channel.fallback_channel"))->error($e->getMessage() . "\n" . $e->getTraceAsString());
        die;
    }
}
