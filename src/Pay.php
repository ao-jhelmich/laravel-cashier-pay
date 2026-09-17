<?php

namespace Paynl\LaravelCashier;

use PayNL\Sdk\Config\Config;
use PayNL\Sdk\Model\Request\OrderCreateRequest;
use PayNL\Sdk\Request\RequestData;

class Pay
{
    protected ?Config $config = null;

    public function config(): Config
    {
        if ($this->config === null) {
            $this->config = $this->makeConfig();
        }

        return $this->config;
    }

    public function setConfig(Config $config): self
    {
        $this->config = $config;

        return $this;
    }

    public function request(RequestData $request): mixed
    {
        return $request->setConfig($this->config())->start();
    }

    public function orderCreate(string $returnUrl, string $exchangeUrl): OrderCreateRequest
    {
        $request = new OrderCreateRequest;

        $serviceId = config('cashier.service_id');
        if (blank($serviceId)) {
            throw new \InvalidArgumentException('Pay service ID is not configured (cashier.service_id).');
        }

        $request->setServiceId((string) $serviceId);

        $request->setReturnurl($returnUrl);
        $request->setExchangeUrl($exchangeUrl);

        return $request;
    }

    protected function makeConfig(): Config
    {
        $settings = config('cashier', []);

        $config = new Config([
            'authentication' => $this->authenticationSettings($settings),
        ]);

        if (! empty($settings['core'])) {
            $config->setCore((string) $settings['core']);
        }

        return $config;
    }

    /**
     * @param  array<string, mixed>  $settings
     * @return array{type: string, username: string, password: string}
     */
    protected function authenticationSettings(array $settings): array
    {
        $password = (string) ($settings['token'] ?? '');
        if ($password === '') {
            throw new \InvalidArgumentException('Pay API token is not configured (cashier.token / PAYNL_TOKEN).');
        }

        $tokenCode = (string) ($settings['api_token_code'] ?? '');
        if ($tokenCode !== '') {
            return [
                'type' => 'Basic',
                'username' => $tokenCode,
                'password' => $password,
            ];
        }

        $serviceId = (string) ($settings['service_id'] ?? '');
        if ($serviceId === '') {
            throw new \InvalidArgumentException(
                'Pay API authentication is not configured. Set PAYNL_API_TOKEN_CODE (AT-code) or PAYNL_SERVICE_ID with PAYNL_TOKEN as service secret.',
            );
        }

        return [
            'type' => 'Basic',
            'username' => $serviceId,
            'password' => $password,
        ];
    }
}
