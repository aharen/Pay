<?php

namespace aharen\Pay\Providers;

use aharen\Pay\Exceptions\SignatureMissmatchException;

class MIBProvider extends AbstractProvider
{
    protected function rules()
    {
        return [
            'host' => true,
            'merRespURL' => true,
            'purchaseCurrency' => false,
            'purchaseCurrencyExponent' => false,
            'version' => false,
            'signatureMethod' => false,
            'acqID' => true,
            'merID' => true,
            'merPassword' => true,
        ];
    }

    protected function defaults()
    {
        return [
            'purchaseCurrency' => '462',
            'purchaseCurrencyExponent' => '2',
            'version' => '2',
            'signatureMethod' => 'SHA1',
        ];
    }

    protected function remove()
    {
        return [
            'merPassword',
        ];
    }

    protected function getExponent()
    {
        if (!isset($this->config['purchaseCurrencyExponent'])) {
            return $this->defaults()['purchaseCurrencyExponent'];
        }
        return parent::getExponent();
    }

    protected function makeSignature($response = false)
    {
        $requestType = '0'; //For Purchase request, this value is always 0

        $signatureValue = $this->config['merPassword'] .
        $this->config['merID'] .
        $this->config['acqID'] .
        $this->config['orderID'];

        if (!$response) {
            $signatureValue .= $this->config['purchaseAmt'] .
            $this->config['purchaseCurrency'] .
            $this->getExponent();
        }

        if ($response) {
            $signatureValue = $requestType . $signatureValue . $this->response['salt'];
        }

        if ($this->config['signatureMethod'] === 'SHA1') {
            return $this->makeSHA1Signature($signatureValue);
        }

        return $this->makeMD5Signature($signatureValue);
    }

    protected function makeMD5Signature($signatureValue)
    {
        return base64_encode(hash('md5', $signatureValue, true));
    }

    protected function makeSHA1Signature($signatureValue)
    {
        return base64_encode(hash('sha1', $signatureValue, true));
    }

    protected function makePurchaseAmt(float $amount)
    {
        $decimalAmount = number_format($amount, $this->getExponent(), '.', '');
        return str_replace('.', '', $decimalAmount);
    }

    protected function verifySignature()
    {
        $this->mergeDefaults();
        if ($this->makeSignature(true) !== $this->response['signature']) {
            throw new SignatureMissmatchException();
        }
    }

    public function callback(array $response, string $orderId)
    {
        $this->response = $response;
        if ((int) $this->response['responseCode'] === 1) {
            $this->config['orderID'] = $orderId;
            $this->verifySignature($response);
        }

        // capitalize the first letter of each param for consistency
        foreach ($this->response as $key => $value) {
            $this->response[ucfirst($key)] = $value;
        }

        return $this->response;
    }

    public function transaction(float $amount, string $orderId)
    {
        $this->config['purchaseAmt'] = $this->makePurchaseAmt($amount);
        $this->config['orderID'] = $orderId;
    }

    public function get()
    {
        $this->mergeDefaults();
        $this->config['signature'] = $this->makeSignature();

        foreach ($this->remove() as $value) {
            if (isset($this->config[$value])) {
                unset($this->config[$value]);
            }
        }

        return $this->config;
    }
}
