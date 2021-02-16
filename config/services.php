<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    // 'mailgun' => [
    //     'domain' => env('MAILGUN_DOMAIN'),
    //     'secret' => env('MAILGUN_SECRET'),
    // ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION'),
    ],

    'sns' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION'),
        'version' => 'latest',
    ],

    // 'sparkpost' => [
    //     'secret' => env('SPARKPOST_SECRET'),
    // ],

    // 'stripe' => [
    //     'model' => App\User::class,
    //     'key' => env('STRIPE_KEY'),
    //     'secret' => env('STRIPE_SECRET'),
    // ],

    /* Social Media */
    'facebook' => [
        'client_id'     => env('FB_ID', ''),
        'client_secret' => env('FB_SECRET', ''),
        'redirect'      => env('FB_URL', ''),
    ],

    'twitter' => [
        'client_id'     => env('TWITTER_ID', ''),
        'client_secret' => env('TWITTER_SECRET', ''),
        'redirect'      => env('TWITTER_URL', ''),
    ],

    'google' => [
        'client_id'     => env('GOOGLE_ID', ''),
        'client_secret' => env('GOOGLE_SECRET', ''),
        'redirect'      => env('GOOGLE_URL', ''),
    ],

    'redis' => [
 
        'cluster' => false,
     
        'default' => [
            'host'     => env('REDIS_HOST', '127.0.0.1'),
            'port'     => env('REDIS_PORT', 6379),
            'database' => env('REDIS_DATABASE', 0),
            'client'   => env('REDIS_CLIENT', 'predis'),
        ],
     
    ],

];