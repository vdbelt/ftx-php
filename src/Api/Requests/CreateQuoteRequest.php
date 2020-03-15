<?php


namespace Vdbelt\FTX\Api\Requests;


class CreateQuoteRequest
{
    public string $underlying;
    public string $type;
    public float $strike;
    public \DateTimeInterface $expiry;
    public string $side;
    public float $size;
    public float $limitPrice;
    public bool $hideLimitPrice;
    public \DateTimeInterface $requestExpiry;
}