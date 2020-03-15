<?php

namespace Vdbelt\FTX;

use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Vdbelt\FTX\Api\Account;
use Vdbelt\FTX\Api\Fills;
use Vdbelt\FTX\Api\FundingPayments;
use Vdbelt\FTX\Api\Futures;
use Vdbelt\FTX\Api\LeveragedTokens;
use Vdbelt\FTX\Api\Markets;
use Vdbelt\FTX\Api\Options;
use Vdbelt\FTX\Api\Orders;
use Vdbelt\FTX\Api\Subaccounts;
use Vdbelt\FTX\Api\Wallet;
use Vdbelt\FTX\Client\HttpClient;

final class FTX
{
    const BASE_URI = 'https://ftx.com/api';
    
    protected HttpClient $client;
    
    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }
    
    public static function create(string $api_key = null, string $api_secret = null) : self 
    {
        $httpClient = new HttpClient(
            Psr18ClientDiscovery::find(),
            Psr17FactoryDiscovery::findRequestFactory(),
            Psr17FactoryDiscovery::findUrlFactory(),
            Psr17FactoryDiscovery::findStreamFactory(),
            self::BASE_URI
        );
        
        if($api_key && $api_secret) {
            $httpClient->authenticate($api_key, $api_secret);   
        }
        
        return new self($httpClient);
    }
    
    public function onSubaccount(string $subaccount) : self
    {
        $this->client->subaccount($subaccount);
        
        return $this;
    }
    
    public function subaccounts()
    {
        return new Subaccounts($this->client);
    }
    
    public function markets() : Markets
    {
        return new Markets($this->client);
    }
    
    public function futures() : Futures
    {
        return new Futures($this->client);
    }
    
    public function account()
    {
        return new Account($this->client);
    }
    
    public function wallet()
    {
        return new Wallet($this->client);
    }
    
    public function orders()
    {
        return new Orders($this->client);
    }
    
    public function fills()
    {
        return new Fills($this->client);
    }
    
    public function fundingPayments()
    {
        return new FundingPayments($this->client);
    }
    
    public function leveragedTokens()
    {
        return new LeveragedTokens($this->client);
    }
    
    public function options()
    {
        return new Options($this->client);
    }
}