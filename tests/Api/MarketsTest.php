<?php

namespace FTX\Tests\Api;

use FTX\Api\Markets;
use Http\Mock\Client;

class MarketsTest extends TestCase
{
    protected Markets $markets;
    protected Client $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->markets = new Markets($this->http);
    }
    
    public function testAll()
    {
        $this->markets->all();

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/markets');
    }

    public function testGet()
    {
        $this->markets->get('BTC-PERP');

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/markets/BTC-PERP');
    }

    public function testOrderbook()
    {
        $this->markets->orderbook('BTC-PERP', 100);

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/markets/BTC-PERP/orderbook');
        $this->assertEquals($this->client->getLastRequest()->getUri()->getQuery(), 'depth=100');
    }
    
    public function testCandles()
    {
        $this->markets->candles('BTC-PERP', 15);

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/markets/BTC-PERP/candles');
        $this->assertEquals($this->client->getLastRequest()->getUri()->getQuery(), 'resolution=15');
    }

    public function testTrades()
    {
        $this->markets->trades('BTC-PERP', 100);

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/markets/BTC-PERP/trades');
        $this->assertEquals($this->client->getLastRequest()->getUri()->getQuery(), 'limit=100');
        
        $start = new \DateTime('2020-02-01');
        $this->markets->trades('BTC-PERP', null, $start);
        $this->assertEquals($this->client->getLastRequest()->getUri()->getQuery(), 'start_time='.$start->getTimestamp());

        $start = new \DateTime('2020-02-01');
        $end = new \DateTime('2020-03-01');
        $this->markets->trades('BTC-PERP', null, $start, $end);
        $this->assertEquals($this->client->getLastRequest()->getUri()->getQuery(), 'start_time='.$start->getTimestamp().'&end_time='.$end->getTimestamp());
    }

    public function testDateTimeImmutable()
    {
        $start = new \DateTimeImmutable('2020-02-01');
        $this->markets->trades('BTC-PERP', null, $start);
        $this->assertEquals($this->client->getLastRequest()->getUri()->getQuery(), 'start_time='.$start->getTimestamp());
    }
}
