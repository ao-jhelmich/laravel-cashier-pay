<?php

namespace Paynl\LaravelCashier;

use Illuminate\Support\Facades\Route;
use Paynl\LaravelCashier\Commands\LaravelCashierCommand;
use Paynl\LaravelCashier\Http\Controllers\WebhookController;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelCashierServiceProvider extends PackageServiceProvider
{
    public function packageRegistered(): void
    {
        $this->app->singleton(Pay::class);
        $this->app->singleton(Subscription\SubscriptionManager::class);
    }

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-cashier')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel_cashier_table')
            ->hasCommand(LaravelCashierCommand::class);
    }

    public function packageBooted(): void
    {
        Route::post('/webhook', WebhookController::class)->name('cashier.webhook');
    }
}
