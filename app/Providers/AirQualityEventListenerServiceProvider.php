<?php

namespace App\Providers;

use App\CollectionLog;
use App\Listeners\LoggingCollectAirQualityCompletedListener;
use App\Listeners\LoggingCollectSiteCompletedListener;
use App\Log\CollectionLogHandler;
use Illuminate\Support\ServiceProvider;
use Monolog\Handler\AbstractProcessingHandler;

class AirQualityEventListenerServiceProvider extends ServiceProvider
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
        // Make the CollectionLogHandler for storing log as a singleton
        $this->app
            ->singleton(CollectionLogHandler::class, function () {
               return new CollectionLogHandler(new CollectionLog);
            });

        // Binding an instance in CollectAirQualityCompletedListener class
        $this->app
            ->when(LoggingCollectAirQualityCompletedListener::class)
            ->needs(AbstractProcessingHandler::class)
            ->give(function () {
                return resolve(CollectionLogHandler::class);
            });

        // Binding an instance in LoggingCollectSiteCompletedListener class
        $this->app
            ->when(LoggingCollectSiteCompletedListener::class)
            ->needs(AbstractProcessingHandler::class)
            ->give(function () {
                return resolve(CollectionLogHandler::class);
            });
    }
}
