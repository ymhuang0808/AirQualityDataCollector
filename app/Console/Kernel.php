<?php

namespace App\Console;

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
            'source' => ['epa'],
        ])->everyThirtyMinutes();

        // Every day at 03:40, execute collecting epa sites command
        $schedule->command(CollectSitesCommand::class, [
            'source' => ['epa'],
        ])->dailyAt('03:40');

        // Every 5 minutes, execute collecting lass and airbox command
        $schedule->command(CollectDatasetCommand::class, [
            'source' => ['lass', 'airbox'],
        ])->everyFiveMinutes();

        // Every 30 minutes, dispatch an aggregation job
        $schedule->job(new AggregateAirQualityDatasetJob('all'))
            ->everyThirtyMinutes();
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
