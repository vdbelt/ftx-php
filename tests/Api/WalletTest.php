<?php

namespace FTX\Tests\Api;

use FTX\Api\Support\PendingWithdrawalRequest;
use FTX\Api\Wallet;
use Http\Mock\Client;

class WalletTest extends TestCase
{
    protected Wallet $wallet;
    protected Client $client;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->wallet = new Wallet($this->http);
    }

    public function testDeposits()
    {
        $this->wallet->deposits();
        
        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/wallet/deposits');
    }

    public function testDepositAddress()
    {
        $this->wallet->depositAddress('foo');

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/wallet/deposit_address/foo');
    }

    public function testWithdrawals()
    {
        $this->wallet->withdrawals();

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/wallet/withdrawals');
    }

    public function testCoins()
    {
        $this->wallet->coins();

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/wallet/coins');
    }

    public function testBalances()
    {
        $this->wallet->balances();

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/wallet/balances');
    }

    public function testAllBalances()
    {
        $this->wallet->allBalances();

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/wallet/all_balances');
    }

    public function testWithdrawalRequest()
    {
        $withdrawal = $this->wallet->createWithdrawalRequest('foo', 100, 'bar');

        $this->assertInstanceOf(PendingWithdrawalRequest::class, $withdrawal);
        $this->assertEquals('foo', $withdrawal->coin);
        $this->assertEquals(100, $withdrawal->size);
        $this->assertEquals(['coin' => 'foo', 'size' => 100, 'address' => 'bar'], $withdrawal->toArray());

        $withdrawal->withdraw();

        $responseBody = $this->client->getLastRequest()->getBody();
        $responseBody->rewind();
        $payload = json_decode($responseBody->getContents(), true);

        $this->assertEquals($this->client->getLastRequest()->getUri()->getPath(), '/api/wallet/withdrawals');
        $this->assertEquals($this->client->getLastRequest()->getMethod(), 'POST');
        $this->assertEquals(['coin' => 'foo', 'size' => 100, 'address' => 'bar'], $payload);
        
        
        $withdrawal = $this->wallet
            ->createWithdrawalRequest('foo', 100, 'bar')
            ->withCode('foo3')
            ->withTag('foo2')
            ->withPassword('foo1');
        
        $this->assertInstanceOf(PendingWithdrawalRequest::class, $withdrawal);
        $this->assertEquals('foo', $withdrawal->coin);
        $this->assertEquals(100, $withdrawal->size);
        $this->assertEquals(
            ['coin' => 'foo', 'size' => 100, 'address' => 'bar', 'password' => 'foo1', 'tag' => 'foo2', 'code' => 'foo3'], 
            $withdrawal->toArray()
        );
    }
}
