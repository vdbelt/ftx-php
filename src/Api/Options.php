<?php


namespace Vdbelt\FTX\Api;


use Vdbelt\FTX\Api\Requests\CreateQuoteRequest;
use Vdbelt\FTX\Api\Traits\TransformsTimestamps;

class Options extends HttpApi
{
    use TransformsTimestamps;
    
    const OPTIONS_URI = 'options';
    const OPTIONS_REQUESTS_URI = 'options/requests';
    const OPTIONS_MY_REQUESTS_URI = 'options/my_requests';
    const OPTIONS_TRADES_URI = 'options/trades';
    const OPTIONS_POSITIONS_URI = 'options/positions';
    const OPTIONS_FILLS_URI = 'options/fills';
    const OPTIONS_ACCOUNT_INFO_URI = 'options/account_info';
    const OPTIONS_MY_QUOTES_URI = 'options/my_quotes';
    
    public function requests()
    {
        return $this->respond($this->http->get(self::OPTIONS_REQUESTS_URI));
    }
    
    public function myRequests()
    {
        return $this->respond($this->http->get(self::OPTIONS_MY_REQUESTS_URI));
    }
    
    public function createRequest(CreateQuoteRequest $request)
    {
        
    }
    
    public function cancelRequest(string $request_id)
    {
        return $this->respond($this->http->delete(self::OPTIONS_URI.'/requests/'.$request_id));
    }
    
    public function quotesForRequest(string $request_id)
    {
        return $this->respond($this->http->get(self::OPTIONS_URI.'/requests/'.$request_id.'/quotes'));
    }
    
    public function createQuote()
    {
        
    }
    
    public function myQuotes()
    {
        return $this->respond($this->http->get(self::OPTIONS_MY_QUOTES_URI));
    }
    
    public function cancelQuote(string $quote_id)
    {
        return $this->respond($this->http->delete(self::OPTIONS_URI.'/quotes/'.$quote_id));
    }
    
    public function acceptQuote(string $quote_id)
    {
        
    }
    
    public function accountInfo()
    {
        return $this->respond($this->http->get(self::OPTIONS_ACCOUNT_INFO_URI));
    }
    
    public function positions()
    {
        return $this->respond($this->http->get(self::OPTIONS_POSITIONS_URI));
    }
    
    public function fills(int $limit = null)
    {
        return $this->respond($this->http->get(self::OPTIONS_FILLS_URI));
    }
    
    public function trades(int $limit = null, \DateTimeInterface $start_time = null, \DateTimeInterface $end_time = null)
    {
        [$start_time, $end_time] = $this->transformTimestamps($start_time, $end_time);
        
        return $this->respond($this->http->get(self::OPTIONS_TRADES_URI, compact('limit', 'start_time', 'end_time')));
    }
}