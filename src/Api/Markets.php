<?php

namespace Vdbelt\FTX\Api;

use Psr\Http\Message\ResponseInterface;

class Markets extends HttpApi
{
    const MARKETS_URI = 'markets';
    
    public function all()
    {
        return $this->toJson($this->http->get(self::MARKETS_URI));
    }
    
    public function get(string $market)
    {
        return $this->toJson($this->http->get(self::MARKETS_URI.'/'.$market));
    }
    
    public function orderbook(string $market, int $depth = null)
    {
        return $this->toJson($this->http->get(self::MARKETS_URI.'/'.$market.'/orderbook', compact('depth')));
    }
    
    public function trades(string $market, ?int $limit, ?\DateTimeInterface $start_time = null, ?\DateTimeInterface $end_time = null)
    {
        [$start_time, $end_time] = $this->transformTimestamps($start_time, $end_time);
        
        return $this->toJson($this->http->get(self::MARKETS_URI.'/'.$market.'/trades', compact('limit', 'start_time', 'end_time')));
    }
    
}