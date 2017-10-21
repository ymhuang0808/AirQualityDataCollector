<?php

namespace App\Events;

use Carbon\Carbon;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class AirQualityMeasurementAggregationCompleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var string
     */
    private $aggregationType;

    /**
     * @var string
     */
    protected $sourceType;

    /**
     * @var Carbon
     */
    protected $startDateTime;

    /**
     * @var Carbon
     */
    protected $endDateTime;

    /**
     * Create a new event instance.
     *
     * @param string $aggregationType
     * @param string $sourceType
     * @param Carbon $startDateTime
     * @param Carbon $endDateTime
     */
    public function __construct(string $aggregationType, string $sourceType, Carbon $startDateTime, Carbon $endDateTime)
    {
        $this->aggregationType = $aggregationType;
        $this->sourceType = $sourceType;
        $this->startDateTime = $startDateTime;
        $this->endDateTime = $endDateTime;
    }

    /**
     * @return string
     */
    public function getAggregationType(): string
    {
        return $this->aggregationType;
    }

    /**
     * @return string
     */
    public function getSourceType()
    {
        return $this->sourceType;
    }

    /**
     * @return Carbon
     */
    public function getStartDateTime()
    {
        return $this->startDateTime;
    }

    /**
     * @return Carbon
     */
    public function getEndDateTime()
    {
        return $this->endDateTime;
    }
}
