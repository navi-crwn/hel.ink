<?php

return [
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

    'turnstile' => [
        'site_key' => env('TURNSTILE_SITE_KEY'),
        'secret' => env('TURNSTILE_SECRET_KEY'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('APP_URL') . '/auth/google/callback',
    ],

    'ip_api' => [
        'base_url' => 'http://ip-api.com/json/',
        'fields' => '66846719', // country,countryCode,region,regionName,city,isp,org,as
    ],

    'ipinfo' => [
        'api_key' => env('IPINFO_API_KEY'), // Optional, 50k/month free without key
    ],

    'abstractapi' => [
        'api_key' => env('ABSTRACTAPI_KEY'), // Required, 20k/month free
    ],
    'proxycheck' => [
        'api_key' => env('PROXYCHECK_API_KEY'), // Optional, 1k/day free, 10k with key
    ],

    'iphub' => [
        'api_key' => env('IPHUB_API_KEY'), // Required, 1k/day free
    ],

    'ipqualityscore' => [
        'api_key' => env('IPQUALITYSCORE_API_KEY'), // Required, 5k/month free
    ],

    'geolite' => [
        'account_id' => env('GEOLITE_ACCOUNT_ID'),
        'license_key' => env('GEOLITE_LICENSE_KEY'),
        'city_db' => storage_path('app/GeoLite2-City.mmdb'),
    ],

];
