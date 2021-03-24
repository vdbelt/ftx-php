<?php


namespace FTX\Api\Support;



class PendingOrder extends PendingRequest
{
    const SIDE_BUY = 'buy';
    const SIDE_SELL = 'sell';

    const TYPE_LIMIT = 'limit';
    const TYPE_MARKET = 'market';
    
    public function buy(string $market) : self
    {
        $this->attributes['market'] = $market;
        $this->attributes['side'] = self::SIDE_BUY;
        
        return $this;
    }
    
    public function sell(string $market) : self
    {
        $this->attributes['market'] = $market;
        $this->attributes['side'] = self::SIDE_SELL;
        
        return $this;
    }
    
    public function market(float $size) : self
    {
        $this->attributes['type'] = self::TYPE_MARKET;
        $this->attributes['size'] = $size;
        $this->attributes['price'] = null;
        
        return $this;
    }
    
    public function limit(float $size, float $price) : self
    {
        $this->attributes['type'] = self::TYPE_LIMIT;
        $this->attributes['size'] = $size;
        $this->attributes['price'] = $price;
        
        return $this;
    }

    public function reduceOnly() : self
    {
        $this->attributes['reduceOnly'] = true;

        return $this;
    }

    public function immediateOrCancel() : self
    {
        $this->attributes['ioc'] = true;

        return $this;
    }
    
    public function postOnly() : self
    {
        $this->attributes['postOnly'] = true;
        
        return $this;
    }
    
    public function withClientId($clientId) : self
    {
        $this->attributes['clientId'] = $clientId;
        
        return $this;
    }
    
    public function place()
    {
        return $this->api->place($this);
    }
}