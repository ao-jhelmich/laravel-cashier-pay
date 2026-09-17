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
    };

    $user->name = 'Ada Lovelace';
    $user->email = 'ada@example.com';

    expect($user->payName())->toBe('Override Name')
        ->and($user->payEmail())->toBe('override@example.com');
});
