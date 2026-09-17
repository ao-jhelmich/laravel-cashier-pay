<?php

namespace Paynl\LaravelCashier;

use Paynl\LaravelCashier\AuthAdapter\Bearer;
use Paynl\LaravelCashier\ValueObjects\Checkout;
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

        $authentication = $this->authenticationSettings($settings);

        $configData = [
            'authentication' => $authentication,
        ];

        if ($authentication['type'] === 'Bearer') {
            $configData['authAdapters'] = [
                'aliases' => [
                    'Bearer' => 'bearer',
                ],
                'invokables' => [
                    'bearer' => Bearer::class,
                ],
            ];
        }

        $config = new Config($configData);

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
        $token = (string) ($settings['token'] ?? '');
        if ($token === '') {
            throw new \InvalidArgumentException('Pay API token is not configured (cashier.token / PAYNL_TOKEN).');
        }

        $scheme = strtolower((string) ($settings['auth_scheme'] ?? 'bearer'));

        if ($scheme === 'basic') {
            return $this->basicAuthenticationSettings($settings, $token);
        }

        if ($scheme !== 'bearer') {
            throw new \InvalidArgumentException('Invalid cashier.auth_scheme. Use "bearer" or "basic".');
        }

        return [
            'type' => 'Bearer',
            'username' => '-',
            'password' => $token,
        ];
    }

    /**
     * @param  array<string, mixed>  $settings
     * @return array{type: string, username: string, password: string}
     */
    protected function basicAuthenticationSettings(array $settings, string $token): array
    {
        $tokenCode = (string) ($settings['api_token_code'] ?? '');
        if ($tokenCode !== '') {
            return [
                'type' => 'Basic',
                'username' => $tokenCode,
                'password' => $token,
            ];
        }

        $serviceId = (string) ($settings['service_id'] ?? '');
        if ($serviceId === '') {
            throw new \InvalidArgumentException(
                'Basic auth requires PAYNL_API_TOKEN_CODE (AT-code) or PAYNL_SERVICE_ID with PAYNL_TOKEN as service secret.',
            );
        }

        return [
            'type' => 'Basic',
            'username' => $serviceId,
            'password' => $token,
        ];
    }

    public function newSubscription(string $plan)
    {
        $plan = config('cashier.plans.'.$plan);

        if (! $plan) {
            throw new \Exception('Plan "'.$plan.'" not found in config/cashier.php');
        }

        $serviceId = config('cashier.service_id');

        if (blank($serviceId)) {
            throw new \InvalidArgumentException('Pay service ID is not configured (cashier.service_id).');
        }

        $request = (new OrderCreateRequest)->setServiceId((string) $serviceId)
            ->setReturnurl(config('cashier.return_url'))
            ->setExchangeUrl(config('cashier.exchange_url'))
            ->setAmount($plan['price'])
            ->setDescription($plan['name'])
            ->setCurrency('EUR');

        $payOrder = $this->request($request);

        return new Checkout(
            redirectUrl: (string) $payOrder->getPaymentUrl(),
        );
    }
}
