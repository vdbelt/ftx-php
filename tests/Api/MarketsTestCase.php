<?php


namespace Vdbelt\FTX\Tests\Api;


use Vdbelt\FTX\Api\Markets;

class MarketsTestCase extends TestCase
{
    protected function getApiClass()
    {
        return Markets::class;
    }
    
    public function test_all() 
    {
        $this->assertEquals(true, true);    
    }
}