<?php

namespace App\Jobs;


use App\Archive\ArchivedMeasurementsManager;
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
     * @param int $startTimestamp
     * @param int $endTimestamp
     */
    public function __construct(string $source, int $startTimestamp, int $endTimestamp)
    {
        $this->source = $source;
        $this->startTimestamp = $startTimestamp;
        $this->endTimestamp = $endTimestamp;
    }

    /**
     * Execute the job.
     *
     * @param ArchiveMeasurementsProcessorContract $processor
     * @return void
     */
    public function handle(ArchiveMeasurementsProcessorContract $processor)
    {
        $class = ClassMappingHelpers::getModelBySourceType($this->source);
        $startDateTime = Carbon::createFromTimestamp($this->startTimestamp);
        $endDateTime = Carbon::createFromTimestamp($this->endTimestamp);
        $processor->setModelClass($class)->process($startDateTime, $endDateTime, 100);

        $prefix = ArchivedMeasurementsManager::LAST_EXECUTE_TIMESTAMP_SETTING_PARENT_NAME;
        $name = $prefix . $this->source;
        $timestamp = Carbon::now()->getTimestamp();
        Setting::set($name, $timestamp);
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
        $timestamp = 0;
        Setting::set($name, $timestamp);
    }

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * @return int
     */
    public function getStartTimestamp(): int
    {
        return $this->startTimestamp;
    }

    /**
     * @return int
     */
    public function getEndTimestamp(): int
    {
        return $this->endTimestamp;
    }
}
