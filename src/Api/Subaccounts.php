<?php


namespace FTX\Api;


class Subaccounts extends HttpApi
{
    const SUBACCOUNTS_URI = 'subaccounts';
    
    public function all()
    {
        return $this->respond($this->http->get(self::SUBACCOUNTS_URI.'/'));
    }
    
    public function create(string $nickname)
    {
        
    }
    
    public function changeName(string $nickname, string $newNickname)
    {
        
    }
    
    public function delete(string $nickname)
    {
        return $this->respond($this->http->delete(self::SUBACCOUNTS_URI));
    }
    
    public function balances(string $nickname)
    {
        return $this->respond($this->http->get(self::SUBACCOUNTS_URI.'/'.$nickname.'/balances'));
    }
    
    public function transfer(string $coin, float $size, string $source, string $destination)
    {
        
    }
}