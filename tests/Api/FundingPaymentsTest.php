<?php

namespace FTX\Tests\Api;

use FTX\Api\FundingPayments;
use Http\Mock\Client;

class FundingPaymentsTest extends TestCase
{
    protected FundingPayments $fundingPayments;
    protected Client $client;
    
    protected function setUp(): void
    {
        parent::setUp();

        $this->fundingPayments = new FundingPayments($this->http);
    }

    public function testAll()
    {
        $this->fundingPayments->all();

        $this->assertEquals('/api/funding_payments', $this->client->getLastRequest()->getUri()->getPath());
        $this->assertEquals('GET', $this->client->getLastRequest()->getMethod());
        $this->assertEquals('', $this->client->getLastRequest()->getUri()->getQuery());


        $start = new \DateTime('2020-02-01');
        $end = new \DateTime('2020-03-01');
        $this->fundingPayments->all(null, $start, $end);

        $this->assertEquals('/api/funding_payments', $this->client->getLastRequest()->getUri()->getPath());
        $this->assertEquals('GET', $this->client->getLastRequest()->getMethod());

        parse_str($this->client->getLastRequest()->getUri()->getQuery(), $query);

        $this->assertEquals(['start_time' => $start->getTimestamp(), 'end_time' => $end->getTimestamp()],
            $query
        );


        $this->fundingPayments->all('BTC-PERP');

        $this->assertEquals('/api/funding_payments', $this->client->getLastRequest()->getUri()->getPath());
        $this->assertEquals('GET', $this->client->getLastRequest()->getMethod());

        parse_str($this->client->getLastRequest()->getUri()->getQuery(), $query);

        $this->assertEquals(['future' => 'BTC-PERP'], $query);
    }
}
