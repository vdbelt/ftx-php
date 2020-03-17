<?php


namespace FTX\Client\Exceptions;


use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class HttpException extends \Exception
{
    protected RequestInterface $request;
    protected ResponseInterface $response;
    
    public function __construct(string $message, RequestInterface $request, ResponseInterface $response, Throwable $previous = null)
    {
        parent::__construct($message, $response->getStatusCode(), $previous);
        
        $this->request = $request;
        $this->response = $response;
    }
    
    public function getRequest() : RequestInterface
    {
        return $this->request;
    }
    
    public function getResponse() : ResponseInterface
    {
        return $this->response;
    }
}