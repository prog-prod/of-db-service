<?php

return [
    'is-server-for-indexation' => env('IS_SERVER_FOR_INDEXATION'),

    'redirect_allowed_domains' => [
        'onlyfans.com',
        'youtube.com',
        'twitter.com',
        'instagram.com',
        'tiktok.com',
        'facebook.com',
        'reddit.com',
        'youtu.be'
    ],
    'parser-url' => env('PARSER_URL', 'http://parser:3005'),

    'name' => env('APP_NAME', 'Laravel'),

    'env' => env('APP_ENV', 'production'),

    'block_avatars ' => env('BLOCK_AVATARS', false),

    'debug' => (bool)env('APP_DEBUG', false),

    'url' => env('APP_URL', 'http://localhost'),

    'asset_url' => env('ASSET_URL'),

    'timezone' => 'UTC',

    'locale' => 'en',

    'fallback_locale' => 'en',

    'faker_locale' => 'en_US',

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    'maintenance' => [
        'driver' => 'file',
        // 'store'  => 'redis',
    ],
];
