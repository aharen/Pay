<?php

namespace aharen\Pay\Providers;

class MPGProvider extends AbstractProvider
{
    protected function configRules()
    {
        return [
            'Host'                     => true,
            'PurchaseCurrency'         => false,
            'PurchaseCurrencyExponent' => false,
            'Version'                  => false,
            'SignatureMethod'          => false,
            'AcqID'                    => true,
            'MerID'                    => true,
            'PurchaseAmt'              => true,
            'OrderID'                  => true,
        ];
    }
}
