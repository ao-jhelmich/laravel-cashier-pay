<?php

namespace Paynl\LaravelCashier\Facades;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Facade;
use Paynl\LaravelCashier\LaravelCashier;
use Paynl\LaravelCashier\Models\Subscription;
use Paynl\LaravelCashier\PendingSubscription;

/**
 * @method static PendingSubscription newSubscription(Model $owner, string $type, string $plan)
 * @method static Subscription prolongSubscription(Subscription $subscription)
 * @method static void cancelSubscription(Subscription $subscription)
 *
 * @see LaravelCashier
 */
class Cashier extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return LaravelCashier::class;
    }
}
