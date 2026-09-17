<?php

declare(strict_types=1);

namespace Paynl\LaravelCashier;

use Illuminate\Database\Eloquent\Model;
use Paynl\LaravelCashier\Subscription\PendingSubscription;
use Paynl\LaravelCashier\Subscription\Subscription;
use Paynl\LaravelCashier\Subscription\SubscriptionManager;

final readonly class Pay
{
    public function __construct(
        private SubscriptionManager $subscriptionManager,
    ) {}

    public function newSubscription(Model $billable, string $type, string $plan): PendingSubscription
    {
        return new PendingSubscription($this->subscriptionManager, $billable, $type, $plan);
    }

    public function prolongSubscription(Subscription $subscription): Subscription
    {
        return $this->subscriptionManager->prolong($subscription);
    }

    public function cancelSubscription(Subscription $subscription): void
    {
        $this->subscriptionManager->cancel($subscription);
    }
}
