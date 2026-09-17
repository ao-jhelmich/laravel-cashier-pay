<?php

namespace Paynl\LaravelCashier;

use Paynl\LaravelCashier\Models\Subscription;

class LaravelCashier
{
    private static $subscriptionModel = Subscription::class;

    public static function subscriptionModel(): string
    {
        return self::$subscriptionModel;
    }
}
