<?php


namespace FTX\Api;


class Orders extends HttpApi
{
    const ORDERS_URI = 'orders';
    const ORDERS_HISTORY_URI = 'orders/history';
    
    public function open()
    {
        return $this->respond($this->http->get(self::ORDERS_URI));
    }
    
    public function history()
    {
        return $this->respond($this->http->get(self::ORDERS_HISTORY_URI));
    }
}