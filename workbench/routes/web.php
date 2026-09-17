<?php

use Illuminate\Support\Facades\Route;
use Paynl\LaravelCashier\Facades\Pay;
use PayNL\Sdk\Exception\PayException;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('dev/pay')->group(function () {
    Route::get('return', function () {
        abort_unless(config('app.debug'), 404);

        return response(
            '<h1>Return URL</h1><p>Customer landed here after payment.</p>',
            200,
            ['Content-Type' => 'text/html; charset=UTF-8'],
        );
    })->name('workbench.pay.return');

    Route::match(['get', 'post'], 'exchange', function () {
        abort_unless(config('app.debug'), 404);

        return response('TRUE', 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    })->name('workbench.pay.exchange');

    Route::get('order', function () {
        abort_unless(config('app.debug'), 404);

        $amount = (float) request('amount', 0.01);
        if ($amount <= 0) {
            return response()->json(['error' => 'Query parameter amount must be greater than zero.'], 422);
        }

        $returnUrl = route('workbench.pay.return');
        $exchangeUrl = route('workbench.pay.exchange');

        try {
            $payOrder = Pay::request(
                Pay::orderCreate($returnUrl, $exchangeUrl)->setAmount($amount)
            );
        } catch (InvalidArgumentException $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        } catch (PayException $exception) {
            return response()->json([
                'error' => $exception->getMessage(),
                'pay_code' => $exception->getPayCode(),
                'friendly_message' => $exception->getFriendlyMessage(),
            ], 422);
        }

        $paymentUrl = $payOrder->getPaymentUrl();

        if (request()->boolean('redirect')) {
            return redirect()->away($paymentUrl);
        }

        return response()->json([
            'order_id' => $payOrder->getOrderId(),
            'payment_url' => $paymentUrl,
            'amount' => $amount,
            'return_url' => $returnUrl,
            'exchange_url' => $exchangeUrl,
            'hint' => 'Open payment_url in the browser, or call this route with ?redirect=1',
        ]);
    })->name('workbench.pay.order');
});
