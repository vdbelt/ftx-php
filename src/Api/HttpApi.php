<?php

namespace Vdbelt\FTX\Api;

use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Client\NetworkExceptionInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Vdbelt\FTX\Client\HttpClient;

abstract class HttpApi
{
    protected HttpClient $http;
    
    public function __construct(HttpClient $client)
    {
        $this->http = $client;
    }
    
    protected function respond(ResponseInterface $response)
    {
        return json_decode($response->getBody()->getContents(), $assoc = true);
    }
    
}