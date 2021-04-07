<?php


namespace FTX\Api;


class SpotMargin extends HttpApi
{
    const SPOTMARGIN_URI = 'spot_margin';

    public function borrowRates()
    {
        return $this->respond($this->http->get(self::SPOTMARGIN_URI.'/borrow_rates'));
    }
    
    public function lendingRates()
    {
        return $this->respond($this->http->get(self::SPOTMARGIN_URI.'/lending_rates'));
    }

    public function borrowSummary()
    {
        return $this->respond($this->http->get(self::SPOTMARGIN_URI.'/borrow_summary'));
    }

    public function marketInfo(string $market)
    {
        return $this->respond($this->http->get(self::SPOTMARGIN_URI.'/market_info', compact('market')));
    }

    public function borrowHistory()
    {
        return $this->respond($this->http->get(self::SPOTMARGIN_URI.'/borrow_history'));
    }

    public function lendingHistory()
    {
        return $this->respond($this->http->get(self::SPOTMARGIN_URI.'/lending_history'));
    }
    
    public function offers()
    {
        return $this->respond($this->http->get(self::SPOTMARGIN_URI.'/offers'));
    }
    
    public function submitLendingOffer(string $coin, float $size, float $rate)
    {
        return $this->respond($this->http->post(self::SPOTMARGIN_URI.'/offers', null, compact('coin', 'size', 'rate')));
    }
    
    public function lendingInfo()
    {
        return $this->respond($this->http->get(self::SPOTMARGIN_URI.'/lending_info'));
    }
}