<?php

namespace Workbench\App\Console;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('cashier:install')]
#[Description('Install cashier Pay')]
class CashierInstall extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (app()->environment('production')) {
            $this->alert('Running in production mode.');

            if (! $this->confirm('Proceed installing Cashier?')) {
                return;
            }
        }

        // $this->comment('Publishing Cashier migrations...');
        // $this->callSilent('vendor:publish', ['--tag' => 'cashier-migrations']);

        $this->comment('Publishing Cashier configuration files...');
        $this->callSilent('vendor:publish', ['--tag' => 'cashier-configs']);

        // if ($this->option('template')) {
        //     $this->callSilent('vendor:publish', ['--tag' => 'cashier-views']);
        // } else {
        //     $this->info(
        //         'You can publish the Cashier invoice template so you can modify it. '
        //         .'Note that this will exclude your template copy from updates by the package maintainers.'
        //     );

        //     if ($this->confirm('Publish Cashier invoice template?')) {
        //         $this->comment('Publishing Cashier invoice template...');
        //         $this->callSilent('vendor:publish', ['--tag' => 'cashier-views']);
        //     }
        // }

        $this->info('Cashier was installed successfully.');
    }
}
