<?php

use PayNL\Sdk\Config\Config;

return [

    /*
    |--------------------------------------------------------------------------
    | Pay. API bearer token
    |--------------------------------------------------------------------------
    */

    'token' => env('PAYNL_TOKEN'),
    'service_id' => env('PAYNL_SERVICE_ID'),

    'return_url' => env('PAYNL_RETURN_URL'),

    'exchange_url' => env('PAYNL_EXCHANGE_URL'),

    /*
    |--------------------------------------------------------------------------
    | Pay. API base URL
    |--------------------------------------------------------------------------
    */

    'core' => env('PAYNL_API_CORE', Config::TGU1),

];
