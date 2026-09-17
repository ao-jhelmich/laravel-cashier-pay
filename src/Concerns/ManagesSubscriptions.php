<?php

namespace Paynl\LaravelCashier\Concerns;

use Paynl\LaravelCashier\LaravelCashier;

trait ManagesSubscriptions
{
    public function subscriptions()
    {
        return $this->morphMany(LaravelCashier::$subscriptionModel, 'owner');
    }
}
