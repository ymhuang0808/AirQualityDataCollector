<?php

namespace App\Aggregate;


interface AggregatorContract
{
    public function setProcessor(AggregateProcessorContract $processor);
}