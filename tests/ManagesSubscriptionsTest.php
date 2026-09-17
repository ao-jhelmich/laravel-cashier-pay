<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Paynl\LaravelCashier\Facades\Pay;
use Paynl\LaravelCashier\Models\Subscription;
use Paynl\LaravelCashier\ValueObjects\Checkout;
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

it('starts a checkout and stores a subscription for the plan', function () {
    Pay::shouldReceive('newSubscription')
        ->once()
        ->with('basic')
        ->andReturn(new Checkout(redirectUrl: 'https://pay.test/redirect'));

    $user = billableUser();
    $checkout = $user->newSubscription('basic');

    expect($checkout)->toBeInstanceOf(Checkout::class)
        ->and($checkout->redirectUrl)->toBe('https://pay.test/redirect')
        ->and($user->subscribed('basic'))->toBeTrue();
});
