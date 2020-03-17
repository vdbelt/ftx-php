<?php

namespace FTX;

use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
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
    
    public function getClient()
    {
        return $this->client;
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