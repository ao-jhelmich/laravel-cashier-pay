<?php

declare(strict_types=1);

namespace Paynl\LaravelCashier\Subscription;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

final class SubscriptionManager
{
    public function create(Model $billable, string $type, string $plan): Subscription
    {
        return new Subscription(
            id: (string) Str::uuid(),
            billableType: $billable->getMorphClass(),
            billableId: $billable->getKey(),
            type: $type,
            plan: $plan,
        );
    }

    public function prolong(Subscription $subscription): Subscription
    {
        return $subscription;
    }

    public function cancel(Subscription $subscription): void {}
}
