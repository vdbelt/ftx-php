<?php

namespace FTX\Tests\Api;

use FTX\Api\Futures;
use Http\Mock\Client;

class FuturesTest extends TestCase
{
    protected Futures $futures;
    protected Client $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->futures = new Futures($this->http);
    }

    public function testStats()
    {
        $this->futures->stats('BTC-PERP');

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/futures/BTC-PERP/stats');
    }

    public function testAll()
    {
        $this->futures->all();

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/futures');
    }

    public function testGet()
    {
        $this->futures->get('BTC-PERP');

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/futures/BTC-PERP');
    }

    public function testFundingRates()
    {
        $this->futures->fundingRates();

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/funding_rates');
        
        $this->futures->fundingRates('BTC-PERP');

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/funding_rates');
        $this->assertEquals($this->client->getLastRequest()->getUri()->getQuery(), 'future=BTC-PERP');
    }
}
