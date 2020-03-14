<?php

require('vendor/autoload.php');

$ftx = \Vdbelt\FTX\FTX::create();


//$markets = $ftx->markets()->trades('BTC-0327', 2, new DateTime('2019-12-01'), new DateTime('2019-12-10'));
$markets = $ftx->options()->trades(100);

var_dump($markets);