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

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/funding_payments');
        $this->assertEquals($this->client->getLastRequest()->getMethod(), 'GET');
    }
}
