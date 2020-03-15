<?php


namespace Vdbelt\FTX\Api;


use Vdbelt\FTX\Api\Traits\TransformsTimestamps;

class Fills extends HttpApi
{
    use TransformsTimestamps;
    
    const FILLS_URI = 'fills';
    
    public function all(string $market, int $limit, ?\DateTimeInterface $start_time = null, ?\DateTimeInterface $end_time = null)
    {
        [$start_time, $end_time] = $this->transformTimestamps($start_time, $end_time);
        
        $this->respond($this->http->get(self::FILLS_URI, compact('market', 'limit', 'start_time', 'end_time')));
    }
}