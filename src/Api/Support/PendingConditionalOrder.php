<?php


namespace FTX\Api\Support;



class PendingConditionalOrder extends PendingRequest
{
    const SIDE_BUY = 'buy';
    const SIDE_SELL = 'sell';

    const TYPE_STOP = 'stop';
    const TYPE_TAKE_PROFIT = 'takeProfit';
    const TYPE_TRAILING_STOP = 'trailingStop';

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

    public function takeProfit(float $size, float $triggerPrice, ?float $orderPrice = null) : self
    {
        $this->attributes['type'] = self::TYPE_TAKE_PROFIT;
        $this->attributes['size'] = $size;
        $this->attributes['triggerPrice'] = $triggerPrice;
        $this->attributes['orderPrice'] = $orderPrice;

        return $this;
    }

    public function stop(float $size, float $triggerPrice, ?float $orderPrice = null) : self
    {
        $this->attributes['type'] = self::TYPE_STOP;
        $this->attributes['size'] = $size;
        $this->attributes['triggerPrice'] = $triggerPrice;
        $this->attributes['orderPrice'] = $orderPrice;

        return $this;
    }

    public function trailingStop(float $size, float $trailValue) : self
    {
        $this->attributes['type'] = self::TYPE_TRAILING_STOP;
        $this->attributes['size'] = $size;
        $this->attributes['trailValue'] = $trailValue;

        return $this;
    }

    public function reduceOnly() : self
    {
        $this->attributes['reduceOnly'] = true;

        return $this;
    }

    public function retryUntilFilled(bool $retry) : self
    {
        $this->attributes['retryUntilFilled'] = $retry;

        return $this;
    }

    public function place()
    {
        return $this->api->place($this);
    }
}
