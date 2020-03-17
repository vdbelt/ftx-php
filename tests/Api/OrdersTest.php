<?php

namespace FTX\Tests\Api;

use FTX\Api\Orders;
use Http\Mock\Client;

class OrdersTest extends TestCase
{
    protected Orders $orders;
    protected Client $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->orders = new Orders($this->http);
    }

    public function testHistory()
    {
        $this->orders->history();

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/orders/history');
        $this->assertEquals($this->client->getLastRequest()->getMethod(), 'GET');
    }

    public function testOpen()
    {
        $this->orders->open();

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/orders');
        $this->assertEquals($this->client->getLastRequest()->getMethod(), 'GET');
    }
}
