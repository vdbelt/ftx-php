<?php


namespace FTX\Api;


class Subaccounts extends HttpApi
{
    const SUBACCOUNTS_URI = 'subaccounts';
    const SUBACCOUNTS_UPDATE_NAME_URI = 'subaccounts/update_name';
    const SUBACCOUNTS_TRANSFER_URI = 'subaccounts/transfer';
    
    public function all()
    {
        return $this->respond($this->http->get(self::SUBACCOUNTS_URI.'/'));
    }
    
    public function create(string $nickname)
    {
        return $this->respond($this->http->post(self::SUBACCOUNTS_URI, null, compact('nickname')));
    }
    
    public function changeName(string $nickname, string $newNickname)
    {
        return $this->respond($this->http->post(self::SUBACCOUNTS_UPDATE_NAME_URI, null, compact('nickname', 'newNickname')));
    }
    
    public function delete(string $nickname)
    {
        return $this->respond($this->http->delete(self::SUBACCOUNTS_URI));
    }
    
    public function balances(string $nickname)
    {
        return $this->respond($this->http->get(self::SUBACCOUNTS_URI.'/'.$nickname.'/balances'));
    }
    
    public function transfer(string $coin, float $size, ?string $source = null, ?string $destination = null)
    {
        return $this->respond($this->http->post(self::SUBACCOUNTS_TRANSFER_URI, null, compact('coin', 'size', 'source', 'destination')));
    }
}