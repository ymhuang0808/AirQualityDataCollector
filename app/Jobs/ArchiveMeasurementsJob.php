<?php

namespace App\Jobs;


use App\Archive\ArchivedMeasurementsManager;
use App\Archive\ArchivedMeasurementsManagerContract;
use App\Archive\ArchiveMeasurementsProcessorContract;
use Carbon\Carbon;
use ClassMappingHelpers;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Setting;

class ArchiveMeasurementsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 600;

    protected $source;

    protected $startTimestamp;

    protected $endTimestamp;

    /**
     * Create a new job instance.
     *
     * @param string $source
     */
    public function __construct(string $source)
    {
        $this->source = $source;
    }

    /**
     * Execute the job.
     *
     * @param ArchivedMeasurementsManagerContract $manager
     * @return void
     */
    public function handle(ArchivedMeasurementsManagerContract $manager)
    {
        $manager->setSourceType($this->source)->archiveAll(300);
    }

    /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        $prefix = ArchivedMeasurementsManager::LAST_JOB_DISPATCH_DATETIME_SETTING_PARENT_NAME;
        $name = $prefix . $this->source;
        $timestamp = '2017-01-01 00:00:00';
        Setting::set($name, $timestamp);
    }

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }
}
