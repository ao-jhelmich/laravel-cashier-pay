<?php

namespace Paynl\LaravelCashier\ValueObjects;

use Illuminate\Http\RedirectResponse;

class Checkout
{
    public function __construct(
        public string $redirectUrl,
    ) {}

    public function redirectUrl(): RedirectResponse
    {
        return redirect()->away($this->redirectUrl);
    }
}
