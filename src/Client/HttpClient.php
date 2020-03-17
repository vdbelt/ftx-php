<?php

namespace FTX\Client;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;

class HttpClient
{
    protected ClientInterface $client;
    protected RequestFactoryInterface $requestFactory;
    protected UriFactoryInterface $uriFactory;
    protected StreamFactoryInterface $streamFactory;
    protected HttpExceptionHandler $exceptionHandler;
    
    protected string $base_uri;
    public ?string $api_key = null;
    public ?string $api_secret = null;
    public ?string $subaccount = null;
    
    public function __construct(
        ClientInterface $client, 
        RequestFactoryInterface $requestFactory,
        UriFactoryInterface $uriFactory,
        StreamFactoryInterface $streamFactory,
        $base_uri
    ) {
        $this->client = $client;
        $this->requestFactory = $requestFactory;
        $this->uriFactory = $uriFactory;
        $this->streamFactory = $streamFactory;
        
        $this->exceptionHandler = new HttpExceptionHandler();
        
        $this->base_uri = $base_uri;
    }
    
    public function authenticate(string $api_key, string $api_secret) 
    {
        $this->api_key = $api_key;
        $this->api_secret = $api_secret;
    }
    
    public function subaccount(string $subaccount)
    {
        $this->subaccount = $subaccount;
    }
    
    public function get(string $path, array $parameters = []) : ResponseInterface
    {
        return $this->send('GET', $path, $parameters);
    }
    
    public function post(string $path, ?array $parameters = [], array $payload = []) : ResponseInterface
    {
        $body = $this->streamFactory->createStream(json_encode($payload));
        return $this->send('POST', $path, $parameters, $body);
    }
    
    public function delete(string $path) : ResponseInterface
    {
        return $this->send('DELETE', $path);
    }
    
    private function send(string $method, $path, ?array $parameters = [], StreamInterface $body = null) : ResponseInterface
    {
        $request = $this->createRequest($method, $path, $parameters, $body);
        
        $response = $this->client->sendRequest($request);
        $response = $this->exceptionHandler->transformResponseToException($request, $response);
        
        return $response;
    }
    
    private function createUri(string $path, ?array $parameters = []) : UriInterface
    {
        $uri = $this->uriFactory->createUri($this->base_uri.'/'.$path);
        
        if(null !== $parameters) {
            $uri = $uri->withQuery(http_build_query($parameters));
        }
        
        return $uri;
    }
    
    private function createRequest(string $method, $path, ?array $parameters = [], StreamInterface $body = null) : RequestInterface
    {
        $uri = $this->createUri($path, $parameters);
        
        $request = $this->requestFactory->createRequest($method, $uri);
        
        if(null !== $body) {
            $request = $request->withBody($body);
        }
        
        if(null !== $this->subaccount) {
            $request = $request->withHeader('FTX-SUBACCOUNT', $this->subaccount);
        }
        
        if(null !== $this->api_key && null !== $this->api_secret) {
            $timestamp = time()*1000;
            $request = $request->withHeader('FTX-KEY', $this->api_key);
            $request = $request->withHeader('FTX-TS', $timestamp);
            $request = $request->withHeader('FTX-SIGN', $this->calculateSignature($timestamp, $request));
        }
        
        return $request;
    }
    
    private function calculateSignature(int $timestamp, RequestInterface $request)
    {
        $data = $timestamp
            . $request->getMethod()
            . $request->getUri()->getPath()
            . ($request->getUri()->getQuery() ? '?'.$request->getUri()->getQuery() : '');
        
        return hash_hmac('sha256', $data, $this->api_secret);
    }
    
}