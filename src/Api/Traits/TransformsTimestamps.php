<?php


namespace FTX\Api\Traits;


trait TransformsTimestamps
{
    protected function transformTimestamps(...$timestamps)
    {
        return array_map(function($dateTime) {
            if($dateTime instanceof \DateTime) {
                return $dateTime->getTimestamp();
            }
            return null;
        }, $timestamps);
    }
}