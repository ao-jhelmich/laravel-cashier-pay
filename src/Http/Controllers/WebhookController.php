<?php

declare(strict_types=1);

namespace Paynl\LaravelCashier\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Paynl\LaravelCashier\Events\WebhookHandled;
use Paynl\LaravelCashier\Events\WebhookReceived;
use Paynl\LaravelCashier\Facades\Cashier;
use Paynl\LaravelCashier\Models\Subscription;

class WebhookController
{
    public function __invoke(Request $request): JsonResponse
    {
        $payload = $this->payload($request);

        if ($payload === []) {
            return $this->successMethod();
        }

        $type = $payload['type'] ?? '';
        $event = $payload['event'] ?? '';
        $method = 'handle'.Str::studly($type.'_'.$event);

        WebhookReceived::dispatch($payload);

        if (method_exists($this, $method)) {
            $response = $this->{$method}($payload);

            WebhookHandled::dispatch($payload);

            return $response;
        }

        return $this->missingMethod();
    }

    /**
     * @return array<string, mixed>
     */
    protected function payload(Request $request): array
    {
        $decoded = json_decode($request->getContent(), true);

        if (is_array($decoded) && $decoded !== []) {
            return $decoded;
        }

        return $request->all();
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    protected function handleOrderStatusChanged(array $payload): JsonResponse
    {
        if ($this->isPaid($payload)) {
            $subscription = $this->subscriptionFromPayload($payload);

            if ($subscription !== null) {
                Cashier::prolongSubscription($subscription);
            }
        }

        return $this->successMethod();
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    protected function isPaid(array $payload): bool
    {
        $action = data_get($payload, 'object.status.action');
        $code = data_get($payload, 'object.status.code');

        return $action === 'PAID' || (int) $code === 100;
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    protected function subscriptionFromPayload(array $payload): ?Subscription
    {
        $email = data_get($payload, 'object.payments.0.customerId');
        $model = config('auth.providers.users.model');

        if (! is_string($email) || $email === '' || ! is_string($model) || ! class_exists($model)) {
            return null;
        }

        $billable = $model::query()->where('email', $email)->first();

        if ($billable === null || ! method_exists($billable, 'subscriptions')) {
            return null;
        }

        return $billable->subscriptions()->latest('id')->first();
    }

    protected function successMethod(): JsonResponse
    {
        return response()->json(['result' => true]);
    }

    protected function missingMethod(): JsonResponse
    {
        return $this->successMethod();
    }
}
