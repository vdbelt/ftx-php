<?php


namespace FTX\Api;


class LeveragedTokens extends HttpApi
{
    const LEVERAGED_TOKENS_URI = 'lt/tokens';
    const LEVERAGED_TOKENS_BALANCES_URI = 'lt/balances';
    const LEVERAGED_TOKENS_CREATIONS_URI = 'lt/creations';
    const LEVERAGED_TOKENS_REDEMPTIONS_URI = 'lt/redemptions';

    public function all()
    {
        return $this->respond($this->http->get(self::LEVERAGED_TOKENS_URI));
    }

    public function info(string $token_name)
    {
        return $this->respond($this->http->get('lt/' . $token_name));
    }

    public function balances()
    {
        return $this->respond($this->http->get(self::LEVERAGED_TOKENS_BALANCES_URI));
    }
    
    public function creationRequests()
    {
        return $this->respond($this->http->get(self::LEVERAGED_TOKENS_CREATIONS_URI));
    }

    public function redemptions()
    {
        return $this->respond($this->http->get(self::LEVERAGED_TOKENS_REDEMPTIONS_URI));
    }
    
    public function requestCreation(string $token_name, float $size)
    {
        return $this->respond($this->http->post('lt/'.$token_name.'/create', null, compact('size')));
    }
    
    public function requestRedemption(string $token_name, float $size)
    {
        return $this->respond($this->http->post('lt/'.$token_name.'/redeem', null, compact('size')));
    }
}