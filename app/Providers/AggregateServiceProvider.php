<?php

namespace App\Providers;

use App\Aggregate\AggregateFactory;
use App\Aggregate\AirQualityDatasetAggregator;
use App\Aggregate\Contracts\AggregateFactoryContract;
use App\Aggregate\Contracts\AggregatorContract;
use App\Repository\AggregationLogRepository;
use App\Repository\Contracts\AggregationLogRepositoryContract;
use App\Repository\Contracts\AggregationMeasurementRepositoryContract;
use App\Repository\SiteAggregationMeasurementRepository;
use Illuminate\Support\ServiceProvider;

class AggregateServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(AggregatorContract::class, AirQualityDatasetAggregator::class);

        $this->app->bind(AggregateFactoryContract::class, AggregateFactory::class);

        $this->app->bind(AggregationLogRepositoryContract::class, AggregationLogRepository::class);

        $this->app->bind(AggregationMeasurementRepositoryContract::class, SiteAggregationMeasurementRepository::class);
    }
}
