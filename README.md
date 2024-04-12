# Laravel Log Telegram Channel
This is a log handler for custom channel to send log via telegram bot. Telegram bot will send message to channel and you can specify log level.

## How to install
You can install this package via composer
```console
composer require iqbalatma/laravel-log-telegram-channel
```

## How to publish configuration ?
You can publish configuration file via this command
```console
php artisan vendor:publish --provider="Iqbalatma\LaravelLogTelegramChannel\LogTelegramChannelServiceProvider"
```

## How to add log channel ?
Before use this log handler, you must add channel for this handler in logging configuration file. Open config/logging.php and add this channel
```php
<?php


return [

  #this is default channel when logging if are not specifying the channel
  'default' => 'stackk',
  'channels' => [
      #when you choose channel with driver stack, the log will send into multiple channel
      #in this case, you will send into 2 channels, single and telegram.
      'stack' => [
            'driver' => 'stack',
            'channels' => ["single", "telegram"],
            'ignore_exceptions' => false,
      ],

      'single' => [
          'driver' => 'single',
          'path' => storage_path('logs/laravel.log'),
          'level' => env('LOG_LEVEL', 'debug'),
          'replace_placeholders' => true,
      ],

      #you can custom level log to log level on that level or higher
      'telegram' => [
            'driver' => 'custom',
            'via' => \Iqbalatma\LaravelLogTelegramChannel\Logger::class,
            'level' => 'debug'
      ],
];
