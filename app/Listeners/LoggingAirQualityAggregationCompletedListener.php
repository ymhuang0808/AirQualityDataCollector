<?php

namespace App\Listeners;

use App\Events\AirQualityMeasurementAggregationCompleted;
use App\Log\LogChannel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

class LoggingAirQualityAggregationCompletedListener implements ShouldQueue
{
    /** @var  \Monolog\Logger */
    protected $logger;

    /**
     * Create the event listener.
     * @param AbstractProcessingHandler $processingHandler
     */
    public function __construct(AbstractProcessingHandler $processingHandler)
    {
        $this->logger = new Logger(LogChannel::AGGREGATION_CHANNEL);
        $this->logger->pushHandler($processingHandler);
    }

    /**
     * Handle the event.
     *
     * @param  AirQualityMeasurementAggregationCompleted $event
     * @return void
     */
    public function handle(AirQualityMeasurementAggregationCompleted $event)
    {
        $context = [
            'aggregation_type' => $event->getAggregationType(),
            'source_type' => $event->getSourceType(),
            'start_datetime' => $event->getStartDateTime(),
            'end_datetime' => $event->getEndDateTime(),
        ];

        $this->logger->log(Logger::INFO, 'Aggregation completed', $context);
    }
}
