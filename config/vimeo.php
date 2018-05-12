<?php

/*
 * This file is part of Laravel Vimeo.
 *
 * (c) Vincent Klaiber <hello@vinkla.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Default Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the connections below you wish to use as
    | your default connection for all work. Of course, you may use many
    | connections at once using the manager class.
    |
    */

    'default' => 'main',

    /*
    |--------------------------------------------------------------------------
    | Vimeo Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the connections setup for your application. Example
    | configuration has been included, but you may add as many connections as
    | you would like.
    |
    */

    'connections' => [

        // 'main' => [
        //     'client_id' => '7bf8c704afbd6608782b1e63c0f974a84c0a3e1a',
        //     'client_secret' => '+DpnEqNvrnkrbmOr33G5btEhWXWjd/JCw+lNnRvZAgNfDqgtPqadTsdtduas3TY9pU2SbuMsGc2urIxJEM1uQrrp/Nx/3mLSsFKLBS9EgsvrLpp0DaJBpPO4dC1ahq3l',
        //     'access_token' => '9550a8a0f5adc6249e5e8b47e41fce28',
        // ],

        'main' => [
            'client_id' => env('VIMEO_ID'),
            'client_secret' => env('VIMEO_SECRET'),
            'access_token' => env('VIMEO_TOKEN'),
        ],

        'alternative' => [
            'client_id' => 'your-client-id',
            'client_secret' => 'your-client-secret',
            'access_token' => null,
        ],

    ],

];
