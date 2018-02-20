<?php

namespace App\Aggregate\Contracts;


interface AggregateFactoryContract
{
    public function getAggregator():  AggregatorContract;
    public function getAggregateProcessor(string $source): ?AggregateProcessorContract;
}