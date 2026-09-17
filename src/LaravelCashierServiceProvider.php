<?php

namespace Paynl\LaravelCashier;

use Paynl\LaravelCashier\Commands\LaravelCashierCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelCashierServiceProvider extends PackageServiceProvider
{
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
}
