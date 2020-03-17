<?php

namespace FTX\Tests\Api;

use FTX\Api\Subaccounts;
use Http\Mock\Client;

class SubaccountsTest extends TestCase
{
    protected Subaccounts $subaccounts;
    protected Client $client;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->subaccounts = new Subaccounts($this->http);
    }

    public function testDelete()
    {
        $this->subaccounts->delete('foo');

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/subaccounts');
        $this->assertEquals($this->client->getLastRequest()->getMethod(), 'DELETE');
    }

    public function testBalances()
    {
        $this->subaccounts->balances('foo');

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/subaccounts/foo/balances');
        $this->assertEquals($this->client->getLastRequest()->getMethod(), 'GET');
    }

    public function testChangeName()
    {
        $this->subaccounts->changeName('foo', 'bar');

        $responseBody = $this->client->getLastRequest()->getBody();
        $responseBody->rewind();
        $payload = json_decode($responseBody->getContents(), true);

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/subaccounts/update_name');
        $this->assertEquals($this->client->getLastRequest()->getMethod(), 'POST');
        $this->assertEquals(['nickname' => 'foo', 'newNickname' => 'bar'], $payload);
    }

    public function testCreate()
    {
        $this->subaccounts->create('foo');

        $responseBody = $this->client->getLastRequest()->getBody();
        $responseBody->rewind();
        $payload = json_decode($responseBody->getContents(), true);

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/subaccounts');
        $this->assertEquals($this->client->getLastRequest()->getMethod(), 'POST');
        $this->assertEquals(['nickname' => 'foo'], $payload);
    }

    public function testTransfer()
    {
        $this->subaccounts->transfer('foo', 99.9, 'source','destination');

        $responseBody = $this->client->getLastRequest()->getBody();
        $responseBody->rewind();
        $payload = json_decode($responseBody->getContents(), true);

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/subaccounts/transfer');
        $this->assertEquals($this->client->getLastRequest()->getMethod(), 'POST');
        $this->assertEquals(['coin' => 'foo', 'size' => 99.9, 'source' => 'source', 'destination' => 'destination'], $payload);
    }

    public function testAll()
    {
        $this->subaccounts->all();
        
        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/subaccounts/');
    }
}
