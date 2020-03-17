<?php

namespace FTX\Tests\Client;

use FTX\Client\Exceptions\HttpException;
use FTX\Client\Exceptions\NotFoundException;
use FTX\Client\Exceptions\UnauthorizedException;
use FTX\Client\HttpExceptionHandler;
use FTX\Tests\FTXTestCase;
use Nyholm\Psr7\Request;
use Nyholm\Psr7\Response;

class HttpExceptionHandlerTest extends FTXTestCase
{
    protected HttpExceptionHandler $httpExceptionHandler;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->httpExceptionHandler = new HttpExceptionHandler();
    }
    
    public function testSuccessfullResponseIsReturned()
    {
        $expected = new Response(200);
        
        $actual = $this->httpExceptionHandler->transformResponseToException(
            new Request('GET', 'foo'),
            $expected
        );
        
        $this->assertEquals($expected, $actual);
    }

    public function testNotFoundException()
    {
        $this->expectException(NotFoundException::class);
        
        $this->httpExceptionHandler->transformResponseToException(
            new Request('GET', 'foo'), 
            new Response(404)
        );
    }

    public function testExceptionReturnsRequestAndResponse()
    {
        $request = new Request('GET', 'foo');
        $response = new Response(404);
        
        try {
            $this->httpExceptionHandler->transformResponseToException(
                $request,
                $response
            );   
        } catch(HttpException $exception) {
            $this->assertEquals($request, $exception->getRequest());
            $this->assertEquals($response, $exception->getResponse());
        }
    }

    public function testUnauthorizedException()
    {
        $this->expectException(UnauthorizedException::class);

        $this->httpExceptionHandler->transformResponseToException(
            new Request('GET', 'foo'),
            new Response(401)
        );
    }
}
