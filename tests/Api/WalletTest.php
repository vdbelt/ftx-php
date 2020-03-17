<?php

namespace FTX\Tests\Api;

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

    public function testRequestWithdrawal()
    {

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
}
