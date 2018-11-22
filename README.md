# AfterMarket.pl API client class for PHP

This library allows to issue calls to the AfterMarket.pl public API from PHP.

## Quick start

Install the library using composer:

```
composer require aftermarketpl/api
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
## Installation

You can install the API library on two ways.

1. The easiest way is to use `composer`:

```
composer require aftermarketpl/api
```

2. If you are not using `composer`, you can install the library manually. 
In order to do that, download and unpack the files (only the `lib` directory is necessary),
and include the library's autoloader in your PHP files:

```php
require "path/to/the/src/folder/autoload.php";
```

