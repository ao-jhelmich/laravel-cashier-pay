<?php

namespace Paynl\LaravelCashier;

use Illuminate\Database\Eloquent\Model;
use Paynl\LaravelCashier\Models\Subscription;

class LaravelCashier
{
    /** @var class-string<Subscription> */
    public static string $subscriptionModel = Subscription::class;

    public static function useSubscriptionModel(string $model): void
    {
        static::$subscriptionModel = $model;
    }

    public function newSubscription(Model $owner, string $type, string $plan): PendingSubscription
    {
        return new PendingSubscription($owner, $type, $plan);
    }

    public function prolongSubscription(Subscription $subscription): Subscription
    {
        return $subscription;
    }

    public function cancelSubscription(Subscription $subscription): void {}
    private static $subscriptionModel = Subscription::class;

    public static function subscriptionModel(): string
    {
        return self::$subscriptionModel;
    }
}
