<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'sharp_api' => [
        'base_url' => env('SHARP_API_BASE_URL'),
        'api_key' => env('SHARP_API_KEY'),
        'version' => env('SHARP_API_VERSION', 'v1'),
        'timeout' => env('SHARP_API_TIMEOUT', 30),
        'retry_times' => env('SHARP_API_RETRY_TIMES', 3),
        'retry_delay' => env('SHARP_API_RETRY_DELAY', 100),
        'skills_path' => env('SHARP_API_SKILLS_PATH', '/skills'),
    ],
];
