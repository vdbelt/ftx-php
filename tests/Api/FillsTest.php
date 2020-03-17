<?php

namespace FTX\Tests\Api;

use FTX\Api\Fills;
use Http\Mock\Client;

class FillsTest extends TestCase
{
    protected Fills $fills;
    protected Client $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fills = new Fills($this->http);
    }

    public function testAll()
    {
        $this->fills->all();

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/fills');
        $this->assertEquals($this->client->getLastRequest()->getMethod(), 'GET');
    }
}
