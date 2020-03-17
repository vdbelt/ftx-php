<?php

namespace FTX\Tests\Api;

use FTX\Api\Account;
use Http\Mock\Client;

class AccountTest extends TestCase
{
    protected Account $account;
    protected Client $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->account = new Account($this->http);
    }

    public function testGet()
    {
        $this->account->get();

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/account');
        $this->assertEquals($this->client->getLastRequest()->getMethod(), 'GET');
    }

    public function testChangeAccountLeverage()
    {
        $this->account->changeAccountLeverage(5);
        
        $responseBody = $this->client->getLastRequest()->getBody();
        $responseBody->rewind();
        $payload = json_decode($responseBody->getContents(), true);

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/account/leverage');
        $this->assertEquals($this->client->getLastRequest()->getMethod(), 'POST');
        $this->assertEquals($payload, ['leverage' => 5]);
    }

    public function testPositions()
    {
        $this->account->positions();

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/positions');
        $this->assertEquals($this->client->getLastRequest()->getMethod(), 'GET');
    }
}
