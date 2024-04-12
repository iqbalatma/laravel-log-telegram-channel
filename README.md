# Laravel Log Telegram Channel
This is a log handler for custom channel to send log via telegram bot. Telegram bot will send message to channel and you can specify log level.

## How to install
You can install this package via composer
```console
composer require iqbalatma/laravel-log-telegram-channel
```

## How to publish configuration
You can publish configuration file via this command
```console
php artisan vendor:publish --provider="Iqbalatma\LaravelLogTelegramChannel\LogTelegramChannelServiceProvider"
```

## How to add log channel
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
```


## Laravel Log Telegram Channel Configuration File
This configuration file used to set credentials and log handler behavior
```php
<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Telegram Bot Host
    |--------------------------------------------------------------------------
    |
    | This is telegram bot host. You can find this host information at telegram
    | bot documentation https://core.telegram.org/bots/api
    |
    */
    "host" => env("LOG_TELEGRAM_HOST", "https://api.telegram.org"),


    /*
    |--------------------------------------------------------------------------
    | Telegram Bot Token
    |--------------------------------------------------------------------------
    |
    | This is token for authorization. You can get this token when create
    | telegram bot via BotFather. You need to add prefix bot on you generated
    | token. If your token like this 123456:ABC-DEF1234ghIkl-zyx57W2v1u123ew11
    | then you token value for this configuration would be
    | bot123456:ABC-DEF1234ghIkl-zyx57W2v1u123ew11
    |
    */
    "token" => env("LOG_TELEGRAM_TOKEN", null),


    /*
    |--------------------------------------------------------------------------
    | Telegram Channel Id
    |--------------------------------------------------------------------------
    |
    | This is channel id of target room chat channel. You can get this value
    | via username of the channel with format @channelusername. If your channel
    | is private, try to change channel into public and then send message via
    | postman to get response information of channel id. The value would be like
    | this -1002017173213. If you are using private channel, please use this id
    | format.
    |
    */
    "channel_id" => env("LOG_TELEGRAM_CHANNEL_ID", null),


    /*
    |--------------------------------------------------------------------------
    | Truncate Message
    |--------------------------------------------------------------------------
    |
    | When you send message via bot telegram, the message has length limit of
    | string. So you need to tell is the log message will be truncated, or
    | the message will send multiple times. If you set false, the message will
    | not truncated and send multiple times.
    |
    */
    "is_truncate_message" => env("LOG_TELEGRAM_IS_TRUNCATE_MESSAGE", false),



    /*
    |--------------------------------------------------------------------------
    | Fallback Channel
    |--------------------------------------------------------------------------
    |
    | There are conditions when this library got some failure and throw exception.
    | In this case, we will catch that exception and send log to this specific
    | channel to prevent infinity loop.
    |
    */
    "fallback_channel" => env("LOG_TELEGRAM_FALLBACK_CHANNEL", "single"),
];

```
