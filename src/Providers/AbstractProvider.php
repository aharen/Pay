<?php

namespace aharen\Pay\Providers;

use aharen\Pay\Exceptions\ConfigurationException;
use aharen\Pay\Interfaces\ProviderInterface;

abstract class AbstractProvider implements ProviderInterface
{
    protected $config;

    protected $response;

    abstract protected function rules();

    abstract protected function defaults();

    abstract protected function makeSignature();

    abstract protected function makePurchaseAmt(float $amount);

    abstract protected function verifySignature();

    public function make(array $config)
    {
        $this->validateConfig($config);
        $this->config = $config;
    }

    public function transaction(float $amount, string $orderId)
    {
        $this->config['PurchaseAmt'] = $this->makePurchaseAmt($amount);
        $this->config['OrderID']     = $orderId;
    }

    public function get()
    {
        $this->mergeDefaults();
        $this->config['Signature'] = $this->makeSignature();

        foreach ($this->remove() as $value) {
            if (isset($this->config[$value])) {
                unset($this->config[$value]);
            }
        }

        return $this->config;
    }

    protected function getExponent()
    {
        if (isset($this->config['PurchaseCurrencyExponent'])) {
            return $this->config['PurchaseCurrencyExponent'];
        }
    }

    protected function mergeDefaults()
    {
        foreach ($this->defaults() as $key => $value) {
            if (!isset($this->config[$key])) {
                $this->config[$key] = $value;
            }
        }
    }

    protected function validateConfig(array $config)
    {
        $requiredConfigKeys = [];
        foreach ($this->rules() as $key => $value) {
            if ($value === true) {
                $requiredConfigKeys[] = $key;
            }
        }
        $userConfigKeys = array_keys($config);
        $configDiff     = array_diff($requiredConfigKeys, $userConfigKeys);

        if (count($configDiff) > 0) {
            throw new ConfigurationException("Missing required configuration parameters: " . implode(', ', $configDiff));
        }
    }
}
