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

        $this->assertEquals('/api/orders/history', $this->client->getLastRequest()->getUri()->getPath());
        $this->assertEquals('GET', $this->client->getLastRequest()->getMethod());
        $this->assertEquals('', $this->client->getLastRequest()->getUri()->getQuery());

        $start = new \DateTime('2020-02-01');
        $end = new \DateTime('2020-03-01');
        $this->orders->history('BTC-PERP', $start, $end, 50);

        $this->assertEquals('/api/orders/history', $this->client->getLastRequest()->getUri()->getPath());
        $this->assertEquals('GET', $this->client->getLastRequest()->getMethod());

        parse_str($this->client->getLastRequest()->getUri()->getQuery(), $query);

        $this->assertEquals(['market' => 'BTC-PERP', 'start_time' => $start->getTimestamp(), 'end_time' => $end->getTimestamp(), 'limit' => '50'],
            $query
        );

    }

    public function testStatus()
    {
        $this->orders->status(12345678);

        $this->assertEquals('/api/orders/' . 12345678, $this->client->getLastRequest()->getUri()->getPath());
        $this->assertEquals('GET', $this->client->getLastRequest()->getMethod());
    }

    public function testCancel()
    {
        $this->orders->cancel(12345678);

        $this->assertEquals('/api/orders/' . 12345678, $this->client->getLastRequest()->getUri()->getPath());
        $this->assertEquals('DELETE', $this->client->getLastRequest()->getMethod());


        $this->orders->cancelAll();

        $responseBody = $this->client->getLastRequest()->getBody();
        $responseBody->rewind();
        $payload = json_decode($responseBody->getContents(), true);

        $this->assertEquals('/api/orders', $this->client->getLastRequest()->getUri()->getPath());
        $this->assertEquals('DELETE', $this->client->getLastRequest()->getMethod());
        $this->assertEquals(['market' => null, 'conditionalOrdersOnly' => null, 'limitOrdersOnly' => null], $payload);


        $this->orders->cancelAll('BTC-PERP', false, false);

        $responseBody = $this->client->getLastRequest()->getBody();
        $responseBody->rewind();
        $payload = json_decode($responseBody->getContents(), true);

        $this->assertEquals('/api/orders', $this->client->getLastRequest()->getUri()->getPath());
        $this->assertEquals('DELETE', $this->client->getLastRequest()->getMethod());
        $this->assertEquals(['market' => 'BTC-PERP', 'conditionalOrdersOnly' => false, 'limitOrdersOnly' => false], $payload);
    }

    public function testOpen()
    {
        $this->orders->open();

        $this->assertEquals('/api/orders', $this->client->getLastRequest()->getUri()->getPath());
        $this->assertEquals('GET', $this->client->getLastRequest()->getMethod());
        $this->assertEquals('', $this->client->getLastRequest()->getUri()->getQuery());

        $this->orders->open('BTC-PERP');

        parse_str($this->client->getLastRequest()->getUri()->getQuery(), $query);
        $this->assertEquals(['market' => 'BTC-PERP'], $query);
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

        $this->assertEquals('/api/orders', $this->client->getLastRequest()->getUri()->getPath());
        $this->assertEquals('POST', $this->client->getLastRequest()->getMethod());
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
