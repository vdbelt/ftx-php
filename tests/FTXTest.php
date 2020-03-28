<?php

namespace FTX\Tests;

use FTX\Api\ConditionalOrders;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use FTX\Api\Account;
use FTX\Api\Fills;
use FTX\Api\FundingPayments;
use FTX\Api\Futures;
use FTX\Api\LeveragedTokens;
use FTX\Api\Markets;
use FTX\Api\Options;
use FTX\Api\Orders;
use FTX\Api\Subaccounts;
use FTX\Api\Wallet;
use FTX\Client\HttpClient;
use FTX\FTX;

class FTXTest extends FTXTestCase
{
    public function testFTXAcceptsHttpClient()
    {
        $http = new HttpClient(
            Psr18ClientDiscovery::find(),
            Psr17FactoryDiscovery::findRequestFactory(),
            Psr17FactoryDiscovery::findUrlFactory(),
            Psr17FactoryDiscovery::findStreamFactory(),
            'https://ftx.com/api'
        );
        
        $ftx = new FTX($http);
        
        $this->assertEquals($ftx->getClient(), $http);
    }
    
    public function testFTXAutomaticallyCreatesHttpClient()
    {
        $ftx = FTX::create();
        
        $this->assertInstanceOf(HttpClient::class, $ftx->getClient());
    }
    
    public function testFTXAcceptsCredentials()
    {
        $ftx = FTX::create('foo', 'bar');
        
        $this->assertEquals('foo', $ftx->getClient()->api_key);
        $this->assertEquals('bar', $ftx->getClient()->api_secret);
    }
    
    public function testIsPossibleToSpecifySubaccount()
    {
        $ftx = FTX::create('foo', 'bar');
        $ftx->onSubaccount('foo');

        $this->assertEquals('foo', $ftx->getClient()->api_key);
        $this->assertEquals('bar', $ftx->getClient()->api_secret);
        $this->assertEquals('foo', $ftx->getClient()->subaccount);
    }
    
    public function testAPIEndpoints()
    {
        $ftx = FTX::create();
        
        $this->assertInstanceOf(Subaccounts::class, $ftx->subaccounts());
        $this->assertInstanceOf(Markets::class, $ftx->markets());
        $this->assertInstanceOf(Futures::class, $ftx->futures());
        $this->assertInstanceOf(Account::class, $ftx->account());
        $this->assertInstanceOf(Wallet::class, $ftx->wallet());
        $this->assertInstanceOf(Orders::class, $ftx->orders());
        $this->assertInstanceOf(ConditionalOrders::class, $ftx->conditionalOrders());
        $this->assertInstanceOf(Fills::class, $ftx->fills());
        $this->assertInstanceOf(FundingPayments::class, $ftx->fundingPayments());
        $this->assertInstanceOf(LeveragedTokens::class, $ftx->leveragedTokens());
        $this->assertInstanceOf(Options::class, $ftx->options());
    }
}
