## Do not use: WIP
# PHP client for FTX

This package aims to implement the FTX.com REST API endpoints.

## Installation
You can install the package via composer:
```bash
composer require vdbelt/ftx-php
```

This library is not hard coupled to Guzzle or any other HTTP library. It follows PSR-18 client abstraction. You'll need to install your own preferred client.

### Basic usage
```php
use Vdbelt\FTX\FTX;

// Unauthenticated
$ftx = FTX::create();

// Authenticated
$ftx = FTX::create('key', 'secret');

$markets = $ftx->markets()->all();
$btcPerp = $ftx->markets()->get('BTC-PERP');
```
