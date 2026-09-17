<?php

namespace Paynl\LaravelCashier\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Paynl\LaravelCashier\LaravelCashier
 */
class LaravelCashier extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Paynl\LaravelCashier\LaravelCashier::class;
    }
}
