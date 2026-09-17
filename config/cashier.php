<?php

use PayNL\Sdk\Config\Config;

return [

    /*
    |--------------------------------------------------------------------------
    | Pay. API credentials
    |--------------------------------------------------------------------------
    |
    | auth_scheme "bearer" (default): Authorization: Bearer {PAYNL_TOKEN}
    |
    | auth_scheme "basic": HTTP Basic for connect.pay.nl
    | - Merchant: PAYNL_API_TOKEN_CODE (AT) + PAYNL_TOKEN (API token), or
    | - Sales location: PAYNL_SERVICE_ID (SL) + PAYNL_TOKEN (service secret)
    |
    */

    'auth_scheme' => env('PAYNL_AUTH_SCHEME', 'bearer'),

    'api_token_code' => env('PAYNL_API_TOKEN_CODE'),

    'token' => env('PAYNL_TOKEN'),

    'service_id' => env('PAYNL_SERVICE_ID'),

    'core' => env('PAYNL_API_CORE', Config::TGU1),

    // https://merchant.example/return?id=99006002008X42f3&reference=&statusAction=PAID&statusCode=100&ticket=
    'return_url' => env('CASHIER_RETURN_URL', env('APP_URL').'/dashboard'),

    'exchange_url' => env('CASHIER_EXCHANGE_URL', env('APP_URL').'/pay/webhook'),
    /*
    |--------------------------------------------------------------------------
    | Pay. Plans
    |--------------------------------------------------------------------------
    |
    | Plan consists of a name and a price.
    |
    | Example:
    | [
    |     'name' => 'basic',
    |     'price' => 1000,
    | ]
    |
    */
    'plans' => [
        'basic' => [
            'name' => 'basic',
            'description' => 'Basic plan',
            'price' => 1000,
        ],
    ],
];
