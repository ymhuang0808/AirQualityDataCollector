<?php

namespace App\Aggregate;


use App\Aggregate\Contracts\AggregateProcessorContract;
use App\Aggregate\Contracts\AggregatorContract;
use App\Aggregate\Contracts\AggregateFactoryContract;
use App\Repository\EpaAggregatableDatasetRepository;
use App\Repository\LassAggregatableDatasetRepository;

class AggregateFactory implements AggregateFactoryContract
{
    public function getAggregator(): AggregatorContract
    {
        return new AirQualityDatasetAggregator();
    }

    public function getAggregateProcessor(string $source): AggregateProcessorContract
    {
        switch ($source) {
            case 'lass':
                $repository = new LassAggregatableDatasetRepository();
                break;

            case 'epa':
                $repository = new EpaAggregatableDatasetRepository();
                break;

            default:
                // TODO: A more readable exception
                throw new \Exception("Could not find processor");
                break;
        }

        return new AirQualityAggregateProcessor($repository);
    }
}