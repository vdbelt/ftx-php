<?php


namespace Vdbelt\FTX\Api;


class Options extends HttpApi
{
    const OPTIONS_URI = 'options';
    
    public function requests()
    {
        return $this->toJson($this->http->get(self::OPTIONS_URI.'/requests'));
    }
    
    public function trades(int $limit = null, \DateTimeInterface $start_time = null, \DateTimeInterface $end_time = null)
    {
        [$start_time, $end_time] = $this->transformTimestamps($start_time, $end_time);
        
        return $this->toJson($this->http->get(self::OPTIONS_URI.'/trades', compact('limit', 'start_time', 'end_time')));
    }
    
    
}