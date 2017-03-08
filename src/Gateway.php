<?php

namespace aharen\Pay;

use aharen\Pay\Exceptions\InvalidProviderException;

class Gateway
{
    protected $provider;

    public function __construct(string $provider)
    {
        $this->provider = $this->makeProvider($provider);
        return $this;
    }

    protected function makeProvider(string $provider)
    {
        $providerClass = "\aharen\Pay\Providers\\" . $provider . 'Provider';
        if (!class_exists($providerClass)) {
            throw new InvalidProviderException;
        }
        return new $providerClass();
    }

    public function config(array $config)
    {
        $this->provider->make($config);
        return $this;
    }

    public function transaction(float $amount, string $orderId)
    {
        $this->provider->transaction($amount, $orderId);
        return $this;
    }

    public function get()
    {
        return $this->provider->get();
    }

    public function callback($response, $orderId)
    {
        return $this->provider->callback($response, $orderId);
    }
}
