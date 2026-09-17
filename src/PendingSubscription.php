<?php

namespace Paynl\LaravelCashier;

use Illuminate\Database\Eloquent\Model;
use Paynl\LaravelCashier\Models\Subscription;

class PendingSubscription
{
    public function __construct(
        protected Model $owner,
        protected string $type,
        protected string $plan,
    ) {}

    public function create(): Subscription
    {
        $subscription = new (LaravelCashier::$subscriptionModel);

        $subscription->forceFill([
            'name' => $this->type,
            'price' => $this->plan,
            'owner_type' => $this->owner->getMorphClass(),
            'owner_id' => $this->owner->getKey(),
        ]);

        return $subscription;
    }
}
