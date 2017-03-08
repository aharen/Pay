<?php

namespace aharen\Pay\Providers;

use aharen\Pay\Exceptions\SignatureMissmatchException;

class MPGProvider extends AbstractProvider
{
    protected function rules()
    {
        return [
            'Host'                     => true,
            'MerRespURL'               => true,
            'PurchaseCurrency'         => false,
            'PurchaseCurrencyExponent' => false,
            'Version'                  => false,
            'SignatureMethod'          => false,
            'AcqID'                    => true,
            'MerID'                    => true,
            'MerPassword'              => true,
        ];
    }

    protected function defaults()
    {
        return [
            'PurchaseCurrency'         => '462',
            'PurchaseCurrencyExponent' => '2',
            'Version'                  => '1.1',
            'SignatureMethod'          => 'SHA1',
        ];
    }

    protected function remove()
    {
        return [
            'MerPassword',
        ];
    }

    protected function getExponent()
    {
        if (!isset($this->config['PurchaseCurrencyExponent'])) {
            return $this->defaults()['PurchaseCurrencyExponent'];
        }
        return parent::getExponent();
    }

    protected function makeSignature($response = false)
    {
        $signatureValue = $this->config['MerPassword'] .
        $this->config['MerID'] .
        $this->config['AcqID'] .
        $this->config['OrderID'];

        if (!$response) {
            $signatureValue .= $this->config['PurchaseAmt'] .
            $this->config['PurchaseCurrency'];
        } else {
            $signatureValue .= $this->response['ResponseCode'] .
            $this->response['ReasonCode'];
        }

        return base64_encode(sha1($signatureValue, true));
    }

    protected function makePurchaseAmt(float $amount)
    {
        $decimalAmount = number_format($amount, $this->getExponent(), '.', '');
        return str_pad(str_replace('.', '', $decimalAmount), 12, 0, STR_PAD_LEFT);
    }

    protected function verifySignature()
    {
        $this->mergeDefaults();
        if (!$this->makeSignature(true) === $this->response['Signature']) {
            throw new SignatureMissmatchException();
        }
    }

    public function callback(array $response, string $orderId)
    {
        $this->response = $response;
        if ((int) $this->response['ResponseCode'] === 1) {
            $this->config['OrderID'] = $orderId;
            $this->verifySignature($response);
        }

        return $this->response;
    }
}
