<?php

namespace Vdbelt\FTX\Client;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;

class HttpClient
{
    protected ClientInterface $client;
    protected RequestFactoryInterface $requestFactory;
    protected UriFactoryInterface $uriFactory;
    protected HttpExceptionHandler $exceptionHandler;
    
    protected ?string $base_uri;
    protected ?string $api_key;
    protected ?string $api_secret;
    protected ?string $subaccount;
    
    public function __construct(
        ClientInterface $client, 
        RequestFactoryInterface $requestFactory,
        UriFactoryInterface $uriFactory,
        $base_uri,
        ?string $api_key,
        ?string $api_secret,
        ?string $subaccount
    ) {
        $this->client = $client;
        $this->requestFactory = $requestFactory;
        $this->uriFactory = $uriFactory;
        
        $this->exceptionHandler = new HttpExceptionHandler();
        
        $this->base_uri = $base_uri;
        $this->api_key = $api_key;
        $this->api_secret = $api_secret;
        $this->subaccount = $subaccount;
    }
    
    public function get($path, array $parameters = []) : ResponseInterface
    {
        return $this->send('GET', $path, $parameters);
    }
    
    private function send(string $method, $path, array $parameters = [], StreamInterface $body = null) : ResponseInterface
    {
        $request = $this->createRequest($method, $path, $parameters, $body);
        
        $response = $this->client->sendRequest($request);
        $response = $this->exceptionHandler->transformResponseToException($request, $response);
        
        return $response;
    }
    
    private function createUri(string $path, array $parameters = []) : UriInterface
    {
        return $this->uriFactory->createUri($this->base_uri.'/'.$path.'?'.http_build_query($parameters));
    }
    
    private function createRequest(string $method, $path, array $parameters = [], StreamInterface $body = null) : RequestInterface
    {
        $uri = $this->createUri($path, $parameters);
        
        $request = $this->requestFactory->createRequest($method, $uri);
        
        if(null !== $body) {
            $request->withBody($body);
        }
        
        if(null !== $this->subaccount) {
            $request->withHeader('FTX-SUBACCOUNT', $this->subaccount);
        }
        
        return $request;
    }
    
}