<?php

return [
    'telegram' => [
        'url_bot' => env('TELEGRAM_API_BOT_URL', ''),
        'token' => env('TELEGRAM_API_TOKEN', ''),
        'chat_id' => env('TELEGRAM_BOT_CHAT_ID', '')
    ]
];
