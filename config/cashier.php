<?php

use PayNL\Sdk\Config\Config;

return [

    /*
    |--------------------------------------------------------------------------
    | Pay. API credentials (HTTP Basic)
    |--------------------------------------------------------------------------
    |
    | Order:Create on connect.pay.nl uses Basic authentication:
    | - Merchant: AT-code (username) + API token 40 chars (password), or
    | - Sales location: SL-code (username) + service secret (password).
    |
    | When api_token_code is set, AT + token is used. Otherwise SL + token.
    |
    */

    'api_token_code' => env('PAYNL_API_TOKEN_CODE'),

    'token' => env('PAYNL_TOKEN'),

    'service_id' => env('PAYNL_SERVICE_ID'),

    'core' => env('PAYNL_API_CORE', Config::TGU1),

    'return_url' => env('CASHIER_RETURN_URL', ENV('APP_URL').'/dashboard'),

    'exchange_url' => env('CASHIER_EXCHANGE_URL', ENV('APP_URL').'/pay/webhook'),
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
