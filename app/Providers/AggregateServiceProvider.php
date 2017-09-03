<?php

namespace App\Providers;

use App\Aggregate\AggregateFactory;
use App\Aggregate\AirQualityDatasetAggregator;
use App\Aggregate\Contracts\AggregateFactoryContract;
use App\Aggregate\Contracts\AggregatorContract;
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
        // TODO: It should use service container
        $this->app->bind(AggregatorContract::class, AirQualityDatasetAggregator::class);

        $this->app->bind(AggregateFactoryContract::class, AggregateFactory::class);
    }
}
