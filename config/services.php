<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'mandrill' => [
        'secret' => env('MANDRILL_SECRET'),
    ],

    'ses' => [
        'key'    => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'stripe' => [
        'model'  => App\User::class,
        'key'    => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
    
    'facebook' => [
        'client_id' => '1758182291070439',//'1079929165390465',//'1758182291070439',
        'client_secret' => '5ebf2484bea55b74106e5e95c53e1a1e', // 'eca5f629e12d0dab1f768ab75b3a4474', // '5ebf2484bea55b74106e5e95c53e1a1e',
        'redirect' => env('APP_URL') . '/callback', //'https://f636f7f5.ngrok.io/scoutmee/public/callback',// 'http://moselaymd-scoutmee-dev.eu-west-1.elasticbeanstalk.com/callback',
         /*'client_id' => '1079929165390465',//'1758182291070439',
        'client_secret' => 'eca5f629e12d0dab1f768ab75b3a4474', // '5ebf2484bea55b74106e5e95c53e1a1e',
        'redirect' => 'https://16757788.ngrok.io/scoutmee/public/callback',// 'http://moselaymd-scoutmee-dev.eu-west-1.elasticbeanstalk.com/callback',*/
    ],

];
