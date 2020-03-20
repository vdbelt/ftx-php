<?php


namespace FTX\Api;


use FTX\Api\Support\PendingWithdrawalRequest;

class Wallet extends HttpApi
{
    const WALLET_COINS_URI = 'wallet/coins';
    const WALLET_BALANCES_URI = 'wallet/balances';
    const WALLET_ALL_BALANCES_URI = 'wallet/all_balances';
    const WALLET_DEPOSIT_ADDRESS_URI = 'wallet/deposit_address';
    const WALLET_DEPOSITS_URI = 'wallet/deposits';
    const WALLET_WITHDRAWALS_URI = 'wallet/withdrawals';
    
    public function coins()
    {
        return $this->respond($this->http->get(self::WALLET_COINS_URI));
    }
    
    public function balances()
    {
        return $this->respond($this->http->get(self::WALLET_BALANCES_URI));
    }
    
    public function allBalances()
    {
        return $this->respond($this->http->get(self::WALLET_ALL_BALANCES_URI));
    }
    
    public function depositAddress(string $coin)
    {
        return $this->respond($this->http->get(self::WALLET_DEPOSIT_ADDRESS_URI.'/'.$coin));
    }
    
    public function deposits()
    {
        return $this->respond($this->http->get(self::WALLET_DEPOSITS_URI));
    }
    
    public function withdrawals()
    {
        return $this->respond($this->http->get(self::WALLET_WITHDRAWALS_URI));
    }
    
    public function createWithdrawalRequest(string $coin, float $size, string $address) : PendingWithdrawalRequest
    {
        return new PendingWithdrawalRequest($this, compact('coin', 'size', 'address'));
    }
    
    public function withdraw(PendingWithdrawalRequest $pendingWithdrawalRequest)
    {
        return $this->respond($this->http->post(self::WALLET_WITHDRAWALS_URI, null, $pendingWithdrawalRequest->toArray()));
    }
}