<?php

namespace Iqbalatma\LaravelLogTelegramChannel\Services;

use Iqbalatma\LaravelLogTelegramChannel\Jobs\SendMessageViaBotJob;
use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;
use Monolog\Utils;

class TelegramHandler extends AbstractProcessingHandler
{
    public const int MAX_MESSAGE_LENGTH = 4096;
    public const string PARSE_MODE = "html";

    /**
     * @param LogRecord $record
     * @return void
     */
    protected function write(LogRecord $record): void
    {
        foreach ($this->getSplitMessage($record->formatted) as $message) {
            $this->send($message);
        }
    }

    /**
     * @param string $message
     * @return void
     */
    protected function send(string $message): void
    {
        SendMessageViaBotJob::dispatch($message);
    }

    /**
     * @return FormatterInterface
     */
    public function getFormatter(): FormatterInterface
    {
        return new TelegramFormatter();
    }


    /**
     * @desription this method use to truncate and send message multiple times
     * @param string $message
     * @return array
     */
    protected function getSplitMessage(string $message): array
    {
        $truncatedMarker = "(...truncated)";

        if (config("log_telegram_channel.is_truncate_message") && strlen($message) > self::MAX_MESSAGE_LENGTH) {
            return [Utils::substr($message, 0, self::MAX_MESSAGE_LENGTH - strlen($truncatedMarker)) . $truncatedMarker];
        }

        return str_split($message, self::MAX_MESSAGE_LENGTH);
    }
}
