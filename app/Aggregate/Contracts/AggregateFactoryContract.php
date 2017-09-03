<?php

namespace App\Aggregate\Contracts;


interface AggregateFactoryContract
{
    public function getAggregateProcessor(string $source): AggregateProcessorContract;

    public function getAggregator(): AggregatorContract;
}