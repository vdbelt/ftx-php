<?php


namespace FTX\Api;


use FTX\Api\Traits\TransformsTimestamps;

class FundingPayments extends HttpApi
{
    use TransformsTimestamps;
    
    const FUNDING_PAYMENTS_URI = 'funding_payments';
    
    public function all(?string $future = null, ?\DateTimeInterface $start_time = null, ?\DateTimeInterface $end_time = null)
    {
        return $this->respond($this->http->get(self::FUNDING_PAYMENTS_URI));
    }
    
}