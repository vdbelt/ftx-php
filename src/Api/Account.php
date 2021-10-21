<?php


namespace FTX\Api;


class Account extends HttpApi
{
    const ACCOUNTS_URI = 'account';
    const POSITIONS_URI = 'positions';
    const ACCOUNTS_LEVERAGE_URI = 'account/leverage';
    
    public function get()
    {
        return $this->respond($this->http->get(self::ACCOUNTS_URI));    
    }
    
    public function positions(?bool $showAvgPrice = null)
    {
        if($showAvgPrice == false) {
            $showAvgPrice = null;
        }
        
        return $this->respond($this->http->get(self::POSITIONS_URI, compact("showAvgPrice")));
    }
    
    public function changeAccountLeverage(int $leverage)
    {
        return $this->respond($this->http->post(self::ACCOUNTS_LEVERAGE_URI, null, compact('leverage')));
    }
}