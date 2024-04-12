<?php

return [
    "host" => env("LOG_TELEGRAM_HOST", null),
    "token" => env("LOG_TELEGRAM_TOKEN", null),
    "channel_id" => env("LOG_TELEGRAM_CHANNEL_ID", null),
    "is_truncate_message" => env("LOG_TELEGRAM_IS_TRUNCATE_MESSAGE", false),
    "fallback_channel" => env("LOG_TELEGRAM_FALLBACK_CHANNEL", "single"),
];
