<?php

namespace aharen\Pay\Providers;

use aharen\Pay\Exceptions\SignatureMissmatchException;

class MFaisaaProvider extends AbstractProvider
{
    protected function rules()
    {
        return [
            'MerID'                    => true,
            'MerRespURL'               => true,
            'MerPin'                   => true,
            'Host'                     => false,
            'TxnId'                    => false,
            'PayAmt'                   => false,
            'PurchaseCurrencyExponent' => false,
            'Version'                  => false,
            'SignatureMethod'          => false,
        ];
    }

    protected function defaults()
    {
        return [
            'Host'                      => 'https://www.ooredoo.mv/webapps/MMOnlinePayment/public/MobileMoney/verify',
            // 'PurchaseCurrency'         => '462',
            'PurchaseCurrencyExponent' => '2',
            // 'Version'                  => '1.1',
            'SignatureMethod'          => 'SHA1',
        ];
    }

    protected function remove()
    {
        return [
            'MerPassword',
            'MerPin',
            'PurchaseCurrencyExponent'
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
        // dd($this->config);

        if ($response) {
            $signatureValue = $this->response['MerchantTxnID'].
            $this->response['transactionID'] .
            $this->config['MerKey'] .
            $this->response['status'];

            return sha1($signatureValue);
        }

        $signatureValue = $this->config['MerID'] .
        $this->config['MerPin'] .
        $this->config['MerKey'] .
        $this->config['TxnId'] .
        $this->config['PayAmt'];

        return sha1($signatureValue);
    }

    protected function makePurchaseAmt(float $amount)
    {
        $decimalAmount = number_format($amount, $this->getExponent(), '.', '');
        return str_pad(str_replace('.', '', $decimalAmount), 7, 0, STR_PAD_LEFT);
    }

    protected function verifySignature()
    {
        $this->mergeDefaults();
        if ($this->makeSignature(true) !== $this->response['hash']) {
            throw new SignatureMissmatchException();
        }
    }

    public function transaction(float $amount, string $txnId)
    {
        $this->config['PayAmt'] = $this->makePurchaseAmt($amount);
        $this->config['TxnId']     = $txnId;
        // dd($this->config);

    }

    public function callback(array $response, string $txnId)
    {
        $this->response = $response;
        if ((int) $this->response['status'] === 1001) {
            $this->config['TxnId'] = $txnId;
            $this->verifySignature($response);
        }

        return $this->response;
    }
}
