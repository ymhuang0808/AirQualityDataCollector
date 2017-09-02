<?php

namespace App\Aggregate;


interface AggregatorContract
{
    public function process();

    public function setProcessor(AggregateProcessorContract $processor);

    public function setSource(string $source);

    public function getSource(): string ;
}