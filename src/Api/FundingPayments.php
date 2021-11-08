<?php


namespace FTX\Api;


use FTX\Api\Traits\TransformsTimestamps;

class FundingPayments extends HttpApi
{
    use TransformsTimestamps;
    
    const FUNDING_PAYMENTS_URI = 'funding_payments';
    
    public function all(?string $future = null, ?\DateTimeInterface $start_time = null, ?\DateTimeInterface $end_time = null)
    {
        [$start_time, $end_time] = $this->transformTimestamps($start_time, $end_time);
        return $this->respond($this->http->get(self::FUNDING_PAYMENTS_URI, compact('future', 'start_time', 'end_time')));
    }
    
}