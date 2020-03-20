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

### Subaccounts
```php
$ftx->subaccounts()->all()
$ftx->subaccounts()->create('nickname')
$ftx->subaccounts()->rename('old', 'new')
$ftx->subaccounts()->delete('nickname')
$ftx->subaccounts()->balances('nickname') // Get balances for a specific subaccount
$ftx->subaccounts()->transfer('BTC', 1, 'main', 'nickname') // Transfer funds between subaccounts
```

### Markets
```php
$ftx->markets()->all()
$ftx->markets()->get('BTC-PERP')
$ftx->markets()->orderbook('BTC-PERP', 100)
$ftx->markets()->trades('BTC-PERP', 100, new \DateTime('2020-03-01'), new \DateTime('2020-03-01 06:00:00'))
$ftx->markets()->candles('BTC-PERP', 15, 100)
```

### Futures
```php
$ftx->futures()->all()
$ftx->futures()->get('BTC-0626')
$ftx->futures()->stats('BTC-0626')
$ftx->futures()->fundingRates()
```

### Account
```php
$ftx->account()->get()
$ftx->account()->positions()
$ftx->account()->changeAccountLeverage(101)
```

### Wallet
```php
$ftx->wallet()->coins()
$ftx->wallet()->balances()
$ftx->wallet()->allBalances() // Balances accross all subaccounts
$ftx->wallet()->depositAddress('BTC')
$ftx->wallet()->deposits()
$ftx->wallet()->withdrawals()
$ftx->wallet()->createWithdrawalRequest('BTC', 1, 'address')->withdraw()
$ftx->wallet()->createWithdrawalRequest('BTC', 1, 'address')->withPassword()->withCode()->withTag()->withdraw()
```

### Orders
```php
$ftx->orders()->open()
$ftx->orders()->history()

// Placing orders
// You can either pass the properties of your order directly:
$ftx->orders()->create(['market' => 'BTC-PERP', 'type' => 'market', 'size' => 1])->place();

// or use the fluent api to build up an order:
$ftx->orders()->create()->buy('BTC-PERP')->limit(1, 4000)->place();
```

### Fills
```php
$ftx->fills()->all()
```

### Funding Payments
```php
$ftx->fundingPayments()->all()
```

### Leveraged Tokens
```php
$ftx->leveragedTokens()->all()
$ftx->leveragedTokens()->info('foo')
$ftx->leveragedTokens()->balances()
$ftx->leveragedTokens()->creationRequests()
$ftx->leveragedTokens()->redemptions()
$ftx->leveragedTokens()->requestCreation('foo', 10)
$ftx->leveragedTokens()->requestRedemption('foo', 10)
```

### Options
```php
$ftx->options()->requests()
$ftx->options()->myRequests()
$ftx->options()->cancelRequest('id')
$ftx->options()->quotesForRequest('id')
$ftx->options()->createQuote('id', 100)
$ftx->options()->myQuotes()
$ftx->options()->cancelQuote('id')
$ftx->options()->acceptQuote('id')
$ftx->options()->accountInfo()
$ftx->options()->positions()
$ftx->options()->trades()
$ftx->options()->fills()
```