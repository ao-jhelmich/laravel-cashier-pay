<?php

declare(strict_types=1);

namespace Paynl\LaravelCashier\Subscription;

enum SubscriptionStatus: string
{
    case Active = 'active';
    case Cancelled = 'cancelled';
}
