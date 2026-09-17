<?php

use Workbench\App\Models\User;

it('returns the model name and email', function () {
    $user = new User([
        'name' => 'Ada Lovelace',
        'email' => 'ada@example.com',
    ]);

    expect($user->payName())->toBe('Ada Lovelace')
        ->and($user->payEmail())->toBe('ada@example.com');
});

it('returns null when name or email is missing', function () {
    $user = new User;

    expect($user->payName())->toBeNull()
        ->and($user->payEmail())->toBeNull();
});

it('keeps an empty name or email as an empty string', function () {
    $user = new User([
        'name' => '',
        'email' => '',
    ]);

    expect($user->payName())->toBe('')
        ->and($user->payEmail())->toBe('');
});

it('lets a billable model override the defaults', function () {
    $user = new class extends User
    {
        public function payName(): ?string
        {
            return 'Override Name';
        }

        public function payEmail(): ?string
        {
            return 'override@example.com';
        }

        public function payPhone(): ?string
        {
            return '+31000000000';
        }

        public function payReference(): ?string
        {
            return 'customref';
        }
    };

    $user->id = 1;
    $user->name = 'Ada Lovelace';
    $user->email = 'ada@example.com';
    $user->phone = '+31612345678';

    expect($user->payName())->toBe('Override Name')
        ->and($user->payEmail())->toBe('override@example.com')
        ->and($user->payPhone())->toBe('+31000000000')
        ->and($user->payReference())->toBe('customref');
});

it('returns the model phone', function () {
    $user = new User;
    $user->phone = '+31612345678';

    expect($user->payPhone())->toBe('+31612345678');
});

it('returns null when phone is missing', function () {
    $user = new User;

    expect($user->payPhone())->toBeNull();
});

it('keeps an empty phone as an empty string', function () {
    $user = new User;
    $user->phone = '';

    expect($user->payPhone())->toBe('');
});

it('builds a pay reference from the class basename and id', function () {
    $user = new User;
    $user->id = 42;

    expect($user->payReference())->toBe('user42');
});

it('returns null when the model has no id', function () {
    $user = new User;

    expect($user->payReference())->toBeNull();
});
