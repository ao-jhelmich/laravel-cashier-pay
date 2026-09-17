<?php

namespace Paynl\LaravelCashier;

use Paynl\LaravelCashier\AuthAdapter\Bearer;
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

    public function orderCreate(?string $returnUrl = null, ?string $exchangeUrl = null): OrderCreateRequest
    {
        $request = new OrderCreateRequest;

        $serviceId = config('cashier.service_id');
        if ($serviceId) {
            $request->setServiceId((string) $serviceId);
        }

        $request->setReturnurl($returnUrl ?? (string) config('cashier.return_url', ''));
        $request->setExchangeUrl($exchangeUrl ?? (string) config('cashier.exchange_url', ''));

        return $request;
    }

    protected function makeConfig(): Config
    {
        $settings = config('cashier', []);
        $token = (string) ($settings['token'] ?? '');

        $config = new Config([
            'authentication' => [
                'type' => 'Bearer',
                'username' => '-',
                'password' => $token,
            ],
            'authAdapters' => [
                'aliases' => [
                    'Bearer' => 'bearer',
                ],
                'invokables' => [
                    'bearer' => Bearer::class,
                ],
            ],
        ]);

        if (! empty($settings['core'])) {
            $config->setCore((string) $settings['core']);
        }

        return $config;
    }
}
