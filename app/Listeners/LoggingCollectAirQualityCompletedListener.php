<?php

namespace App\Listeners;

use App\Events\CollectAirQualityCompletedEvent;
use App\Log\LogChannel;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

class LoggingCollectAirQualityCompletedListener
{
    protected $log;

    /**
     * Create the event listener.
     * @param AbstractProcessingHandler $processingHandler
     */
    public function __construct(AbstractProcessingHandler $processingHandler)
    {
        $this->log = new Logger(LogChannel::COLLECT_AIR_CHANNEL);
        $this->log->pushHandler($processingHandler);
    }

    /**
     * Handle the event.
     *
     * @param  CollectAirQualityCompletedEvent  $event
     * @return void
     */
    public function handle(CollectAirQualityCompletedEvent $event)
    {
        $count = $event->dataset->count();
        $sourceType = $event->type;

        $format = 'Collected %d air quality dataset items from %s';
        $message = sprintf($format, $count, $event->type);

        // Insert a new record with time and the amount of air quality items
        $this->log->info($message, [
            'count' => $count,
            'source_type' => $sourceType,
        ]);
    }
}
