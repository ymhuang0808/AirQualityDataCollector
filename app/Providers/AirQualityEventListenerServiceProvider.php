<?php

namespace App\Providers;

use App\AggregationLog;
use App\CollectionLog;
use App\Events\CollectSiteCompletedEvent;
use App\Listeners\CollectExceptionNotificationListener;
use App\Listeners\LoggingAirQualityAggregationCompletedListener;
use App\Listeners\LoggingCollectAirQualityCompletedListener;
use App\Listeners\LoggingCollectSiteCompletedListener;
use App\Log\AggregationLogHandler;
use App\Log\CollectionLogHandler;
use App\Recipients\AbstractRecipient;
use App\Recipients\SiteAdminRecipient;
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
               return new CollectionLogHandler();
            });
        $this->app
            ->singleton(AggregationLogHandler::class, function () {
               return new AggregationLogHandler();
            });

        // Binding an instance in CollectAirQualityCompletedListener class
        $this->app
            ->when(LoggingCollectAirQualityCompletedListener::class)
            ->needs(AbstractProcessingHandler::class)
            ->give(function () {
                return resolve(CollectionLogHandler::class);
            });
        $this->app
            ->when(LoggingAirQualityAggregationCompletedListener::class)
            ->needs(AbstractProcessingHandler::class)
            ->give(function () {
               return resolve(AggregationLogHandler::class);
            });

        // Binding an instance in LoggingCollectSiteCompletedListener class
        $this->app
            ->when(LoggingCollectSiteCompletedListener::class)
            ->needs(AbstractProcessingHandler::class)
            ->give(function () {
                return resolve(CollectionLogHandler::class);
            });

        $this->app
            ->when(CollectExceptionNotificationListener::class)
            ->needs(AbstractRecipient::class)
            ->give(function () {
                return new SiteAdminRecipient;
            });
    }
}
