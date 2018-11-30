<?php

namespace App\Providers;

use App\Archive\ArchivedMeasurementsManager;
use App\Archive\ArchivedMeasurementsManagerContract;
use App\Archive\ArchiveMeasurementsProcessor;
use App\Archive\ArchiveMeasurementsProcessorContract;
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
        $this->app->bind(ArchiveMeasurementsProcessorContract::class, ArchiveMeasurementsProcessor::class);

        $this->app->bind(ArchivedMeasurementsManagerContract::class, function ($app) {
            return new ArchivedMeasurementsManager($app->make(ArchiveMeasurementsProcessorContract::class));
        });
    }
}
