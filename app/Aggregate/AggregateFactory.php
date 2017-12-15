<?php

namespace App\Aggregate;


use App\Aggregate\Contracts\AggregateProcessorContract;
use App\Aggregate\Contracts\AggregatorContract;
use App\Aggregate\Contracts\AggregateFactoryContract;
use App\Repository\AirboxAggregatableDatasetRepository;
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
                $fields = ['pm25', 'pm10'];
                break;

            case 'epa':
                $repository = new EpaAggregatableDatasetRepository();
                $fields = ['pm25', 'pm10'];
                break;

            case 'airbox':
                $repository = new AirboxAggregatableDatasetRepository();
                $fields = ['pm25', 'pm10'];
                break;

            default:
                return;
        }

        $processor = new AirQualityAggregateProcessor($repository);
        $processor->setFields($fields);

        return $processor;
    }
}