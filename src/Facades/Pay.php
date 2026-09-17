<?php

namespace Paynl\LaravelCashier\Facades;

use Illuminate\Support\Facades\Facade;
use Paynl\LaravelCashier\Pay as PayService;
use PayNL\Sdk\Config\Config;
use PayNL\Sdk\Model\Request\OrderCreateRequest;
use PayNL\Sdk\Request\RequestData;

/**
 * @method static Config config()
 * @method static PayService setConfig(Config $config)
 * @method static mixed request(RequestData $request)
 * @method static OrderCreateRequest orderCreate(?string $returnUrl = null, ?string $exchangeUrl = null)
 *
 * @see PayService
 */
class Pay extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return PayService::class;
    }
}
