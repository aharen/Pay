<?php

namespace aharen\Pay\Providers;

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
            'Version'                  => '1',
            'SignatureMethod'          => 'SHA1',
        ];
    }

    function unset() {
        return [
            'MerPassword',
        ];
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
        return str_pad(str_replace('.', '', $amount), 12, 0, STR_PAD_LEFT);
    }
}
