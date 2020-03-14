<?php


namespace Vdbelt\FTX\Tests\Api;


use Vdbelt\FTX\Tests\FTXTestCase;

abstract class TestCase extends FTXTestCase
{
    protected $server;
    
    abstract protected function getApiClass();
}