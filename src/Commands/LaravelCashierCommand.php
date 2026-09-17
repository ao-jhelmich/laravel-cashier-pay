<?php

namespace Paynl\LaravelCashier\Commands;

use Illuminate\Console\Command;

class LaravelCashierCommand extends Command
{
    public $signature = 'laravel-cashier';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
