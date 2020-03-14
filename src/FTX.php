<?php

namespace Vdbelt\FTX;

use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Vdbelt\FTX\Api\Futures;
use Vdbelt\FTX\Api\Markets;
use Vdbelt\FTX\Api\Options;
use Vdbelt\FTX\Client\HttpClient;

final class FTX
{
    const BASE_URI = 'https://ftx.com/api';
    
    protected HttpClient $client;
    
    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }
    
    public static function create(
        string $api_key = null, 
        string $api_secret = null, 
        string $subaccount = null,
        ?ClientInterface $httpClient = null,
        ?RequestFactoryInterface $requestFactory = null,
        ?UriFactoryInterface $uriFactory = null
    ) : self {
        $httpClient = $httpClient ?: Psr18ClientDiscovery::find();
        $requestFactory = $requestFactory ?: Psr17FactoryDiscovery::findRequestFactory();
        $uriFactory = $uriFactory ?: Psr17FactoryDiscovery::findUrlFactory();
        
        $httpClient = new HttpClient(
            $httpClient, 
            $requestFactory,
            $uriFactory,
            self::BASE_URI,
            $api_key,
            $api_secret,
            $subaccount
        );
        
        return new self($httpClient);
    }
    
    public function subaccounts()
    {
        
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
        
    }
    
    public function wallet()
    {
        
    }
    
    public function orders()
    {
        
    }
    
    public function fills()
    {
        
    }
    
    public function fundingPayments()
    {
        
    }
    
    public function leveragedTokens()
    {
        
    }
    
    public function options()
    {
        return new Options($this->client);
    }
}