<?php

namespace FTX\Tests\Client;

use FTX\Client\HttpClient;
use FTX\Tests\FTXTestCase;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Mock\Client;

class HttpClientTest extends FTXTestCase
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

    public function testSubaccountHeaderIsAdded()
    {
        $this->http->subaccount = 'foo';
        
        $this->http->get('foo');

        $this->assertEquals($this->client->getLastRequest()->getHeaderLine('FTX-SUBACCOUNT'), 'foo');
    }

    public function testCredentialsHeadersAreAdded()
    {
        $this->http->api_key = 'foo';
        $this->http->api_secret = 'bar';

        $this->http->get('foo');
        
        $time = time()*1000;
        
        $signature = hash_hmac('sha256', $time.'GET/api/foo', 'bar');

        $this->assertEquals($this->client->getLastRequest()->getHeaderLine('FTX-KEY'), 'foo');
        $this->assertEquals($this->client->getLastRequest()->getHeaderLine('FTX-TS'), $time);
        $this->assertEquals($this->client->getLastRequest()->getHeaderLine('FTX-SIGN'), $signature);
    }
}
