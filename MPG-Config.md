### Config For MPG

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
Version | 1.1 | no
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
$pay = $gateway->get();

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

### Callback

The callback method verifies the response signature from the gateway. If missmatched will throw a **SignatureMissmatchException**

It accepts 2 parameters:

**response** *(array)* `$_POST` response from the gateway

**orderId** *(string)* The unique order id that you made the request with

Example:

```
$config = [
    'Host'        => 'banking-gateway-uri',
    'MerRespURL'  => 'callback-uri',
    'AcqID'       => 'aquirer-id',
    'MerID'       => 'merchant-id',
    'MerPassword' => 'merchant-password',
];

$response = $_POST;

Or if you're using laravel you can pass the 'Request' array like:

$response = Request::all();

$gateway = new Gateway('MPG');
$pay = $gateway->config($config)
    ->callback($response, 'ORDER-01');
```
