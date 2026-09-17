<?php

use Illuminate\Database\Eloquent\Model;
use Paynl\LaravelCashier\Facades\Cashier;
use Paynl\LaravelCashier\LaravelCashier;
use Paynl\LaravelCashier\Models\Subscription;
use Paynl\LaravelCashier\PendingSubscription;

it('resolves the cashier facade root', function () {
    expect(Cashier::getFacadeRoot())->toBeInstanceOf(LaravelCashier::class);
});

it('starts a new subscription via the cashier facade', function () {
    $billable = new class extends Model
    {
        protected $table = 'users';

        public $incrementing = false;

        protected $keyType = 'string';

        protected $guarded = [];
    };

    $billable->forceFill(['id' => 'user-1']);

    $pending = Cashier::newSubscription($billable, 'default', 'premium');

    expect($pending)->toBeInstanceOf(PendingSubscription::class);

    $subscription = $pending->create();

    expect($subscription)
        ->toBeInstanceOf(Subscription::class)
        ->name->toBe('default')
        ->price->toBe('premium')
        ->owner_id->toBe('user-1');
});

it('prolongs a subscription via the cashier facade', function () {
    $subscription = new Subscription([
        'name' => 'default',
        'price' => 'premium',
    ]);

    expect(Cashier::prolongSubscription($subscription))->toBe($subscription);
});

it('cancels a subscription via the cashier facade', function () {
    $subscription = new Subscription([
        'name' => 'default',
        'price' => 'premium',
    ]);

    Cashier::cancelSubscription($subscription);

    expect(true)->toBeTrue();
});
