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

    'paypal' => [
        'product_id' => env('PAYPAL_PRODUCT_ID'),
    ],

    'paypack' => [
        'client_id' => env('PAYPACK_CLIENT_ID'),
        'client_secret' => env('PAYPACK_CLIENT_SECRET'),
        'webhook_mode' => env('PAYPACK_WEBHOOK_MODE', 'development'),
    ],

    'google_forms' => [
        'visa_consultancy' => env('GOOGLE_FORM_VISA_CONSULTANCY', 'https://docs.google.com/forms/d/e/1FAIpQLSdpJmU0RkK5yB3K_VISA_CONSULTANCY/viewform'),
        'feedback' => env('GOOGLE_FORM_FEEDBACK', 'https://docs.google.com/forms/d/e/1FAIpQLSdpJmU0RkK5yB3K_FEEDBACK/viewform'),
    ],

];
