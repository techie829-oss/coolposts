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
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
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

    'adsense' => [
        'enabled' => env('ADSENSE_ENABLED', false),
        'client_id' => env('ADSENSE_CLIENT_ID'),
        'client_secret' => env('ADSENSE_CLIENT_SECRET'),
        'refresh_token' => env('ADSENSE_REFRESH_TOKEN'),
        'account_id' => env('ADSENSE_ACCOUNT_ID'),
    ],

    'admob' => [
        'enabled' => env('ADMOB_ENABLED', false),
        'client_id' => env('ADMOB_CLIENT_ID'),
        'client_secret' => env('ADMOB_CLIENT_SECRET'),
        'refresh_token' => env('ADMOB_REFRESH_TOKEN'),
        'account_id' => env('ADMOB_ACCOUNT_ID'),
    ],

    'maxmind' => [
        'account_id' => env('MAXMIND_ACCOUNT_ID'),
        'license_key' => env('MAXMIND_LICENSE_KEY'),
        'use_web_service' => env('MAXMIND_USE_WEB_SERVICE', false),
        'database_path' => env('MAXMIND_DATABASE_PATH', storage_path('app/geoip/GeoLite2-Country.mmdb')),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI'),
    ],

    'linkedin' => [
        'client_id' => env('LINKEDIN_CLIENT_ID'),
        'client_secret' => env('LINKEDIN_CLIENT_SECRET'),
        'redirect' => env('LINKEDIN_REDIRECT_URI'),
    ],

];
