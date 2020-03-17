<?php

require('vendor/autoload.php');

use FTX\FTX;

// Unauthenticated
$ftx = FTX::create();

// Authenticated
$ftx = FTX::create('key', 'secret');

// Authenticated with subaccount
$ftx = $ftx->onSubaccount('sub1');
// or simply
//$ftx = FTX::create('key', 'secret')->onSubaccount('sub1');



$markets = $ftx->markets()->trades('BTC-0327', 2, new DateTime('2019-12-01'), new DateTime('2019-12-10'));
//$markets = $ftx->onSubaccount('main')->orders()->history();

var_dump($markets);