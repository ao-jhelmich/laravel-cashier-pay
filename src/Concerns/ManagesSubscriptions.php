<?php

namespace Paynl\LaravelCashier\Concerns;

use Paynl\LaravelCashier\Facades\Cashier;

trait ManagesSubscriptions
{
    public function subscriptions(): MorphMany
    {
        return $this->morphMany(Cashier::$subscriptionModel, 'owner');
    }

    public function subscribed(string $plan): bool {}

    public function newSubscription(string $plan): Checkout {}
}
