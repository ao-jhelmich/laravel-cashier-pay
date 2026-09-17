<?php

declare(strict_types=1);

namespace Paynl\LaravelCashier\Facades;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Facade;
use Paynl\LaravelCashier\Pay as PayManager;
use Paynl\LaravelCashier\Subscription\PendingSubscription;
use Paynl\LaravelCashier\Subscription\Subscription;

/**
 * @method static PendingSubscription newSubscription(Model $billable, string $type, string $plan)
 * @method static Subscription prolongSubscription(Subscription $subscription)
 * @method static void cancelSubscription(Subscription $subscription)
 *
 * @see PayManager
 */
class Pay extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return PayManager::class;
    }
}
