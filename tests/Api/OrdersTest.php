<?php

namespace FTX\Tests\Api;

use FTX\Api\Orders;
use FTX\Api\Support\PendingOrder;
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

    public function testCreateOrder()
    {
        $order = $this->orders->create(['size' => 99.9, 'market' => 'BTC-PERP']);
        
        $this->assertInstanceOf(PendingOrder::class, $order);
        $this->assertEquals('BTC-PERP', $order->market);
        $this->assertEquals(99.9, $order->size);
        $this->assertEquals(['market' => 'BTC-PERP', 'size' => 99.9], $order->toArray());
        
        $order->place();

        $responseBody = $this->client->getLastRequest()->getBody();
        $responseBody->rewind();
        $payload = json_decode($responseBody->getContents(), true);
        
        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/orders');
        $this->assertEquals($this->client->getLastRequest()->getMethod(), 'POST');
        $this->assertEquals(['market' => 'BTC-PERP', 'size' => 99.9], $payload);
        
        $order = $this->orders->create()->market(99.9)->sell('BTC-PERP')->immediateOrCancel();

        $this->assertInstanceOf(PendingOrder::class, $order);
        $this->assertEquals('BTC-PERP', $order->market);
        $this->assertEquals(99.9, $order->size);
        $this->assertEquals(
            ['market' => 'BTC-PERP', 'size' => 99.9, 'type' => 'market', 'side' => 'sell', 'ioc' => true], 
            $order->toArray()
        );

        $order = $this->orders->create()->limit(99.9, 2000)->buy('BTC-PERP')->postOnly();

        $this->assertInstanceOf(PendingOrder::class, $order);
        $this->assertEquals('BTC-PERP', $order->market);
        $this->assertEquals(99.9, $order->size);
        $this->assertEquals(
            ['market' => 'BTC-PERP', 'size' => 99.9, 'price' => 2000, 'type' => 'limit', 'side' => 'buy', 'postOnly' => true],
            $order->toArray()
        );

        $order = $this->orders->create()->buy('BTC-PERP')->market(10)->reduceOnly()->withClientId('foo');

        $this->assertInstanceOf(PendingOrder::class, $order);
        $this->assertEquals('BTC-PERP', $order->market);
        $this->assertEquals(10, $order->size);
        $this->assertEquals(
            ['market' => 'BTC-PERP', 'size' => 10, 'type' => 'market', 'side' => 'buy', 'reduceOnly' => true, 'clientId' => 'foo'],
            $order->toArray()
        );
    }
}
