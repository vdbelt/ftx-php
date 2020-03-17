<?php

namespace FTX\Tests\Api\Traits;


use Cassandra\Date;
use FTX\Api\Traits\TransformsTimestamps;
use FTX\Tests\FTXTestCase;

class TransformsTimestampsTest extends FTXTestCase
{
    use TransformsTimestamps;
    
    public function testTransformsTimestamps()
    {
        [$start_time, $end_time] = $this->transformTimestamps(new \DateTime(), new \DateTime());
        
        $this->assertIsInt($start_time);
        $this->assertIsInt($end_time);
    }
}
