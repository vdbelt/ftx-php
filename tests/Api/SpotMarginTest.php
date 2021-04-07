<?php

namespace FTX\Tests\Api;

use FTX\Api\SpotMargin;
use Http\Mock\Client;

class SpotMarginTest extends TestCase
{
    protected SpotMargin $spotMargin;
    protected Client $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->spotMargin = new SpotMargin($this->http);
    }

    public function testBorrowRates()
    {
        $this->spotMargin->borrowRates();

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/spot_margin/borrow_rates');
        $this->assertEquals($this->client->getLastRequest()->getMethod(), 'GET');
    }

    public function testLendingRates()
    {
        $this->spotMargin->lendingRates();

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/spot_margin/lending_rates');
        $this->assertEquals($this->client->getLastRequest()->getMethod(), 'GET');
    }

    public function testBorrowSummary()
    {
        $this->spotMargin->borrowSummary();

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/spot_margin/borrow_summary');
        $this->assertEquals($this->client->getLastRequest()->getMethod(), 'GET');
    }

    public function testMarketInfo()
    {
        $this->spotMargin->marketInfo('foo');

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/spot_margin/market_info');
        $this->assertEquals($this->client->getLastRequest()->getMethod(), 'GET');
    }

    public function testBorrowHistory()
    {
        $this->spotMargin->borrowHistory();

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/spot_margin/borrow_history');
        $this->assertEquals($this->client->getLastRequest()->getMethod(), 'GET');
    }

    public function testLendingHistory()
    {
        $this->spotMargin->lendingHistory();

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/spot_margin/lending_history');
        $this->assertEquals($this->client->getLastRequest()->getMethod(), 'GET');
    }

    public function testOffers()
    {
        $this->spotMargin->offers();

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/spot_margin/offers');
        $this->assertEquals($this->client->getLastRequest()->getMethod(), 'GET');
    }

    public function testSubmitLendingOffer()
    {
        $this->spotMargin->submitLendingOffer('USD', 10., 1e-6);

        $responseBody = $this->client->getLastRequest()->getBody();
        $responseBody->rewind();
        $payload = json_decode($responseBody->getContents(), true);

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/spot_margin/offers');
        $this->assertEquals($this->client->getLastRequest()->getMethod(), 'POST');
        $this->assertEquals($payload, ['coin' => 'USD', 'size' => 10., 'rate' => 1e-6]);
    }

    public function testLendingInfo()
    {
        $this->spotMargin->lendingInfo();

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/spot_margin/lending_info');
        $this->assertEquals($this->client->getLastRequest()->getMethod(), 'GET');
    }
}
