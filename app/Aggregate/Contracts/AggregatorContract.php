<?php

namespace App\Aggregate\Contracts;


interface AggregatorContract
{
    public function process();

    /**
     * @param AggregateProcessorContract $processor
     * @return self
     */
    public function setProcessor(AggregateProcessorContract $processor);

    /**
     * @param string $source
     * @return self
     */
    public function setSource(string $source);

    public function getSource(): string ;

    public function getAvailableSource(): array ;
}