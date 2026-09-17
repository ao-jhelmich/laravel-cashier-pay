<?php

declare(strict_types=1);

namespace Paynl\LaravelCashier\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Paynl\LaravelCashier\Events\WebhookHandled;
use Paynl\LaravelCashier\Events\WebhookReceived;

class WebhookController
{
    public function __invoke(Request $request): JsonResponse
    {
        $payload = json_decode($request->getContent(), true);

        if (! is_array($payload)) {
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

    protected function handleOrderStatusChanged(array $payload): JsonResponse
    {
        return $this->successMethod();
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
