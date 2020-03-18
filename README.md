## Please note: WIP!
# PHP client for FTX
![GitHub Workflow Status](https://img.shields.io/github/workflow/status/vdbelt/ftx-php/PHP%20Composer)
![Codecov](https://img.shields.io/codecov/c/github/vdbelt/ftx-php)
![Packagist](https://img.shields.io/packagist/dt/vdbelt/ftx-php)
![GitHub](https://img.shields.io/github/license/vdbelt/ftx-php)

This package aims to implement the FTX.com REST API endpoints.

## Installation
You can install the package via composer:
```bash
composer require vdbelt/ftx-php
```

This library is not hard coupled to Guzzle or any other HTTP library. It follows PSR-18 client abstraction. You'll need to install your own preferred client.

If you want to get started quickly:
```bash
composer require vdbelt/ftx-php php-http/curl-client nyholm/psr7
```

## Basic usage
```php
use FTX\FTX;

// Unauthenticated
$ftx = FTX::create();

// Authenticated
$ftx = FTX::create('key', 'secret');

$markets = $ftx->markets()->all();
$btcPerp = $ftx->markets()->get('BTC-PERP');
```

If you want to perform an action on a certain subaccount, you can do so:
```php
$orders = $ftx->onSubaccount('foo')->orders()->open();
```

### Placing orders
You can either pass the properties of your order directly:
```php
$ftx->orders()->create(['market' => 'BTC-PERP', 'type' => 'market', 'size' => 1])->place();
```

or use the fluent api to build up an order:
```php
$ftx->orders()->create()->buy('BTC-PERP')->limit(1, 4000)->place();
```