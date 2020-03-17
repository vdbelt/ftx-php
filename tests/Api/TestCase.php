<?php


namespace FTX\Tests\Api;


use FTX\Client\HttpClient;
use FTX\Tests\FTXTestCase;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Http\Mock\Client;

abstract class TestCase extends FTXTestCase
{
    protected HttpClient $http;
    protected Client $client;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->client = new Client();
        
        $this->http = new HttpClient(
            $this->client,
            Psr17FactoryDiscovery::findRequestFactory(),
            Psr17FactoryDiscovery::findUrlFactory(),
            Psr17FactoryDiscovery::findStreamFactory(),
            'https://ftx.com/api'
        );
    }
}