<?php

declare(strict_types=1);

namespace Paynl\LaravelCashier\Subscription;

use DateTimeInterface;

final readonly class Subscription
{
    public function __construct(
        public string $id,
        public string $billableType,
        public int|string $billableId,
        public string $type,
        public string $plan,
        public SubscriptionStatus $status = SubscriptionStatus::Active,
        public ?DateTimeInterface $endsAt = null,
    ) {}

    public function withStatus(SubscriptionStatus $status): self
    {
        return new self(
            id: $this->id,
            billableType: $this->billableType,
            billableId: $this->billableId,
            type: $this->type,
            plan: $this->plan,
            status: $status,
            endsAt: $this->endsAt,
        );
    }

    public function withEndsAt(?DateTimeInterface $endsAt): self
    {
        return new self(
            id: $this->id,
            billableType: $this->billableType,
            billableId: $this->billableId,
            type: $this->type,
            plan: $this->plan,
            status: $this->status,
            endsAt: $endsAt,
        );
    }
}
