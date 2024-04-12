<?php

namespace Iqbalatma\LaravelLogTelegramChannel\Services;

use Exception;
use Iqbalatma\LaravelLogTelegramChannel\FallbackLogExceptionHandler;
use Monolog\Formatter\FormatterInterface;
use Monolog\LogRecord;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TelegramFormatter implements FormatterInterface
{
    private LogRecord $logRecord;
    private string $message;
    private string $dateFormat;

    public function __construct()
    {
        $this->message = "<b>%level_name%</b> (%channel%) [%date%]\n\n%message%\n\n%context%%extra%%exception%";
        $this->dateFormat = "Y-m-d H:i:s e";
    }

    /**
     * @param LogRecord $record
     * @return string
     */
    public function format(LogRecord $record): string
    {
        $this->setLogRecord($record)
            ->formatMessage()
            ->formatLogHeader()
            ->formatExtraData()
            ->formatContextData()
            ->formatExceptionData();
        return $this->message;
    }


    /**
     * @param LogRecord $logRecord
     * @return $this
     */
    protected function setLogRecord(LogRecord $logRecord): self
    {
        $this->logRecord = $logRecord;
        return $this;
    }


    /**
     * @return $this
     */
    protected function formatLogHeader(): self
    {
        try {
            $this->message = str_replace(
                ['%level_name%', '%channel%', '%date%'],
                [$this->logRecord->level->getName(), $this->logRecord->channel, $this->logRecord->datetime->format($this->dateFormat)],
                $this->message
            );
        } catch (Exception $e) {
            FallbackLogExceptionHandler::handle($e);
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function formatMessage(): self
    {
        try {
            $this->message = str_replace("%message%", $this->logRecord->message, $this->message);
        } catch (Exception $e) {
            FallbackLogExceptionHandler::handle($e);
        }

        return $this;
    }


    /**
     * @return $this
     */
    protected function formatContextData(): self
    {
        try {
            if ($this->logRecord->context) {
                $context = "<b>Context:</b> \n";
                foreach ($this->logRecord->context as $key => $value) {
                    if ($key === "exception" || $key === "extra" || $key === "view") {
                        continue;
                    }

                    if ($value) {
                        if (is_array($value)) {
                            $value = json_encode($value, JSON_THROW_ON_ERROR);
                        }
                        $context .= "\t$key : $value\n";
                    }
                }

                $this->message = str_replace('%context%', $context . "\n", $this->message);
            } else {
                $this->message = str_replace('%context%', '', $this->message);
            }
        } catch (Exception $e) {
            FallbackLogExceptionHandler::handle($e);
        }

        return $this;
    }


    /**
     * @return $this
     */
    protected function formatExtraData(): self
    {
        try {
            if (isset($this->logRecord->context["extra"])) {
                $extra = "<b>Extra:</b> \n";
                foreach ($this->logRecord->context["extra"] as $key => $value) {
                    if ($value) { //to prevent null value
                        $extra .= "\t$key : $value\n";
                    }
                }
                $this->message = str_replace('%extra%', $extra . "\n", $this->message);
            } else {
                $this->message = str_replace('%extra%', '', $this->message);
            }
        } catch (Exception $e) {
            FallbackLogExceptionHandler::handle($e);
        }
        return $this;
    }


    /**
     * @return $this
     */
    public function formatExceptionData(): self
    {
        try {
            if (isset($this->logRecord->context["exception"])) {
                /** @var Exception|HttpException $logRecordException */
                $logRecordException = $this->logRecord->context["exception"];
                $exception = "<b>Exception :</b> \n";

                if ($logRecordExceptionMessage = $logRecordException->getMessage()) {
                    $exception .= "\tMessage : $logRecordExceptionMessage\n";
                }

                $exception .= "\tCode : " . $logRecordException->getCode() . "\n";


                if ($logRecordException instanceof HttpException && $logRecordExceptionHttpStatusCode = $logRecordException->getStatusCode()) {
                    $exception .= "\tHttp Status Code : $logRecordExceptionHttpStatusCode\n";
                }


                if ($logRecordExceptionFile = $logRecordException->getFile()) {
                    $exception .= "\tFile : $logRecordExceptionFile\n";
                }

                if ($logRecordExceptionLine = $logRecordException->getLine()) {
                    $exception .= "\tLine : $logRecordExceptionLine\n";
                }

                if ($logRecordExceptionTrace = $logRecordException->getTraceAsString()) {
                    $exception .= "\tTrace : $logRecordExceptionTrace\n";
                }


                $this->message = str_replace('%exception%', $exception . "\n", $this->message);
            } else {
                $this->message = str_replace('%exception%', '', $this->message);
            }
        } catch (Exception $e) {
            FallbackLogExceptionHandler::handle($e);
        }

        return $this;
    }

    /**
     * @param array $records
     * @return void
     */
    public function formatBatch(array $records)
    {
        // TODO: Implement formatBatch() method.
    }
}
