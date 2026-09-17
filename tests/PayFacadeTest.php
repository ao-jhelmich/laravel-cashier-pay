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

it('sets return and exchange urls on order create', function () {
    config(['cashier.service_id' => 'SL-1234-5678']);

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
