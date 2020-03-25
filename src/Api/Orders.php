<?php


namespace FTX\Api;


use FTX\Api\Support\PendingOrder;

class Orders extends HttpApi
{
    const ORDERS_URI = 'orders';
    const ORDERS_HISTORY_URI = 'orders/history';
    
    public function open(?string $market = null)
    {
        return $this->respond($this->http->get(self::ORDERS_URI, compact('market')));
    }
    
    public function create(?array $attributes = []) : PendingOrder
    {
        return new PendingOrder($this, $attributes);
    }
    
    public function place(PendingOrder $pendingOrder)
    {
        return $this->respond($this->http->post(self::ORDERS_URI, null, $pendingOrder->toArray()));
    }
    
    public function history()
    {
        return $this->respond($this->http->get(self::ORDERS_HISTORY_URI));
    }
}
