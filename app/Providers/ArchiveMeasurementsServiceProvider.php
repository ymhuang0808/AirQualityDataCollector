<?php

namespace App\Providers;

use App\Managers\ArchivedMeasurementsManager;
use App\Managers\ArchivedMeasurementsManagerInterface;
use App\Repository\Contracts\AggregationLogRepositoryContract;
use Illuminate\Support\ServiceProvider;

class ArchiveMeasurementsServiceProvider extends ServiceProvider
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
        $this->app->bind(ArchivedMeasurementsManagerInterface::class, function ($app) {
            return new ArchivedMeasurementsManager($app->make(AggregationLogRepositoryContract::class));
        });
    }
}
