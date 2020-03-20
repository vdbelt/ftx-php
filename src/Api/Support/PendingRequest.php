<?php


namespace FTX\Api\Support;


use FTX\Api\HttpApi;

abstract class PendingRequest
{
    protected HttpApi $api;

    protected array $attributes = [];

    public function __construct(HttpApi $api, ?array $attributes = [])
    {
        $this->api = $api;

        $this->attributes = array_merge($this->attributes, $attributes);
    }

    public function __get($name)
    {
        return array_key_exists($name, $this->attributes) ? $this->attributes[$name] : null;
    }

    public function toArray()
    {
        return $this->attributes;
    }
}