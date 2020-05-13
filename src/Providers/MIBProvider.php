<?php

namespace aharen\Pay\Providers;

use aharen\Pay\Exceptions\SignatureMissmatchException;

class MIBProvider extends AbstractProvider
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
            'Version'                  => '2',
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
        $requestType = '0'; //For Purchase request, this value is always 0

        $signatureValue = $this->config['MerPassword'] .
        $this->config['MerID'] .
        $this->config['AcqID'] .
        $this->config['OrderID'];

        if (!$response) {
            $signatureValue .= $this->config['PurchaseAmt'] .
            $this->config['PurchaseCurrency'].
            $this->getExponent();
        }

        if($response){
            $signatureValue = $requestType.$signatureValue.$this->response['salt'];
        }

        if ($this->config['SignatureMethod'] === 'SHA1') {
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
        // return str_pad(str_replace('.', '', $decimalAmount), 12, 0, STR_PAD_LEFT);
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
            $this->config['OrderID'] = $orderId;
            $this->verifySignature($response);
        }

        return $this->response;
    }
}
