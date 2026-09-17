<?php

namespace Paynl\LaravelCashier;

use Illuminate\Support\Facades\Route;
use Paynl\LaravelCashier\Commands\CashierInstall;
use Paynl\LaravelCashier\Commands\CashierRun;
use Paynl\LaravelCashier\Http\Controllers\WebhookController;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelCashierServiceProvider extends PackageServiceProvider
{
    public function packageRegistered(): void
    {
        $this->app->singleton(LaravelCashier::class);
        $this->app->singleton(Pay::class);
    }

    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-cashier')
            ->hasConfigFile('cashier')
            ->hasViews()
            ->hasMigration('create_laravel_cashier_table')
            ->hasCommands(CashierInstall::class, CashierRun::class);
    }

    public function packageBooted(): void
    {
        Route::match(['get', 'post'], '/pay/webhook', WebhookController::class)->name('cashier.webhook');
    }
}
