<?php

namespace FTX\Tests\Api;

use FTX\Api\Options;
use Http\Mock\Client;

class OptionsTest extends TestCase
{
    protected Options $options;
    protected Client $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->options = new Options($this->http);
    }

    public function testCancelQuote()
    {
        $this->options->cancelQuote('foo');

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/options/quotes/foo');
        $this->assertEquals($this->client->getLastRequest()->getMethod(), 'DELETE');
    }

    public function testTrades()
    {
        $this->options->trades();

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/options/trades');
        $this->assertEquals($this->client->getLastRequest()->getMethod(), 'GET');
    }

    public function testMyRequests()
    {
        $this->options->myRequests();

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/options/my_requests');
        $this->assertEquals($this->client->getLastRequest()->getMethod(), 'GET');
    }

    public function testAccountInfo()
    {
        $this->options->accountInfo();

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/options/account_info');
        $this->assertEquals($this->client->getLastRequest()->getMethod(), 'GET');
    }

    public function testCancelRequest()
    {
        $this->options->cancelRequest('foo');

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/options/requests/foo');
        $this->assertEquals($this->client->getLastRequest()->getMethod(), 'DELETE');
    }

    public function testAcceptQuote()
    {
        $this->options->acceptQuote('foo');

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/options/quotes/foo/accept');
        $this->assertEquals($this->client->getLastRequest()->getMethod(), 'POST');
    }

    public function testCreateQuote()
    {
        $this->options->createQuote('foo', 99.9);

        $responseBody = $this->client->getLastRequest()->getBody();
        $responseBody->rewind();
        $payload = json_decode($responseBody->getContents(), true);

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/options/requests/foo/quotes');
        $this->assertEquals($this->client->getLastRequest()->getMethod(), 'POST');
        $this->assertEquals($payload, ['price' => 99.9]);
    }

    public function testRequests()
    {
        $this->options->requests();

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/options/requests');
        $this->assertEquals($this->client->getLastRequest()->getMethod(), 'GET');
    }

    public function testFills()
    {
        $this->options->fills();

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/options/fills');
        $this->assertEquals($this->client->getLastRequest()->getMethod(), 'GET');
    }

    public function testMyQuotes()
    {
        $this->options->myQuotes();

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/options/my_quotes');
        $this->assertEquals($this->client->getLastRequest()->getMethod(), 'GET');
    }

    public function testQuotesForRequest()
    {
        $this->options->quotesForRequest('foo');

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/options/requests/foo/quotes');
        $this->assertEquals($this->client->getLastRequest()->getMethod(), 'GET');
    }

    public function testPositions()
    {
        $this->options->positions();

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/options/positions');
        $this->assertEquals($this->client->getLastRequest()->getMethod(), 'GET');
    }
}
