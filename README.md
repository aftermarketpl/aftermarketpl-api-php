# AfterMarket.pl API client class for PHP

This library allows to issue calls to the AfterMarket.pl public API from PHP.

## Quick start

Install the library using composer:

```
composer install aftermarketpl/api
```

Create the API client object, providing your API key.
[Click here to obtain your API key.](https://www.aftermarket.pl/API/Create/)

```php
$client = new Aftermarketpl\Api\Client(array(
    "key" => "... your public key ...",
    "secret" => "... your secret key ...",
));
```

Call an API function and obtain a result.
[Click here to see the list of API functions.](https://json.aftermarket.pl/)

```php
try
{
    $ret = $client->send("/domain/check", array(
        "names" => array("check-1.pl", "check-2.pl"),
    ));
    print_r($ret);
}
catch(Aftermarketpl\Api\Exception\Exception $e)
{
    print_r($e->getResponse());
}
```
