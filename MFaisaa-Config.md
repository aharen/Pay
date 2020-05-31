### Config For MFaisaa

```
$gateway->config([
    'MerID'        => 'Mobile Money number, prefixed with 960',
    'MerRespURL'  => 'Response URL',
    'MerPin'       => 'Merchant Pin',
    'MerKey' => 'Merchant identification',
]);
```

Below are all the available config options:

Option | Default | Required
--- | --- | ---
Host | https://www.ooredoo.mv/webapps/MMOnlinePayment/public/MobileMoney/verify | no
MerRespURL | null | yes
MerPin | null | yes
MerID | null | yes
MerKey | null | yes

### Transaction

Transaction requires 2 parameters:

- **amount** - Amount is required to be a float
- **transaction id** - Your order id, do note that it is required that this be unique on every request (even if re-trying a cancelled/rejected payment request)

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
    'MerID'        => 'Mobile Money number, prefixed with 960',
    'MerRespURL'  => 'Response URL',
    'MerPin'       => 'Merchant Pin',
    'MerKey' => 'Merchant identification',
];

$gateway = new Gateway('MFaisaa');
$pay = $gateway->config($config)
    ->transaction(110.55, 'ORDER-01')
    ->get();
```

### Callback

The callback method verifies the response signature from the gateway. If missmatched will throw a **SignatureMissmatchException**

It accepts 2 parameters:

**response** *(array)* `$_POST` response from the gateway

**transactionId** *(string)* The unique order id that you made the request with

Example:

```
$config = [
    'MerID'         => 'Mobile Money number, prefixed with 960',
    'MerRespURL'    => 'Response URL',
    'MerPin'        => 'Merchant Pin',
    'MerKey'        => 'Merchant identification',
];

$response = $_POST;

Or if you're using laravel you can pass the 'Request' array like:

$response = Request::all();

$gateway = new Gateway('MFaisaa');
$pay = $gateway->config($config)
    ->callback($response, 'ORDER-01');
```
