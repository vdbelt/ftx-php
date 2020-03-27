<?php


namespace FTX\Api;


use FTX\Api\Support\PendingOrder;
use FTX\Api\Traits\TransformsTimestamps;

class Orders extends HttpApi
{
    use TransformsTimestamps;

    const ORDERS_URI = 'orders';
    const ORDERS_HISTORY_URI = 'orders/history';
    
    public function open(?string $market = null)
    {
        return $this->respond($this->http->get(self::ORDERS_URI, compact('market')));
    }

    public function status(string $orderId)
    {
        return $this->respond($this->http->get(self::ORDERS_URI . '/' . $orderId));
    }

    public function create(?array $attributes = []) : PendingOrder
    {
        return new PendingOrder($this, $attributes);
    }
    
    public function place(PendingOrder $pendingOrder)
    {
        return $this->respond($this->http->post(self::ORDERS_URI, null, $pendingOrder->toArray()));
    }

    public function cancel(string $orderId)
    {
        return $this->respond($this->http->delete(self::ORDERS_URI . '/' . $orderId));
    }

    /**
     * This will also cancel conditional orders (stop loss and trailing stop orders).
     * @param string|null $market
     * @param bool|null $conditionalOrdersOnly
     * @param bool|null $limitOrdersOnly
     * @return mixed
     */
    public function cancelAll(?string $market = null, ?bool $conditionalOrdersOnly = null, ?bool $limitOrdersOnly = null)
    {
        return $this->respond($this->http->delete(self::ORDERS_URI, null, compact('market', 'conditionalOrdersOnly', 'limitOrdersOnly')));
    }

    public function history(?string $market = null, ?\DateTimeInterface $start_time = null, ?\DateTimeInterface $end_time = null, ?int $limit = null)
    {
        [$start_time, $end_time] = $this->transformTimestamps($start_time, $end_time);
        return $this->respond($this->http->get(self::ORDERS_HISTORY_URI, compact('market', 'start_time', 'end_time', 'limit')));
    }

}
