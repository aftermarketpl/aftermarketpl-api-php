# AfterMarket.pl API client class for PHP

This library allows you to issue calls to the AfterMarket.pl public API from PHP.

## Quick start

Install the library using `composer`.

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
try {
    $result = $client->send("/domain/check", array(
        "names" => array("check-1.pl", "check-2.pl"),
    ));
    print_r($result);
}
catch(Aftermarketpl\Api\Exception\Exception $exception) {
    print_r($exception->getResponse());
}
```

Use Guzzle-style asynchronous call:

```php
$promise = $client->sendAsync("/domain/check", array(
    "names" => array("check-1.pl", "check-2.pl"),
));
$promise->then(
    function($result) {
        print_r($result);
    },
    function($exception) {
        print_r($exception->$getResponse();
    }
);
$promise->wait();
```

## Installation

You can install the API library in two ways.

1. The easiest way is to use `composer`:

```
composer require aftermarketpl/api
```

2. If you are not using `composer`, you can install the library manually.
In order to do that, download and unpack the files (only the `lib` directory is necessary),
and include the library's autoloader in your PHP files:

```php
require "... path to the lib folder.../autoload.php";
```

In the latter case, you don't need the Guzzle library if you don't plan on using asynchronous requests
- the library will switch to plain CURL in that case.

## API keys

To connect to the API, you need an API key.
[Click here to obtain your API key.](https://www.aftermarket.pl/API/Create/)

You can have more than one API key.
Each API key has a set of operations which can be performed using that key.
You can also limit the key usage to a specific set of IP addresses.

After creating an API key, you can modify its permissions, revoke or disable it
on the Aftermarket.pl website.

## Reference

### Creating the class

To create a new client class, use:

```php
$client = new Aftermarketpl\Api\Client($options);
```

The `$options` variable is an array, which can contain the following values:

* `key` - The public key used to connect to the API.
* `secret` - The secret key used to connect to the API.
* `debug` - If set to `true`, the CURL calls to the API will be performed with the CURL_VERBOSE flag, allowing you to debug connection errors.
* `url` - Alternative URL to use when connecting to the API. You don't normally need to use this option.

The parameters `key` and `secret` are necessary for making calls to the API.
However, you do not need to specify them at class creation time - see the section _Modifying the class_ below.

### Making API calls

```php
$result = $client->send($command, $parameters);
```

The function `send()` is used to send an API command to the server.
You need to specify two parameters:

* `$command` - The name of the command, for example `"/domain/check"`. [Click here to see the list of API functions.](https://json.aftermarket.pl/)
* `$parameters` - Array containing the command parameters. The actual contents depend on the command. Can be omitted of the command does not take any parameters.

The return value is the data structure returned by the API command. 
Its contents also depend on the actual command used - see the API reference for details on what is being returned by each command.

### Asynchronous calls

```php
$promise = $client->sendAsync($command, $parameters);
$promise->then(function($response) { ... }, function($exception) { ... });
$response = $promise->wait();
```

The function `sendAsync()` uses the Guzzle library to send an API call asynchronously.
The input parameters are the same as with the `send()` function, 
but the returned value is a promise which resolves to the data structure returned from the API command.

Refer to Guzzle documentation for information on how to use promises with asynchronous calls.

### Exceptions

If something goes wrong, a subclass of `Aftermarketpl\Api\Exception\Exception` is thrown.
This class extends the standard `Exception` class with the method `getResponse()`,
which returns the contents of the response received from the server.
This value is, of course, only valid when the actual call to the server has been made;
if the error occurred before that, the value is `null`.

### Modifying the class

You can modify the created client class in runtime, using the following functions:

```php
$client->setAuth($key, $secret);`
$client->getAuthKey();
$client->getAuthSecret();
$client->setDebug($debug);
$client->getDebug();
$client->setUrl($url);
$client->getUrl();
```
