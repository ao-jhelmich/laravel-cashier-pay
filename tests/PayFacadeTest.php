<?php

use Paynl\LaravelCashier\Facades\Pay;
use Paynl\LaravelCashier\Pay as PayService;

it('resolves the pay facade root', function () {
    expect(Pay::getFacadeRoot())->toBeInstanceOf(PayService::class);
});

it('throws when service id is not configured', function () {
    config(['cashier.service_id' => null]);

    Pay::orderCreate('https://shop.test/return', 'https://shop.test/exchange');
})->throws(InvalidArgumentException::class, 'Pay service ID is not configured');

it('uses bearer auth by default', function () {
    config(['cashier.token' => 'bearer-token']);

    $pay = new PayService;
    $auth = $pay->config()->get('authentication');

    expect($auth->get('type'))->toBe('Bearer')
        ->and($auth->get('password'))->toBe('bearer-token');
});

it('builds basic auth from api token code when configured', function () {
    config([
        'cashier.auth_scheme' => 'basic',
        'cashier.api_token_code' => 'AT-1234-5678',
        'cashier.token' => 'api-token-secret',
        'cashier.service_id' => 'SL-1234-5678',
    ]);

    $pay = new PayService;
    $auth = $pay->config()->get('authentication');

    expect($auth->get('type'))->toBe('Basic')
        ->and($auth->get('username'))->toBe('AT-1234-5678')
        ->and($auth->get('password'))->toBe('api-token-secret');
});

it('builds basic auth from service id when api token code is omitted', function () {
    config([
        'cashier.auth_scheme' => 'basic',
        'cashier.api_token_code' => null,
        'cashier.token' => 'service-secret',
        'cashier.service_id' => 'SL-9999-8888',
    ]);

    $pay = new PayService;
    $auth = $pay->config()->get('authentication');

    expect($auth->get('username'))->toBe('SL-9999-8888')
        ->and($auth->get('password'))->toBe('service-secret');
});

it('sets return and exchange urls on order create', function () {
    config([
        'cashier.token' => 'secret',
        'cashier.service_id' => 'SL-1234-5678',
    ]);

    $body = Pay::orderCreate(
        returnUrl: 'https://shop.test/checkout/done',
        exchangeUrl: 'https://shop.test/webhooks/pay',
    )
        ->setAmount(250)
        ->getBodyParameters();

    expect($body['returnUrl'])->toBe('https://shop.test/checkout/done')
        ->and($body['exchangeUrl'])->toBe('https://shop.test/webhooks/pay')
        ->and($body['serviceId'])->toBe('SL-1234-5678');
});
