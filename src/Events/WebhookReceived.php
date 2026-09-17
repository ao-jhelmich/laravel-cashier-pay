<?php

declare(strict_types=1);

namespace Paynl\LaravelCashier\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class WebhookReceived
{
    use Dispatchable, SerializesModels;

    public function __construct(public array $payload) {}
}
