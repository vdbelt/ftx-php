# PHP client for FTX
[![GitHub Workflow Status](https://img.shields.io/github/workflow/status/vdbelt/ftx-php/PHP%20Composer)](https://github.com/vdbelt/ftx-php/actions)
[![Codecov](https://img.shields.io/codecov/c/github/vdbelt/ftx-php)](https://codecov.io/gh/vdbelt/ftx-php)
[![Packagist](https://img.shields.io/packagist/dt/vdbelt/ftx-php)](https://packagist.org/packages/vdbelt/ftx-php)
[![GitHub](https://img.shields.io/github/license/vdbelt/ftx-php)](https://github.com/vdbelt/ftx-php/blob/master/LICENSE.md)

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
$ftx->orders()->open();
$ftx->orders()->open('BTC-PERP');

// history
$ftx->orders()->history();
$ftx->orders()->history('BTC-PERP')

// Placing orders
// You can either pass the properties of your order directly:
$ftx->orders()->create(['market' => 'BTC-PERP', 'type' => 'market', 'size' => 1])->place();

// or use the fluent api to build up an order:
$ftx->orders()->create()->buy('BTC-PERP')->limit(1, 4000)->place();

// order status
$ftx->orders()->status(123456);

// cancel order
$ftx->orders()->cancel(123456);

// cancel all orders, including conditional orders
$ftx->orders()->cancelAll();
$ftx->orders()->cancelAll('BTC-PERP', $conditionalOrdersOnly = false, $limitOrdersOnly = true)

```

### Conditional Orders
```php
$ftx->conditionalOrders()->open();
$ftx->conditionalOrders()->open('BTC-PERP', 'take_profit');

//history
$ftx->conditionalOrders()->history();
$ftx->conditionalOrders()->history('BTC-PERP', null, null, 'buy', 'stop', 'market', 10);

// Placing orders
// You can either pass the properties of your order directly:
$ftx->conditionalOrders()->create(['market' => 'BTC-PERP', 'type' => 'takeProfit', 'triggerPrice' => 7000.99, 'size' => 1, 'side' => 'buy', 'reduceOnly' => true])->place();

// or use the fluent api to build up an order:
$ftx->conditionalOrders()->create()->stop($size = 1, $triggerPrice = 7000.99)->buy('BTC-PERP')->reduceOnly()->place();

// order status
$ftx->conditionalOrders()->status(123456);

// cancel order
$ftx->conditionalOrders()->cancel(123456);

// cancel all orders conditional orders
$ftx->conditionalOrders()->cancelAll();
$ftx->conditionalOrders()->cancelAll('BTC-PERP', $conditionalOrdersOnly = true, $limitOrdersOnly = true)
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