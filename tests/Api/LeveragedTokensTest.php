<?php

namespace FTX\Tests\Api;

use FTX\Api\LeveragedTokens;
use Http\Mock\Client;

class LeveragedTokensTest extends TestCase
{
    protected LeveragedTokens $leveragedTokens;
    protected Client $client;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->leveragedTokens = new LeveragedTokens($this->http);
    }

    public function testRequestCreation()
    {
        $this->leveragedTokens->requestCreation('foo', 99.9);

        $responseBody = $this->client->getLastRequest()->getBody();
        $responseBody->rewind();
        $payload = json_decode($responseBody->getContents(), true);

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/lt/foo/create');
        $this->assertEquals($this->client->getLastRequest()->getMethod(), 'POST');
        $this->assertEquals(['size' => 99.9], $payload);
    }

    public function testRequestRedemption()
    {
        $this->leveragedTokens->requestRedemption('foo', 99.9);

        $responseBody = $this->client->getLastRequest()->getBody();
        $responseBody->rewind();
        $payload = json_decode($responseBody->getContents(), true);

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/lt/foo/redeem');
        $this->assertEquals($this->client->getLastRequest()->getMethod(), 'POST');
        $this->assertEquals(['size' => 99.9], $payload);
    }

    public function testBalances()
    {
        $this->leveragedTokens->balances();

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/lt/balances');
        $this->assertEquals($this->client->getLastRequest()->getMethod(), 'GET');
    }

    public function testRedemptions()
    {
        $this->leveragedTokens->redemptions();

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/lt/redemptions');
        $this->assertEquals($this->client->getLastRequest()->getMethod(), 'GET');
    }

    public function testAll()
    {
        $this->leveragedTokens->all();

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/lt/tokens');
        $this->assertEquals($this->client->getLastRequest()->getMethod(), 'GET');
    }

    public function testInfo()
    {
        $this->leveragedTokens->info('foo');

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/lt/foo');
        $this->assertEquals($this->client->getLastRequest()->getMethod(), 'GET');
    }

    public function testCreationRequests()
    {
        $this->leveragedTokens->creationRequests();

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/lt/creations');
        $this->assertEquals($this->client->getLastRequest()->getMethod(), 'GET');
    }
}
