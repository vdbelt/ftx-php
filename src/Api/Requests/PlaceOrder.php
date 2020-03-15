<?php


namespace Vdbelt\FTX\Api\Requests\Api;


class PlaceOrder
{
    public string $market;
    public string $side;
    public float $price;
    public string $type;
    
}