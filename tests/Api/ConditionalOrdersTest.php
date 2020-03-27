<?php

namespace FTX\Tests\Api;

use FTX\Api\ConditionalOrders;
use FTX\Api\Support\PendingConditionalOrder;
use Http\Mock\Client;

class ConditionalOrdersTest extends TestCase
{
    protected ConditionalOrders $orders;
    protected Client $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->orders = new ConditionalOrders($this->http);
    }

    public function testHistory()
    {
        $this->orders->history();

        $this->assertEquals('/api/conditional_orders/history', $this->client->getLastRequest()->getUri()->getPath());
        $this->assertEquals('GET', $this->client->getLastRequest()->getMethod());
        $this->assertEquals('', $this->client->getLastRequest()->getUri()->getQuery());

        $start = new \DateTime('2020-02-01');
        $end = new \DateTime('2020-03-01');
        $this->orders->history('BTC-PERP', $start, $end, PendingConditionalOrder::SIDE_BUY, 'trailing_stop', 'limit', 50);

        parse_str($this->client->getLastRequest()->getUri()->getQuery(), $query);
        $this->assertEquals(['market' => 'BTC-PERP', 'start_time' => $start->getTimestamp(), 'end_time' => $end->getTimestamp(), 'side' => PendingConditionalOrder::SIDE_BUY, 'type' => 'trailing_stop', 'orderType' => 'limit', 'limit' => '50'], $query);
    }

    public function testOpen()
    {
        $this->orders->open();

        $this->assertEquals('/api/conditional_orders', $this->client->getLastRequest()->getUri()->getPath());
        $this->assertEquals('GET', $this->client->getLastRequest()->getMethod());
        $this->assertEquals('', $this->client->getLastRequest()->getUri()->getQuery());

        $this->orders->open('BTC_PERP', 'take_profit');

        parse_str($this->client->getLastRequest()->getUri()->getQuery(), $query);
        $this->assertEquals(['market' => 'BTC_PERP', 'type' => 'take_profit'], $query);
    }

    public function testStatus()
    {
        $this->orders->status(12345678);

        $this->assertEquals('/api/conditional_orders/' . 12345678, $this->client->getLastRequest()->getUri()->getPath());
        $this->assertEquals('GET', $this->client->getLastRequest()->getMethod());
    }

    public function testCancel()
    {
        $this->orders->cancel(12345678);

        $this->assertEquals('/api/conditional_orders/' . 12345678, $this->client->getLastRequest()->getUri()->getPath());
        $this->assertEquals('DELETE', $this->client->getLastRequest()->getMethod());


        $this->orders->cancelAll();

        $responseBody = $this->client->getLastRequest()->getBody();
        $responseBody->rewind();
        $payload = json_decode($responseBody->getContents(), true);

        $this->assertEquals('/api/orders', $this->client->getLastRequest()->getUri()->getPath());
        $this->assertEquals('DELETE', $this->client->getLastRequest()->getMethod());
        $this->assertEquals(['market' => null, 'conditionalOrdersOnly' => true, 'limitOrdersOnly' => null], $payload);


        $this->orders->cancelAll('BTC-PERP', false, false);

        $responseBody = $this->client->getLastRequest()->getBody();
        $responseBody->rewind();
        $payload = json_decode($responseBody->getContents(), true);

        $this->assertEquals('/api/orders', $this->client->getLastRequest()->getUri()->getPath());
        $this->assertEquals('DELETE', $this->client->getLastRequest()->getMethod());
        $this->assertEquals(['market' => 'BTC-PERP', 'conditionalOrdersOnly' => false, 'limitOrdersOnly' => false], $payload);
    }

    public function testCreateOrder()
    {
        $order = $this->orders->create(['side' => PendingConditionalOrder::SIDE_BUY, 'size' => 99.9, 'market' => 'BTC-PERP', 'triggerPrice' => 7000.01, 'orderPrice' => 7001.01]);

        $this->assertInstanceOf(PendingConditionalOrder::class, $order);
        $this->assertEquals('BTC-PERP', $order->market);
        $this->assertEquals(99.9, $order->size);
        $this->assertEquals(7000.01, $order->triggerPrice);
        $this->assertEquals(7001.01, $order->orderPrice);
        $this->assertEquals(['side' => PendingConditionalOrder::SIDE_BUY, 'size' => 99.9, 'market' => 'BTC-PERP', 'triggerPrice' => 7000.01, 'orderPrice' => 7001.01], $order->toArray());

        $order->place();

        $responseBody = $this->client->getLastRequest()->getBody();
        $responseBody->rewind();
        $payload = json_decode($responseBody->getContents(), true);

        $this->assertEquals('/api/conditional_orders', $this->client->getLastRequest()->getUri()->getPath());
        $this->assertEquals('POST', $this->client->getLastRequest()->getMethod());
        $this->assertEquals(['side' => PendingConditionalOrder::SIDE_BUY, 'size' => 99.9, 'market' => 'BTC-PERP', 'triggerPrice' => 7000.01, 'orderPrice' => 7001.01], $payload);

        $order = $this->orders->create()->takeProfit(99.9, 7000.01, 7001.01)->sell('BTC-PERP');

        $this->assertInstanceOf(PendingConditionalOrder::class, $order);
        $this->assertEquals('BTC-PERP', $order->market);
        $this->assertEquals(99.9, $order->size);
        $this->assertEquals(7000.01, $order->triggerPrice);
        $this->assertEquals(7001.01, $order->orderPrice);
        $this->assertEquals(
            ['side' => PendingConditionalOrder::SIDE_SELL, 'size' => 99.9, 'market' => 'BTC-PERP', 'triggerPrice' => 7000.01, 'orderPrice' => 7001.01, 'type' => PendingConditionalOrder::TYPE_TAKE_PROFIT],
            $order->toArray()
        );

        $order = $this->orders->create()->stop(99.9, 7000.01, 7001.01)->buy('BTC-PERP');

        $this->assertInstanceOf(PendingConditionalOrder::class, $order);
        $this->assertEquals('BTC-PERP', $order->market);
        $this->assertEquals(99.9, $order->size);
        $this->assertEquals(7000.01, $order->triggerPrice);
        $this->assertEquals(7001.01, $order->orderPrice);
        $this->assertEquals(
            ['side' => PendingConditionalOrder::SIDE_BUY, 'size' => 99.9, 'market' => 'BTC-PERP', 'triggerPrice' => 7000.01, 'orderPrice' => 7001.01, 'type' => PendingConditionalOrder::TYPE_STOP],
            $order->toArray()
        );

        $order = $this->orders->create()->trailingStop(99.9, -0.05)->buy('BTC-PERP');

        $this->assertInstanceOf(PendingConditionalOrder::class, $order);
        $this->assertEquals('BTC-PERP', $order->market);
        $this->assertEquals(99.9, $order->size);
        $this->assertEquals(-0.05, $order->trailValue);
        $this->assertEquals(
            ['side' => PendingConditionalOrder::SIDE_BUY, 'size' => 99.9, 'market' => 'BTC-PERP', 'trailValue' => -0.05, 'type' => PendingConditionalOrder::TYPE_TRAILING_STOP],
            $order->toArray()
        );
    }
}
