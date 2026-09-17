<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Model;
use Paynl\LaravelCashier\Facades\Pay;
use Paynl\LaravelCashier\Pay as PayManager;
use Paynl\LaravelCashier\Subscription\PendingSubscription;
use Paynl\LaravelCashier\Subscription\Subscription;
use Paynl\LaravelCashier\Subscription\SubscriptionStatus;

it('resolves the pay facade root', function () {
    expect(Pay::getFacadeRoot())->toBeInstanceOf(PayManager::class);
});

it('starts a new subscription via the facade', function () {
    $billable = new class extends Model
    {
        protected $table = 'users';

        public $incrementing = false;

        protected $keyType = 'string';

        protected $guarded = [];
    };

    $billable->forceFill(['id' => 'user-1']);

    $pending = Pay::newSubscription($billable, 'default', 'premium');

    expect($pending)->toBeInstanceOf(PendingSubscription::class);

    $subscription = $pending->create();

    expect($subscription)
        ->toBeInstanceOf(Subscription::class)
        ->status->toBe(SubscriptionStatus::Active)
        ->type->toBe('default')
        ->plan->toBe('premium')
        ->billableId->toBe('user-1');
});

it('prolongs a subscription via the facade', function () {
    $subscription = new Subscription(
        id: 'sub-1',
        billableType: 'user',
        billableId: 1,
        type: 'default',
        plan: 'premium',
    );

    expect(Pay::prolongSubscription($subscription))->toBe($subscription);
});

it('cancels a subscription via the facade', function () {
    $subscription = new Subscription(
        id: 'sub-1',
        billableType: 'user',
        billableId: 1,
        type: 'default',
        plan: 'premium',
    );

    Pay::cancelSubscription($subscription);

    expect(true)->toBeTrue();
});
