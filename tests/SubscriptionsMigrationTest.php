<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Paynl\LaravelCashier\Models\Subscription;
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
});

function runCreateSubscriptionsTableMigration(): void
{
    $migration = include __DIR__.'/../database/migrations/2026_09_17_132021_create_subscriptions_table.php';
    $migration->up();
}

it('creates the subscriptions table with plan and owner columns', function () {
    runCreateSubscriptionsTableMigration();

    expect(Schema::hasTable('subscriptions'))->toBeTrue()
        ->and(Schema::hasColumns('subscriptions', [
            'id',
            'plan',
            'owner_type',
            'owner_id',
            'created_at',
            'updated_at',
        ]))->toBeTrue();
});

it('saves a new subscription in the subscriptions table', function () {
    runCreateSubscriptionsTableMigration();

    $user = User::query()->create([
        'name' => 'Ada',
        'email' => 'ada@example.com',
        'password' => 'password',
    ]);

    $subscription = $user->subscriptions()->create([
        'plan' => 'premium',
    ]);

    $this->assertDatabaseHas('subscriptions', [
        'id' => $subscription->getKey(),
        'plan' => 'premium',
        'owner_id' => $user->getKey(),
        'owner_type' => $user->getMorphClass(),
    ]);

    expect($subscription->exists)->toBeTrue()
        ->and($subscription)->toBeInstanceOf(Subscription::class);
});
