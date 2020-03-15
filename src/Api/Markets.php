<?php

namespace Vdbelt\FTX\Api;

use Psr\Http\Message\ResponseInterface;
use Vdbelt\FTX\Api\Traits\TransformsTimestamps;

class Markets extends HttpApi
{
    use TransformsTimestamps;
    
    const MARKETS_URI = 'markets';
    
    public function all()
    {
        return $this->respond($this->http->get(self::MARKETS_URI));
    }
    
    public function get(string $market)
    {
        return $this->respond($this->http->get(self::MARKETS_URI.'/'.$market));
    }
    
    public function orderbook(string $market, int $depth = null)
    {
        return $this->respond($this->http->get(self::MARKETS_URI.'/'.$market.'/orderbook', compact('depth')));
    }
    
    public function trades(string $market, ?int $limit, ?\DateTimeInterface $start_time = null, ?\DateTimeInterface $end_time = null)
    {
        [$start_time, $end_time] = $this->transformTimestamps($start_time, $end_time);
        
        return $this->respond($this->http->get(self::MARKETS_URI.'/'.$market.'/trades', compact('limit', 'start_time', 'end_time')));
    }

    public function candles(string $market, int $resolution, ?int $limit = null, ?\DateTimeInterface $start_time = null, ?\DateTimeInterface $end_time = null)
    {
        [$start_time, $end_time] = $this->transformTimestamps($start_time, $end_time);

        return $this->respond($this->http->get(self::MARKETS_URI.'/'.$market.'/candles', compact('limit', 'resolution', 'start_time', 'end_time')));
    }
    
}