<?php

use Paynl\LaravelCashier\Facades\Pay;
use Paynl\LaravelCashier\Pay as PayService;

it('resolves the pay facade root', function () {
    expect(Pay::getFacadeRoot())->toBeInstanceOf(PayService::class);
});

it('applies return and exchange urls from config on order create', function () {
    config([
        'cashier.service_id' => 'SL-1234-5678',
        'cashier.return_url' => 'https://shop.test/return',
        'cashier.exchange_url' => 'https://shop.test/exchange',
    ]);

    $body = Pay::orderCreate()
        ->setAmount(100)
        ->getBodyParameters();

    expect($body['returnUrl'])->toBe('https://shop.test/return')
        ->and($body['exchangeUrl'])->toBe('https://shop.test/exchange')
        ->and($body['serviceId'])->toBe('SL-1234-5678');
});

it('allows overriding return and exchange urls per order create', function () {
    config([
        'cashier.return_url' => 'https://shop.test/default-return',
        'cashier.exchange_url' => 'https://shop.test/default-exchange',
    ]);

    $body = Pay::orderCreate(
        returnUrl: 'https://shop.test/checkout/done',
        exchangeUrl: 'https://shop.test/webhooks/pay',
    )
        ->setServiceId('SL-9999-8888')
        ->setAmount(250)
        ->getBodyParameters();

    expect($body['returnUrl'])->toBe('https://shop.test/checkout/done')
        ->and($body['exchangeUrl'])->toBe('https://shop.test/webhooks/pay');
});
