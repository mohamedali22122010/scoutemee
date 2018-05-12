<?php

use Aws\Laravel\AwsServiceProvider;

return [

    /*
    |--------------------------------------------------------------------------
    | AWS SDK Configuration
    |--------------------------------------------------------------------------
    |
    | The configuration options set in this file will be passed directly to the
    | `Aws\Sdk` object, from which all client objects are created. The minimum
    | required options are declared here, but the full set of possible options
    | are documented at:
    | http://docs.aws.amazon.com/aws-sdk-php/v3/guide/guide/configuration.html
    |
    */
    'credentials' => [
        'key'    => env('AWS_ACCESS_KEY_ID', 'your-key'),
        'secret' =>  env('AWS_SECRET_KEY', 'your-secret'),
    ],
    'region' => env('AWS_REGION', 'eu-west-1'),
    'version' => 'latest',
    'ua_append' => [
        'L5MOD/' . AwsServiceProvider::VERSION,
    ],
    'DomainName'=>env('CLOUD_SEARCH_DOMAIN_NAME', 'scoutmee-dev'),
    'CloudSearchDomain' => ['endpoint' => env('CLOUD_SEARCH_ENDPOINT', 'http://doc-scoutmee-dev-vsdynjte5z7qkxwnf5t2lwhgey.eu-west-1.cloudsearch.amazonaws.com')],
];
