<?php

namespace Paynl\LaravelCashier\Concerns;

use Paynl\LaravelCashier\Cashier;

trait ManagesSubscriptions
{
    public function subscriptions()
    {
        return $this->morphMany(Cashier::$subscriptionModel, 'owner');
    }
}
