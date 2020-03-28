<?php


namespace FTX\Api;


use FTX\Api\Support\PendingConditionalOrder;
use FTX\Api\Traits\TransformsTimestamps;

class ConditionalOrders extends HttpApi
{
    use TransformsTimestamps;

    const ORDERS_URI = 'orders';
    const COND_ORDERS_URI = 'conditional_orders';
    const COND_ORDERS_HISTORY_URI = 'conditional_orders/history';

    /**
     * @param string|null $market
     * @param string|null $type type of trigger order (stop, trailing_stop, or take_profit)
     * @return mixed
     */
    public function open(?string $market = null, ?string $type = null)
    {
        return $this->respond($this->http->get(self::COND_ORDERS_URI, compact('market', 'type')));
    }

    public function status(string $orderId)
    {
        return $this->respond($this->http->get(self::COND_ORDERS_URI . '/' . $orderId));
    }

    public function create(?array $attributes = []) : PendingConditionalOrder
    {
        return new PendingConditionalOrder($this, $attributes);
    }

    public function place(PendingConditionalOrder $pendingOrder)
    {
        return $this->respond($this->http->post(self::COND_ORDERS_URI, null, $pendingOrder->toArray()));
    }

    public function cancel(string $orderId)
    {
        return $this->respond($this->http->delete(self::COND_ORDERS_URI . '/' . $orderId));
    }

    /**
     * @param string|null $market
     * @param bool|null $conditionalOrdersOnly
     * @param bool|null $limitOrdersOnly
     * @return mixed
     */
    public function cancelAll(?string $market = null, ?bool $conditionalOrdersOnly = true, ?bool $limitOrdersOnly = null)
    {
        return $this->respond($this->http->delete(self::ORDERS_URI, null, compact('market', 'conditionalOrdersOnly', 'limitOrdersOnly')));
    }

    /**
     * @param string|null $market
     * @param DateTimeInterface|null $start_time
     * @param DateTimeInterface|null $end_time
     * @param string|null $side
     * @param string|null $type valid values are stop, trailing_stop, and take_profit
     * @param string|null $orderType valid values are market and limit
     * @param int|null $limit default 100, maximum 100
     * @return mixed
     */
    public function history(?string $market = null, ?\DateTimeInterface $start_time = null, ?\DateTimeInterface $end_time = null, ?string $side = null, ?string $type = null, ?string $orderType = null, ?int $limit = null)
    {
        [$start_time, $end_time] = $this->transformTimestamps($start_time, $end_time);
        return $this->respond($this->http->get(self::COND_ORDERS_HISTORY_URI, compact('market', 'start_time', 'end_time', 'side', 'type', 'orderType', 'limit')));
    }
}
