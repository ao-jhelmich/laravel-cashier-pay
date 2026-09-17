<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Paynl\LaravelCashier\Models\Subscription;
use Paynl\LaravelCashier\Subscription\PendingSubscription;
use Paynl\LaravelCashier\Subscription\Subscription as DomainSubscription;
use Paynl\LaravelCashier\Subscription\SubscriptionStatus;
use Workbench\App\Models\User;

beforeEach(function () {
    Schema::dropIfExists('subscriptions');
    Schema::dropIfExists('users');

    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->string('password');
        $table->timestamps();
    });

    Schema::create('subscriptions', function (Blueprint $table) {
        $table->id();
        $table->morphs('owner');
        $table->string('name');
        $table->string('price')->nullable();
        $table->timestamps();
    });
});

function billableUser(string $email = 'ada@example.com'): User
{
    return User::query()->create([
        'name' => 'Ada',
        'email' => $email,
        'password' => 'password',
    ]);
}

it('exposes subscriptions as a morph many owned by the billable', function () {
    $relation = billableUser()->subscriptions();

    expect($relation)->toBeInstanceOf(MorphMany::class)
        ->and($relation->getRelated())->toBeInstanceOf(Subscription::class)
        ->and($relation->getForeignKeyName())->toBe('owner_id')
        ->and($relation->getMorphType())->toBe('owner_type');
});

it('is not subscribed when no subscription exists for the plan', function () {
    expect(billableUser()->subscribed('premium'))->toBeFalse();
});

it('is subscribed only when a subscription with that plan name exists', function () {
    $user = billableUser();

    $user->subscriptions()->create([
        'name' => 'premium',
        'price' => '1000',
    ]);

    expect($user->subscribed('premium'))->toBeTrue()
        ->and($user->subscribed('basic'))->toBeFalse();
});

it('does not treat another billable subscription as its own', function () {
    $owner = billableUser('ada@example.com');
    $other = billableUser('other@example.com');

    $other->subscriptions()->create([
        'name' => 'premium',
        'price' => '1000',
    ]);

    expect($owner->subscribed('premium'))->toBeFalse();
});

it('starts a pending default-type subscription for the plan', function () {
    $user = billableUser();

    $pending = $user->newSubscription('premium');

    expect($pending)->toBeInstanceOf(PendingSubscription::class);

    $subscription = $pending->create();

    expect($subscription)
        ->toBeInstanceOf(DomainSubscription::class)
        ->status->toBe(SubscriptionStatus::Active)
        ->type->toBe('default')
        ->plan->toBe('premium')
        ->billableId->toBe($user->getKey())
        ->billableType->toBe($user->getMorphClass());
});
