<?php

namespace App\Console;

use App\Archive\ArchivedMeasurementsManagerContract;
use App\Console\Commands\AggregateMeasurementsCommand;
use App\Console\Commands\ArchiveMeasurementsCommand;
use App\Console\Commands\CollectDatasetCommand;
use App\Console\Commands\CollectSitesCommand;
use App\Jobs\AggregateAirQualityDatasetJob;
use App\Jobs\ArchiveMeasurementsJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Artisan;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        CollectSitesCommand::class,
        CollectDatasetCommand::class,
        AggregateMeasurementsCommand::class,
        ArchiveMeasurementsCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Every 30 minutes, execute collecting epa command
        $schedule->command(CollectDatasetCommand::class, [
            ['epa'],
        ])->everyThirtyMinutes();

        // Every day at 03:40, execute collecting epa sites command
        $schedule->command(CollectSitesCommand::class, [
            ['epa'],
        ])->dailyAt('03:40');

        // Every 5 minutes, execute collecting lass and airbox command
        $schedule->command(CollectDatasetCommand::class, [
            ['lass', 'airbox'],
        ])->everyFiveMinutes();

        // Every 30 minutes, dispatch an aggregation job
        $schedule->job(new AggregateAirQualityDatasetJob('all'))
            ->everyThirtyMinutes();

        // Every day at 01:40, dispatches archive measurements job for LASS
        $schedule->call(function () {
            /** @var ArchivedMeasurementsManagerContract $archiveMeasurementManager */
            $archiveMeasurementManager = resolve(ArchivedMeasurementsManagerContract::class);
            $archiveMeasurementManager->setSourceType('lass')->dispatchJob();
        })->dailyAt('01:40');

        // Every day at 02:40, dispatches archive measurements job for EPA
        $schedule->call(function () {
            /** @var ArchivedMeasurementsManagerContract $archiveMeasurementManager */
            $archiveMeasurementManager = resolve(ArchivedMeasurementsManagerContract::class);
            $archiveMeasurementManager->setSourceType('epa')->dispatchJob();
        })->dailyAt('02:40');

        // Every day at 03:40, dispatches archive measurements job for Airbox
        $schedule->call(function () {
            /** @var ArchivedMeasurementsManagerContract $archiveMeasurementManager */
            $archiveMeasurementManager = resolve(ArchivedMeasurementsManagerContract::class);
            $archiveMeasurementManager->setSourceType('airbox')->dispatchJob();
        })->dailyAt('03:40');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
