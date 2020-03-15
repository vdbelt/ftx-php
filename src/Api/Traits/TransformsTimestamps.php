<?php


namespace Vdbelt\FTX\Api\Traits;


trait TransformsTimestamps
{
    protected function transformTimestamps(?\DateTimeInterface $start_time = null, ?\DateTimeInterface $end_time = null)
    {
        $start_time = $start_time ? $start_time->getTimestamp() : null;
        $end_time = $end_time ? $end_time->getTimestamp() : null;

        return [$start_time, $end_time];
    }
}