<?php

namespace Paynl\LaravelCashier\Facades;

use Illuminate\Support\Facades\Facade;
use Paynl\LaravelCashier\LaravelCashier;

/**
 * @see LaravelCashier
 */
class Cashier extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return LaravelCashier::class;
    }
}
