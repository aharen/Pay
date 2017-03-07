# Pay

Process payments with Maldives Banking Gateways. Currently Supports Maldives Payment Gateway (MPG) by Bank of Maldives and Maldives Islamic Bank.

## Installation

```
composer require aharen/pay
``` 

or update your `composer.json` as follows and run `composer update`

```
require: {
    "aharen/pay": "1.0.*"
}
```

## Usage

Initialize with the Gateway provider that you need, options are:

- **MPG** (Maldives Payment Gateway by Bank of Maldives) 
- **MIB** (Maldives Islamic Bank)

```
use aharen\Pay\Gateway;

// this initiates MPG
$gatway = new Gateway('MPG');

// this initiates MIB
$gatway = new Gateway('MIB');
```

If an invalid provider is set an `InvalidProviderException` will be thrown.

### Config

Now you have to set the gateway config. 

```
$gateway->config([
    'Host'        => 'banking-gateway-uri',
    'MerRespURL'  => 'callback-uri',
    'AcqID'       => 'aquirer-id',
    'MerID'       => 'merchant-id',
    'MerPassword' => 'merchant-password',
]);
```

Below are all the available config options:

Option | Default | Required
--- | --- | ---
Host | null | yes
MerRespURL | null | yes
AcqID | null | yes
MerID | null | yes
MerPassword | null | yes
PurchaseCurrency | 462 | no
PurchaseCurrencyExponent | 2 | If you have the gateway setup to a value different from the default, make sure that this is set correctly.
Version | MIB: 1, MPG: 1.1 | no
SignatureMethod | SHA1 | no
SignatureMethod | SHA1 | no

### Transaction

Transaction requires 2 parameters:

- **amount** - Amount is required to be a float
- **order id** - Your order id, do note that both the gateways require that this be unique on every request (even if re-trying a cancelled/rejected payment request)

```
$gateway->transaction(110.55, 'ORDER-01');
```

### Get Values Array

```
$pay = $gatewau->get();

var_dump($pay);
```

You can also chain all the methods together, example:

```
$config = [
    'Host'        => 'banking-gateway-uri',
    'MerRespURL'  => 'callback-uri',
    'AcqID'       => 'aquirer-id',
    'MerID'       => 'merchant-id',
    'MerPassword' => 'merchant-password',
];

$gateway = new Gateway('MPG');
$pay = $gateway->config($config)
    ->transaction(110.55, 'ORDER-01')
    ->get();
```

### TODO

- tests
