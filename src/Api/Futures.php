<?php

namespace FTX\Api;

use FTX\Api\Traits\TransformsTimestamps;

class Futures extends HttpApi
{
    use TransformsTimestamps;
    
    const FUTURES_URI = 'futures';
    const FUNDING_RATES_URI = 'funding_rates';
    
    public function all()
    {
        return $this->respond($this->http->get(self::FUTURES_URI));
    }
    
    public function get(string $future)
    {
        return $this->respond($this->http->get(self::FUTURES_URI.'/'.$future));
    }
    
    public function stats(string $future)
    {
        return $this->respond($this->http->get(self::FUTURES_URI.'/'.$future.'/stats'));
    }

    public function fundingRates(?string $future = null, ?\DateTimeInterface $start_time = null, ?\DateTimeInterface $end_time = null)
    {
        [$start_time, $end_time] = $this->transformTimestamps($start_time, $end_time);
        
        return $this->respond($this->http->get(self::FUNDING_RATES_URI, compact('future', 'start_time', 'end_time')));
    }
    
}