<?php

namespace Paynl\LaravelCashier\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Paynl\LaravelCashier\Facades\Cashier;
use Paynl\LaravelCashier\Facades\Pay;
use Paynl\LaravelCashier\ValueObjects\Checkout;

trait ManagesSubscriptions
{
    public function subscriptions(): MorphMany
    {
        return $this->morphMany(Cashier::$subscriptionModel, 'owner');
    }

    public function subscribed(string $plan): bool
    {
        return $this->subscriptions()->where('name', $plan)->exists();
    }

    public function newSubscription(string $plan): Checkout
    {
        return Pay::newSubscription($this, $plan);
    }
}
