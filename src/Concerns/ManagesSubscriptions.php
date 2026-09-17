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
        return $this->morphMany(Cashier::subscriptionModel(), 'owner');
    }

    public function subscribed(string $plan): bool
    {
        return $this->subscriptions()->where('name', $plan)->exists();
    }

    public function newSubscription(string $plan): Checkout
    {
        $planConfig = config('cashier.plans.'.$plan);

        if (! $planConfig) {
            throw new \Exception('Plan "'.$plan.'" not found in config/cashier.php');
        }

        $checkout = Pay::newSubscription($plan);

        $this->subscriptions()->create([
            'plan' => $plan,
        ]);

        return $checkout;
    }
}
