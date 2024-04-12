<?php

namespace App\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Iqbalatma\LaravelLogTelegramChannel\FallbackLogExceptionHandler;
use Iqbalatma\LaravelLogTelegramChannel\Services\TelegramHandler;

class SendMessageViaBotJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected string $message)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $url = config("log_telegram_channel.host") . "/" . config("log_telegram_channel.token") . "/sendMessage";
            Http::post($url, [
                "text" => $this->message,
                "chat_id" => config("log_telegram_channel.channel_id"),
                "parse_mode" => TelegramHandler::PARSE_MODE,
            ]);
        } catch (Exception $e) {
            FallbackLogExceptionHandler::handle($e);
        }
    }
}
