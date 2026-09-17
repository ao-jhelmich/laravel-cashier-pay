<?php

declare(strict_types=1);

namespace Paynl\LaravelCashier\Subscription;

use Illuminate\Database\Eloquent\Model;

final class PendingSubscription
{
    public function __construct(
        private readonly SubscriptionManager $subscriptionManager,
        private readonly Model $billable,
        private readonly string $type,
        private readonly string $plan,
    ) {}

    public function create(): Subscription
    {
        return $this->subscriptionManager->create($this->billable, $this->type, $this->plan);
    }
}
