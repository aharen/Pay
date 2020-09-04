# Pay

Process payments with Maldives Banking Gateways. Currently Supports Maldives Payment Gateway (MPG) by Bank of Maldives, Maldives Islamic Bank and MFaisaa by Ooredoo Maldives.

**NOTE:** If you are looking for a WooCommerce (WordPress) implementation please take a look at [https://github.com/ashhama/woocommerce-bml-mpos-integration](https://github.com/ashhama/woocommerce-bml-mpos-integration)

## Installation

```
composer require aharen/pay
``` 

or update your `composer.json` as follows and run `composer update`

```
require: {
    "aharen/pay": "1.2.*"
}
```

## Usage

Initialize with the Gateway provider that you need, options are:

- **MPG** (Maldives Payment Gateway by Bank of Maldives) 
- **MIB** (Maldives Islamic Bank)
- **MFaisaa** (Ooredoo MFaisaa)

**ATTENTION:** For MIB Gateway version 1 use v1.0.* of the package. From v1.1.* of the package it is for MIB Gateway version 2

```
use aharen\Pay\Gateway;

// this initiates MPG
$gatway = new Gateway('MPG');

// this initiates MIB
$gatway = new Gateway('MIB');

// this initiates MFaisaa
$gatway = new Gateway('MFaisaa');
```

If an invalid provider is set an `InvalidProviderException` will be thrown.

### Config

[Maldives Payment Gateway by Bank of Maldives (MPG),  Usage](MPG-Config.md)\
[Maldives Islamic Bank (MIB),  Usage](MIB-Config.md)\
[Ooredoo MFaisaa (MFaisaa),  Usage](MFaisaa-Config.md)

### CREDITS

- MIB v2 updated by [hammaadhrasheedh](https://github.com/hammaadhrasheedh)
- MFaisaa added by [hammaadhrasheedh](https://github.com/hammaadhrasheedh)

### TODO

- tests
